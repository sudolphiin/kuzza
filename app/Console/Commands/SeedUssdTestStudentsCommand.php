<?php

namespace App\Console\Commands;

use App\SmParent;
use App\SmStudent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SeedUssdTestStudentsCommand extends Command
{
    protected $signature = 'ussd:seed-test-students
                            {--parent-user=6 : Parent users.id (USSD test parent)}
                            {--school=1 : sm_schools.id}';

    protected $description = 'Create two active students (Khloe & Klaus) linked to a parent for USSD testing.';

    public function handle(): int
    {
        $parentUserId = (int) $this->option('parent-user');
        $schoolId = (int) $this->option('school');

        $parentUser = User::withoutGlobalScopes()->find($parentUserId);
        if (! $parentUser) {
            $this->error("Parent user #{$parentUserId} not found.");

            return self::FAILURE;
        }

        $parentProfile = SmParent::withoutGlobalScopes()->where('user_id', $parentUserId)->first();
        if (! $parentProfile) {
            $this->error('No sm_parents row for this user. Run: php artisan ussd:register-test-parent …');

            return self::FAILURE;
        }

        $studentRoleId = (int) (DB::table('infix_roles')->where('name', 'Student')->value('id') ?? 2);

        $academicId = (int) (DB::table('sm_academic_years')
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->value('id')
            ?? DB::table('sm_academic_years')->orderByDesc('id')->value('id')
            ?? 1);

        $pairs = [
            ['Khloe', 'Test'],
            ['Klaus', 'Test'],
        ];

        foreach ($pairs as [$first, $last]) {
            $existing = SmStudent::withoutGlobalScopes()
                ->where('parent_id', $parentProfile->id)
                ->where('first_name', $first)
                ->where('last_name', $last)
                ->where('active_status', 1)
                ->first();

            if ($existing) {
                $this->line("Skip {$first} {$last}: already linked (student id {$existing->id}, user_id {$existing->user_id}).");

                continue;
            }

            DB::beginTransaction();
            try {
                $admissionNo = $this->nextAdmissionNo($schoolId);
                $username = 'stu_ussd_'.$parentProfile->id.'_'.$first.'_'.substr(sha1($first.$last), 0, 6);
                $email = $username.'@placeholder.kuzza';

                $user = new User;
                $user->full_name = $first.' '.$last;
                $user->username = $username;
                $user->email = $email;
                $user->password = Hash::make(bin2hex(random_bytes(6)));
                $user->role_id = $studentRoleId;
                $user->school_id = $schoolId;
                $user->active_status = 1;
                $user->is_administrator = 'no';
                $user->created_by = 1;
                $user->updated_by = 1;
                if (Schema::hasColumn($user->getTable(), 'phone_number')) {
                    $user->phone_number = null;
                }
                $user->save();

                $student = new SmStudent;
                $student->first_name = $first;
                $student->last_name = $last;
                $student->full_name = $first.' '.$last;
                $student->user_id = $user->id;
                $student->parent_id = $parentProfile->id;
                $student->role_id = $studentRoleId;
                $student->school_id = $schoolId;
                $student->academic_id = $academicId;
                $student->session_id = $academicId;
                $student->admission_no = $admissionNo;
                $student->active_status = 1;
                $student->save();

                DB::commit();
                $this->info("Created {$first} {$last}: users.id={$user->id}, sm_students.id={$student->id}, admission_no={$admissionNo}");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("Failed {$first} {$last}: ".$e->getMessage());

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }

    protected function nextAdmissionNo(int $schoolId): int
    {
        $max = (int) (SmStudent::withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->max('admission_no') ?? 0);

        return max(880000, $max + 1);
    }
}
