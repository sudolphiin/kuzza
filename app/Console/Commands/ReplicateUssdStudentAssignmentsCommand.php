<?php

namespace App\Console\Commands;

use App\ParentRecommendedItem;
use App\SmParent;
use App\SmStudent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Copies pending / selected_for_order parent_recommended_items from one student to another
 * under the same parent (e.g. Khloe → Klaus) when admin only assigned one sibling.
 */
class ReplicateUssdStudentAssignmentsCommand extends Command
{
    protected $signature = 'ussd:replicate-assignments
                            {--parent-user= : Parent users.id}
                            {--from-student-user= : Source student users.id}
                            {--to-student-user= : Target student users.id}
                            {--dry-run : Show what would be created without saving}';

    protected $description = 'Duplicate assignable recommended-item rows from one student to a sibling (same parent).';

    public function handle(): int
    {
        $parentUserId = (int) $this->option('parent-user');
        $fromUserId = (int) $this->option('from-student-user');
        $toUserId = (int) $this->option('to-student-user');
        $dryRun = (bool) $this->option('dry-run');

        if ($parentUserId <= 0 || $fromUserId <= 0 || $toUserId <= 0) {
            $this->error('Required: --parent-user= --from-student-user= --to-student-user=');
            $this->line('Example: php artisan ussd:replicate-assignments --parent-user=6 --from-student-user=7 --to-student-user=8');

            return self::FAILURE;
        }

        if ($fromUserId === $toUserId) {
            $this->error('from and to student user ids must differ.');

            return self::FAILURE;
        }

        $parent = User::withoutGlobalScopes()->find($parentUserId);
        if (! $parent) {
            $this->error("Parent user #{$parentUserId} not found.");

            return self::FAILURE;
        }

        $profile = SmParent::withoutGlobalScopes()->where('user_id', $parentUserId)->first();
        if (! $profile) {
            $this->error('No sm_parents row for this parent.');

            return self::FAILURE;
        }

        $parentKeys = array_values(array_unique([(int) $parentUserId, (int) $profile->id]));
        $fromKeys = $this->studentIdKeys($fromUserId);
        $toKeys = $this->studentIdKeys($toUserId);

        if ($fromKeys === [] || $toKeys === []) {
            $this->error('Could not resolve student id keys (user_id / sm_students.id).');

            return self::FAILURE;
        }

        foreach ([$fromUserId, $toUserId] as $uid) {
            $stu = SmStudent::withoutGlobalScopes()
                ->where('parent_id', $profile->id)
                ->where('user_id', $uid)
                ->where('active_status', 1)
                ->first();
            if (! $stu) {
                $this->error("Student users.id={$uid} is not an active child of this parent.");

                return self::FAILURE;
            }
        }

        $items = ParentRecommendedItem::query()
            ->whereIn('parent_id', $parentKeys)
            ->whereIn('student_id', $fromKeys)
            ->whereIn('status', ['pending', 'selected_for_order'])
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            $this->warn('No pending/selected_for_order rows found for the source student.');

            return self::FAILURE;
        }

        $created = 0;
        $skipped = 0;

        foreach ($items as $row) {
            $exists = ParentRecommendedItem::query()
                ->where('recommended_item_id', $row->recommended_item_id)
                ->where('parent_id', $parentUserId)
                ->where('student_id', $toUserId)
                ->whereIn('status', ['pending', 'selected_for_order', 'already_bought'])
                ->exists();

            if ($exists) {
                $skipped++;
                $this->line("Skip recommended_item_id={$row->recommended_item_id}: already on target.");

                continue;
            }

            if ($dryRun) {
                $this->line("[dry-run] Would create copy of parent_recommended_items.id={$row->id} → student_id={$toUserId}");
                $created++;

                continue;
            }

            DB::beginTransaction();
            try {
                ParentRecommendedItem::create([
                    'recommended_item_id' => $row->recommended_item_id,
                    'parent_id' => $parentUserId,
                    'student_id' => $toUserId,
                    'assignment_batch_id' => $row->assignment_batch_id,
                    'assigned_quantity' => (int) ($row->assigned_quantity ?: 1),
                    'assignment_type' => $row->assignment_type ?: 'recommended',
                    'status' => 'pending',
                ]);
                DB::commit();
                $created++;
                $this->info("Created row for recommended_item_id={$row->recommended_item_id} → student users.id={$toUserId}");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error('Failed: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        $this->info("Done. Created {$created}, skipped {$skipped}.".($dryRun ? ' (dry-run)' : ''));
        $this->line('Run: php artisan ussd:diagnose-parent … to verify counts.');

        return self::SUCCESS;
    }

    /**
     * @return list<int>
     */
    protected function studentIdKeys(int $studentUserId): array
    {
        if ($studentUserId <= 0) {
            return [];
        }
        $ids = [$studentUserId];
        $sid = SmStudent::withoutGlobalScopes()->where('user_id', $studentUserId)->value('id');
        if ($sid && (int) $sid !== $studentUserId) {
            $ids[] = (int) $sid;
        }

        return array_values(array_unique(array_filter($ids)));
    }
}
