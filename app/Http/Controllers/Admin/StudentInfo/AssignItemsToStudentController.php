<?php

namespace App\Http\Controllers\Admin\StudentInfo;

use App\Http\Controllers\Controller;
use App\ItemAssignmentBatch;
use App\ParentRecommendedItem;
use App\SchoolRecommendedItem;
use App\SmClass;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\User;
use App\Models\StudentRecord;
use App\Services\MyBidhaaApiService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AssignItemsToStudentController extends Controller
{
    protected function isJsonAssignmentRequest(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }

    protected function assignmentErrorResponse(Request $request, string $message, int $status = 422)
    {
        if ($this->isJsonAssignmentRequest($request)) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        Toastr::error($message, 'Error');

        return redirect()->route('assign-items-to-student')->withInput();
    }

    protected function assignmentSuccessResponse(
        Request $request,
        int $assigned,
        int $skippedNoParent,
        int $skippedNoStudentUser
    ) {
        $message = $assigned === 0
            ? 'Items already assigned to all selected recipients.'
            : "Assigned {$assigned} item(s) to selected recipients.";

        if ($this->isJsonAssignmentRequest($request)) {
            return response()->json([
                'success' => true,
                'assigned' => $assigned,
                'skipped_no_parent' => $skippedNoParent,
                'skipped_no_student_user' => $skippedNoStudentUser,
                'message' => $message,
            ]);
        }

        if ($skippedNoParent || $skippedNoStudentUser) {
            $parts = [];
            if ($skippedNoParent) {
                $parts[] = $skippedNoParent . ' missing parent account';
            }
            if ($skippedNoStudentUser) {
                $parts[] = $skippedNoStudentUser . ' missing student user';
            }
            $message .= ' Skipped ' . implode(', ', $parts) . '.';
        }

        Toastr::success($message, 'Success');

        return redirect()->route('assign-items-to-student', ['tab' => 'assigned']);
    }

    protected function resolveStudentForAssignment(int $schoolId, $studentIdentifier): ?SmStudent
    {
        if (! $studentIdentifier) {
            return null;
        }

        return SmStudent::where('school_id', $schoolId)
            ->with('parents')
            ->where(function ($query) use ($studentIdentifier) {
                $query->where('id', $studentIdentifier)
                    ->orWhere('user_id', $studentIdentifier);
            })
            ->first();
    }

    protected function ensureAdminAccess(): void
    {
        $user = auth()->user();

        if (! $user || (int) $user->role_id !== 1) {
            abort(403);
        }
    }

    /**
     * Search products using the live MyBidhaa Products API.
     */
    public function searchProducts(Request $request, MyBidhaaApiService $api)
    {
        $keyword = trim((string) $request->input('q', ''));
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);
        $categoryId = $request->input('category_id');

        $result = $api->searchProducts([
            'keyword' => $keyword,
            'per_page' => $perPage,
            'page' => $page,
            'category_id' => $categoryId,
        ]);

        return response()->json([
            'products' => $result['data'],
            'meta' => $result['meta'],
        ]);
    }

    /**
     * List product categories for the MyBidhaa marketplace.
     *
     * Used by the UI to populate the Categories dropdown so that
     * admins can easily browse between groups such as "Special needs".
     */
    public function categories(MyBidhaaApiService $api)
    {
        $categories = $api->categories();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Admin view: search products and assign to student.
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $classes = SmClass::where('school_id', $schoolId)->orderBy('class_name')->get();

        $batches = ItemAssignmentBatch::with(['items.recommendedItem', 'creator'])
            ->where('school_id', $schoolId)
            ->orderByDesc('id')
            ->paginate(10);

        return view('backEnd.studentInformation.assign_items_to_student', compact('classes', 'batches'));
    }

    /**
     * Search students by name, admission no, etc. (for admin dropdown).
     */
    public function searchStudents(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['students' => []]);
        }

        $students = SmStudent::where('school_id', auth()->user()->school_id)
            ->where(function ($builder) use ($q) {
                $builder->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('full_name', 'like', "%{$q}%")
                    ->orWhere('admission_no', 'like', "%{$q}%");
            })
            ->with('parents')
            ->limit(20)
            ->get()
            ->map(function ($s) {
                $parent = $s->parents;

                return [
                    'id' => $s->id,
                    'user_id' => $s->user_id,
                    'full_name' => $s->full_name,
                    'admission_no' => $s->admission_no,
                    'parent_user_id' => $parent ? $parent->user_id : null,
                    'class_id' => $s->class_id,
                    'section_id' => $s->section_id,
                ];
            });

        return response()->json(['students' => $students]);
    }

    /**
     * Search sections for a given class (for \"assign to class\" scope).
     */
    public function sectionsByClass(Request $request)
    {
        $classId = $request->input('class_id');

        if (! $classId) {
            return response()->json(['sections' => []]);
        }

        $sections = SmSection::where('class_id', $classId)
            ->where('school_id', auth()->user()->school_id)
            ->orderBy('section_name')
            ->get(['id', 'section_name']);

        return response()->json(['sections' => $sections]);
    }

    /**
     * Assign selected products (from MyBidhaa API) to recipients.
     * Creates SchoolRecommendedItem if needed and ParentRecommendedItem.
     */
    public function assignToStudent(Request $request)
    {
        $request->validate([
            'scope' => 'required|in:all,class,student',
            'class_id' => 'nullable|required_if:scope,class|integer',
            'section_id' => 'nullable|integer',
            'student_id' => 'nullable|required_if:scope,student|integer',
            'products' => 'required|array',
            'products.*.id' => 'required|string',
            'products.*.name' => 'required|string',
            'products.*.category' => 'nullable|string',
            'products.*.price' => 'nullable|numeric',
            'products.*.description' => 'nullable|string',
            'products.*.image_url' => 'nullable|string',
            'products.*.quantity' => 'nullable|integer|min:1|max:999',
            'products.*.assignment_type' => 'nullable|in:recommended,required',
            'deadline' => 'nullable|date',
        ]);
        $schoolId = auth()->user()->school_id;
        $createdBy = auth()->id();

        // Resolve recipients based on scope using StudentRecord (authoritative for class/section)
        $recipientStudents = collect();
        $scope = $request->input('scope');

        if ($scope === 'all') {
            $recipientStudents = StudentRecord::where('school_id', $schoolId)
                ->where('active_status', 1)
                ->where('is_promote', 0)
                ->with(['student.parents'])
                ->get()
                ->map->student
                ->filter();
        } elseif ($scope === 'class') {
            $recipientStudents = StudentRecord::where('school_id', $schoolId)
                ->where('active_status', 1)
                ->where('is_promote', 0)
                ->where('class_id', $request->input('class_id'))
                ->when($request->filled('section_id'), function ($q) use ($request) {
                    $q->where('section_id', $request->input('section_id'));
                })
                ->with(['student.parents'])
                ->get()
                ->map->student
                ->filter();
        } else {
            $student = $this->resolveStudentForAssignment($schoolId, $request->input('student_id'));

            if (! $student) {
                return $this->assignmentErrorResponse($request, 'Selected student could not be found for this school.');
            }

            $recipientStudents = collect([$student]);
        }

        if ($recipientStudents->isEmpty()) {
            return $this->assignmentErrorResponse($request, 'No recipients found for the selected scope.');
        }

        $deadline = $request->input('deadline');
        $assigned = 0;
        $touched = 0;
        $skippedNoParent = 0;
        $skippedNoStudentUser = 0;

        // Create a batch only if there will be at least one new assignment.
        $batch = ItemAssignmentBatch::create([
            'school_id' => $schoolId,
            'created_by' => $createdBy,
            'scope' => $scope,
            'class_id' => $scope === 'class' ? $request->input('class_id') : null,
            'section_id' => $scope === 'class' ? $request->input('section_id') : null,
            'deadline' => $deadline,
        ]);

        foreach ($request->products as $p) {
            $externalId = $p['id'] ?? null;
            $name = $p['name'];
            $itemType = $p['category'] ?? 'general';
            $description = $p['description'] ?? null;
            $price = isset($p['price']) ? (float) $p['price'] : null;
            $imageUrl = $p['image_url'] ?? null;
            $productUrl = $p['product_url'] ?? null;
            $quantity = max(1, (int) ($p['quantity'] ?? 1));
            $assignmentType = in_array(($p['assignment_type'] ?? 'recommended'), ['recommended', 'required'], true)
                ? $p['assignment_type']
                : 'recommended';

            $schoolItem = SchoolRecommendedItem::firstOrCreate(
                [
                    'school_id' => $schoolId,
                    'item_name' => $name,
                ],
                [
                    'item_type' => $itemType,
                    'description' => $description,
                    'price' => $price,
                    'product_link' => $productUrl ?: $externalId,
                    'image_url' => $imageUrl,
                    'is_active' => true,
                    'created_by' => $createdBy,
                ]
            );

            foreach ($recipientStudents as $student) {
                $parentProfile = $student->parents ?: SmParent::find($student->parent_id);
                $parentUserId = optional(optional($parentProfile)->parent_user)->id ?: optional($parentProfile)->user_id;
                $studentUserId = $student->user_id;
                if (! $studentUserId) {
                    $skippedNoStudentUser++;
                    continue;
                }
                if (! $parentUserId) {
                    $skippedNoParent++;
                    continue;
                }

                $existingAssignment = ParentRecommendedItem::where('recommended_item_id', $schoolItem->id)
                    ->where('parent_id', $parentUserId)
                    ->where('student_id', $studentUserId)
                    ->first();

                if (! $existingAssignment) {
                    ParentRecommendedItem::create([
                        'recommended_item_id' => $schoolItem->id,
                        'parent_id' => $parentUserId,
                        'student_id' => $studentUserId,
                        'assignment_batch_id' => $batch->id,
                        'assigned_quantity' => $quantity,
                        'assignment_type' => $assignmentType,
                        'status' => 'pending',
                    ]);
                    $assigned++;
                    $touched++;
                } else {
                    $existingAssignment->assigned_quantity = (int) ($existingAssignment->assigned_quantity ?: 1) + $quantity;
                    $existingAssignment->assignment_type = $assignmentType;
                    $existingAssignment->assignment_batch_id = $batch->id;
                    $existingAssignment->status = 'pending';
                    $existingAssignment->save();
                    $touched++;
                }
            }
        }

        if ($touched === 0) {
            $batch->delete();
            return $this->assignmentErrorResponse($request, 'No valid recipients with parent accounts were found for the selected scope.');
        }

        return $this->assignmentSuccessResponse($request, $assigned, $skippedNoParent, $skippedNoStudentUser);
    }

    /**
     * Unassign all items in a batch.
     */
    public function unassignBatch(ItemAssignmentBatch $batch)
    {
        $this->ensureAdminAccess();

        if ($batch->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        ParentRecommendedItem::where('assignment_batch_id', $batch->id)->delete();

        Toastr::success('Batch unassigned successfully.', 'Success');

        return redirect()->route('assign-items-to-student')->with('success', 'Batch unassigned.');
    }

    /**
     * Repair legacy parent_recommended_items rows that used sm_parents.id or sm_students.id.
     */
    public function repairLegacyAssignments()
    {
        $this->ensureAdminAccess();

        $schoolId = auth()->user()->school_id;
        $rows = ParentRecommendedItem::whereHas('recommendedItem', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->get();

        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $changed = false;

            // Fix student_id if it points to sm_students.id
            $student = SmStudent::find($row->student_id);
            if ($student && $student->user_id) {
                $row->student_id = $student->user_id;
                $changed = true;
            }

            // Fix parent_id if it points to sm_parents.id
            $parent = SmParent::find($row->parent_id);
            if ($parent && $parent->user_id) {
                $row->parent_id = $parent->user_id;
                $changed = true;
            }

            if ($changed) {
                $row->save();
                $updated++;
            } else {
                $skipped++;
            }
        }

        Toastr::success("Legacy repair complete. Updated {$updated} rows, skipped {$skipped}.", 'Success');

        return redirect()->route('assign-items-to-student', ['tab' => 'assigned']);
    }

    /**
     * Reassign items from a batch using corrected parent/student user IDs.
     * Creates a new batch and replays assignments for each row.
     */
    public function reassignBatch(ItemAssignmentBatch $batch)
    {
        $this->ensureAdminAccess();

        if ($batch->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $items = ParentRecommendedItem::where('assignment_batch_id', $batch->id)->get();
        if ($items->isEmpty()) {
            Toastr::warning('This batch has no items to reassign.', 'Warning');
            return redirect()->route('assign-items-to-student');
        }

        $newBatch = ItemAssignmentBatch::create([
            'school_id' => $batch->school_id,
            'created_by' => auth()->id(),
            'scope' => $batch->scope,
            'class_id' => $batch->class_id,
            'section_id' => $batch->section_id,
            'deadline' => $batch->deadline,
        ]);

        $created = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $student = SmStudent::where('user_id', $item->student_id)->first();
            if (! $student) {
                $student = SmStudent::find($item->student_id);
            }
            if (! $student) {
                $skipped++;
                continue;
            }

            $parentUserId = optional(optional($student->parents)->parent_user)->id ?: optional($student->parents)->user_id;
            if (! $parentUserId || ! User::find($parentUserId)) {
                $skipped++;
                continue;
            }

            $existing = ParentRecommendedItem::where('recommended_item_id', $item->recommended_item_id)
                ->where('parent_id', $parentUserId)
                ->where('student_id', $student->user_id)
                ->where('assignment_batch_id', $newBatch->id)
                ->first();

            if (! $existing) {
                ParentRecommendedItem::create([
                    'recommended_item_id' => $item->recommended_item_id,
                    'parent_id' => $parentUserId,
                    'student_id' => $student->user_id,
                    'assignment_batch_id' => $newBatch->id,
                    'assigned_quantity' => (int) ($item->assigned_quantity ?: 1),
                    'assignment_type' => $item->assignment_type ?: 'recommended',
                    'status' => 'pending',
                ]);
                $created++;
            } else {
                $existing->assigned_quantity = (int) ($existing->assigned_quantity ?: 1) + (int) ($item->assigned_quantity ?: 1);
                $existing->assignment_type = $item->assignment_type ?: $existing->assignment_type;
                $existing->status = 'pending';
                $existing->save();
                $created++;
            }
        }

        Toastr::success("Reassigned {$created} item(s). Skipped {$skipped} rows without valid parents/students.", 'Success');

        return redirect()->route('assign-items-to-student', ['tab' => 'assigned']);
    }

    /**
     * Unassign a single item (all recipient rows tied to this ParentRecommendedItem record).
     */
    public function unassignItem(ParentRecommendedItem $item)
    {
        $this->ensureAdminAccess();

        $batch = $item->batch;
        if ($batch && $batch->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $item->delete();

        Toastr::success('Item unassigned successfully.', 'Success');

        return redirect()->route('assign-items-to-student')->with('success', 'Item unassigned.');
    }
}
