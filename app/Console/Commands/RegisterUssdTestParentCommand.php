<?php

namespace App\Console\Commands;

use App\SmParent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RegisterUssdTestParentCommand extends Command
{
    protected $signature = 'ussd:register-test-parent
                            {phone : E.164 or local, e.g. +254701280676 or 0701280676}
                            {--school= : sm_schools.id (defaults to USSD_DEFAULT_SCHOOL_ID or 1)}';

    protected $description = 'Create or update a parent user + sm_parents row so USSD MSISDN lookup succeeds (testing).';

    public function handle(): int
    {
        $phone = trim((string) $this->argument('phone'));
        $parsed = $this->parseKenyaPhone($phone);
        if ($parsed === null) {
            $this->error('Could not parse as a Kenya MSISDN (use 254…, 07…, or +254…).');

            return self::FAILURE;
        }

        $primary = $parsed['primary'];
        $variants = $parsed['variants'];

        $schoolId = $this->option('school');
        if ($schoolId === null || $schoolId === '') {
            $envSchool = config('ussd.default_school_id');
            $schoolId = ($envSchool !== null && $envSchool !== '') ? (int) $envSchool : 1;
        } else {
            $schoolId = (int) $schoolId;
        }

        if (! DB::table('sm_schools')->where('id', $schoolId)->exists()) {
            $this->error("School id {$schoolId} not found in sm_schools.");

            return self::FAILURE;
        }

        $roleId = (int) config('ussd.parent_role_id', 3);
        if (! DB::table('infix_roles')->where('id', $roleId)->exists()) {
            $this->error("Parent role_id {$roleId} not found in infix_roles.");

            return self::FAILURE;
        }

        $conflict = $this->findAnyUserByPhone($variants);
        if ($conflict && (int) $conflict->role_id !== $roleId) {
            $this->error("Phone already belongs to user #{$conflict->id} with role_id={$conflict->role_id}. Remove or change that user first.");

            return self::FAILURE;
        }

        $academicId = (int) (DB::table('sm_academic_years')
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->value('id')
            ?? DB::table('sm_academic_years')->orderByDesc('id')->value('id')
            ?? 1);

        $user = $this->findExistingParentByPhone($variants, $roleId, $schoolId);

        if ($user) {
            $this->info("Updating existing user #{$user->id}");
            $user->phone_number = $primary;
            if (Schema::hasColumn($user->getTable(), 'phone')) {
                $user->phone = $primary;
            }
            $user->role_id = $roleId;
            $user->school_id = $schoolId;
            $user->active_status = 1;
            if (empty($user->full_name)) {
                $user->full_name = 'USSD Test Parent';
            }
            $user->save();
        } else {
            $username = 'ussd_p_'.substr(hash('sha256', $primary), 0, 20);
            $email = $username.'@placeholder.kuzza';

            $user = new User;
            $user->full_name = 'USSD Test Parent';
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
            $this->info("Created user #{$user->id}");
        }

        $parentRow = SmParent::withoutGlobalScopes()->where('user_id', $user->id)->first();
        if (! $parentRow) {
            $parentRow = new SmParent;
            $parentRow->user_id = $user->id;
        }
        $parentRow->fathers_name = $user->full_name ?? 'USSD Test Parent';
        $parentRow->fathers_mobile = $primary;
        $parentRow->school_id = $schoolId;
        $parentRow->academic_id = $academicId;
        $parentRow->active_status = 1;
        $parentRow->save();

        $this->line('Phone stored as: '.$primary);
        $this->line('Matched variants for USSD: '.implode(', ', $variants));
        $this->line("school_id={$schoolId}, parent role_id={$roleId}");
        $this->warn('Link at least one active student to this parent in Admin if USSD should show assigned items.');

        return self::SUCCESS;
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

    /**
     * @param  list<string>  $variants
     */
    /**
     * @param  list<string>  $variants
     */
    protected function findAnyUserByPhone(array $variants): ?User
    {
        $q = User::withoutGlobalScopes()->where(function ($query) use ($variants): void {
            foreach ($variants as $v) {
                $query->orWhere('phone_number', $v);
                if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                    $query->orWhere('phone', $v);
                }
            }
        });

        return $q->first();
    }

    /**
     * @param  list<string>  $variants
     */
    protected function findExistingParentByPhone(array $variants, int $roleId, int $schoolId): ?User
    {
        $q = User::withoutGlobalScopes()->where('role_id', $roleId)->where('school_id', $schoolId);

        $q->where(function ($query) use ($variants): void {
            foreach ($variants as $v) {
                $query->orWhere('phone_number', $v);
                if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                    $query->orWhere('phone', $v);
                }
            }
        });

        $hit = $q->first();
        if ($hit) {
            return $hit;
        }

        $q2 = User::withoutGlobalScopes()->where('role_id', $roleId)->where(function ($query) use ($variants): void {
            foreach ($variants as $v) {
                $query->orWhere('phone_number', $v);
                if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                    $query->orWhere('phone', $v);
                }
            }
        });

        return $q2->first();
    }
}
