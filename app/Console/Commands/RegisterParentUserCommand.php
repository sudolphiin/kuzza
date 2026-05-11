<?php

namespace App\Console\Commands;

use App\SmParent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RegisterParentUserCommand extends Command
{
    protected $signature = 'parent:register
                            {full_name : Parent full name}
                            {phone : Kenya phone e.g. 0701280676}
                            {email : Unique login email}
                            {--school=1 : sm_schools.id}';

    protected $description = 'Create or update a parent (users + sm_parents) with name, phone, and email.';

    public function handle(): int
    {
        $fullName = trim((string) $this->argument('full_name'));
        $phoneRaw = trim((string) $this->argument('phone'));
        $email = strtolower(trim((string) $this->argument('email')));

        if ($fullName === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid full name or email.');

            return self::FAILURE;
        }

        $parsed = $this->parseKenyaPhone($phoneRaw);
        if ($parsed === null) {
            $this->error('Could not parse phone as Kenya MSISDN (07… / 254… / +254…).');

            return self::FAILURE;
        }

        $primary = $parsed['primary'];
        $variants = $parsed['variants'];

        $schoolId = (int) $this->option('school');
        if (! DB::table('sm_schools')->where('id', $schoolId)->exists()) {
            $this->error("School id {$schoolId} not found in sm_schools.");

            return self::FAILURE;
        }

        $roleId = (int) config('ussd.parent_role_id', 3);
        if (! DB::table('infix_roles')->where('id', $roleId)->exists()) {
            $this->error("Parent role_id {$roleId} not found in infix_roles.");

            return self::FAILURE;
        }

        $emailOwner = User::withoutGlobalScopes()->where('email', $email)->first();
        $phoneOwner = $this->findParentByPhoneVariants($variants, $roleId);

        if ($emailOwner && $phoneOwner && (int) $emailOwner->id !== (int) $phoneOwner->id) {
            $this->error("Email belongs to user #{$emailOwner->id} but phone belongs to user #{$phoneOwner->id}. Resolve manually.");

            return self::FAILURE;
        }

        $nonParentPhone = $this->findAnyUserByPhone($variants);
        if ($nonParentPhone && (int) $nonParentPhone->role_id !== $roleId) {
            $this->error("Phone already used by user #{$nonParentPhone->id} (role_id={$nonParentPhone->role_id}).");

            return self::FAILURE;
        }

        if ($emailOwner && (int) $emailOwner->role_id !== $roleId) {
            $this->error("Email already used by user #{$emailOwner->id} (role_id={$emailOwner->role_id}).");

            return self::FAILURE;
        }

        $user = $phoneOwner ?? $emailOwner;

        $academicId = (int) (DB::table('sm_academic_years')
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->value('id')
            ?? DB::table('sm_academic_years')->orderByDesc('id')->value('id')
            ?? 1);

        $username = $this->uniqueUsernameFromEmail($email, $user?->id);

        if ($user) {
            $this->info("Updating existing parent user #{$user->id}");
            $user->full_name = $fullName;
            $user->email = $email;
            $user->username = $username;
            $user->phone_number = $primary;
            if (Schema::hasColumn($user->getTable(), 'phone')) {
                $user->phone = $primary;
            }
            $user->role_id = $roleId;
            $user->school_id = $schoolId;
            $user->active_status = 1;
            $user->save();
        } else {
            if (User::withoutGlobalScopes()->where('email', $email)->exists()) {
                $this->error('Email already taken by another user.');

                return self::FAILURE;
            }

            $user = new User;
            $user->full_name = $fullName;
            $user->username = $username;
            $user->email = $email;
            $user->password = Hash::make(bin2hex(random_bytes(8)));
            $user->phone_number = $primary;
            if (Schema::hasColumn($user->getTable(), 'phone')) {
                $user->phone = $primary;
            }
            $user->role_id = $roleId;
            $user->school_id = $schoolId;
            $user->active_status = 1;
            $user->is_administrator = 'no';
            $user->created_by = 1;
            $user->updated_by = 1;
            $user->save();
            $this->info("Created parent user #{$user->id}");
        }

        $parentRow = SmParent::withoutGlobalScopes()->where('user_id', $user->id)->first();
        if (! $parentRow) {
            $parentRow = new SmParent;
            $parentRow->user_id = $user->id;
        }
        $parentRow->fathers_name = $fullName;
        $parentRow->fathers_mobile = $primary;
        $parentRow->guardians_name = $fullName;
        $parentRow->guardians_mobile = $primary;
        $parentRow->guardians_email = $email;
        $parentRow->school_id = $schoolId;
        $parentRow->academic_id = $academicId;
        $parentRow->active_status = 1;
        $parentRow->save();

        $this->line("Name: {$fullName}");
        $this->line("Phone: {$primary}");
        $this->line("Email: {$email}");
        $this->line("Username: {$username}");
        $this->line("school_id={$schoolId}, users.id={$user->id}");

        return self::SUCCESS;
    }

    protected function uniqueUsernameFromEmail(string $email, ?int $exceptUserId): string
    {
        $base = explode('@', $email, 2)[0];
        $base = preg_replace('/[^a-zA-Z0-9._-]/', '', $base) ?: 'parent';
        $base = substr($base, 0, 100);

        $candidate = $base;
        $n = 0;
        while (User::withoutGlobalScopes()
            ->where('username', $candidate)
            ->when($exceptUserId, fn ($q) => $q->where('id', '!=', $exceptUserId))
            ->exists()) {
            $n++;
            $candidate = substr($base, 0, 80).'_'.$n;
        }

        return $candidate;
    }

    /**
     * @param  list<string>  $variants
     */
    protected function findParentByPhoneVariants(array $variants, int $roleId): ?User
    {
        return User::withoutGlobalScopes()
            ->where('role_id', $roleId)
            ->where(function ($query) use ($variants): void {
                foreach ($variants as $v) {
                    $query->orWhere('phone_number', $v);
                    if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                        $query->orWhere('phone', $v);
                    }
                }
            })
            ->first();
    }

    /**
     * @param  list<string>  $variants
     */
    protected function findAnyUserByPhone(array $variants): ?User
    {
        return User::withoutGlobalScopes()
            ->where(function ($query) use ($variants): void {
                foreach ($variants as $v) {
                    $query->orWhere('phone_number', $v);
                    if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                        $query->orWhere('phone', $v);
                    }
                }
            })
            ->first();
    }

    /**
     * @return array{primary: string, variants: list<string>}|null
     */
    protected function parseKenyaPhone(string $raw): ?array
    {
        $digits = preg_replace('/\D+/', '', trim($raw)) ?? '';
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '254')) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '0') && strlen($digits) >= 10) {
            $normalized = '254'.substr($digits, 1);
        } elseif (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            $normalized = '254'.$digits;
        } else {
            return null;
        }

        if (strlen($normalized) < 12) {
            return null;
        }

        $primary = '+'.$normalized;
        $local = '0'.substr($normalized, 3);

        $variants = array_values(array_unique(array_filter([
            $primary,
            $normalized,
            $local,
            substr($normalized, 3),
        ])));

        return ['primary' => $primary, 'variants' => $variants];
    }
}
