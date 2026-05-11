<?php

namespace App\Console\Commands;

use App\ParentRecommendedItem;
use App\SmParent;
use App\SmStudent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

/**
 * Verifies MSISDN → parent → students → parent_recommended_items for USSD support.
 */
class DiagnoseUssdParentCommand extends Command
{
    protected $signature = 'ussd:diagnose-parent
                            {phone : e.g. 0701280676 or +254701280676}
                            {--school= : Optional sm_schools.id filter}';

    protected $description = 'Show parent user, students, and assignable USSD items for a phone number.';

    public function handle(): int
    {
        $phone = trim((string) $this->argument('phone'));
        $schoolFilter = $this->option('school');

        $variants = $this->phoneVariants($phone);
        if ($variants === []) {
            $this->error('Could not derive MSISDN variants from input.');

            return self::FAILURE;
        }

        $roleId = (int) config('ussd.parent_role_id', 3);
        $q = User::withoutGlobalScopes()->where('role_id', $roleId);
        $q->where(function ($sub) use ($variants): void {
            foreach ($variants as $v) {
                $sub->orWhere('phone_number', $v);
                if (Schema::hasColumn((new User)->getTable(), 'phone')) {
                    $sub->orWhere('phone', $v);
                }
            }
        });
        if ($schoolFilter !== null && $schoolFilter !== '') {
            $q->where('school_id', (int) $schoolFilter);
        }

        $parent = $q->first();
        if (! $parent) {
            $this->warn('No parent user found for this phone (role_id='.$roleId.'). Variants tried: '.implode(', ', $variants));

            return self::FAILURE;
        }

        $this->info("Parent users.id={$parent->id} school_id={$parent->school_id} name=".($parent->full_name ?? ''));

        $profile = SmParent::withoutGlobalScopes()->where('user_id', $parent->id)->first();
        if (! $profile) {
            $this->error('No sm_parents row for this user_id.');

            return self::FAILURE;
        }

        $this->line("sm_parents.id={$profile->id}");

        $students = SmStudent::withoutGlobalScopes()
            ->where('parent_id', $profile->id)
            ->where('active_status', 1)
            ->with('user')
            ->orderBy('id')
            ->get();

        if ($students->isEmpty()) {
            $this->warn('No active sm_students for this parent.');

            return self::FAILURE;
        }

        foreach ($students as $s) {
            $uname = $s->user?->full_name ?? trim(($s->first_name ?? '').' '.($s->last_name ?? ''));
            $this->line("  Student sm_students.id={$s->id} user_id={$s->user_id} name={$uname}");
            $keys = array_unique([(int) $s->user_id, (int) $s->id]);
            $n = ParentRecommendedItem::query()
                ->whereIn('parent_id', [(int) $parent->id, (int) $profile->id])
                ->whereIn('student_id', $keys)
                ->whereIn('status', ['pending', 'selected_for_order'])
                ->count();
            $this->line("    Assignable parent_recommended_items (pending/selected): {$n}");
        }

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    protected function phoneVariants(string $raw): array
    {
        $digits = preg_replace('/\D+/', '', trim($raw)) ?? '';
        if ($digits === '') {
            return [];
        }

        $variants = [$raw, $digits, '+'.$digits];

        if (str_starts_with($digits, '254')) {
            $variants[] = '0'.substr($digits, 3);
            $variants[] = '+254'.substr($digits, 3);
        }

        if (str_starts_with($digits, '0') && strlen($digits) >= 10) {
            $variants[] = '254'.substr($digits, 1);
            $variants[] = '+254'.substr($digits, 1);
        }

        if (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            $variants[] = '254'.$digits;
            $variants[] = '+254'.$digits;
            $variants[] = '0'.$digits;
        }

        return array_values(array_unique(array_filter($variants)));
    }
}
