<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Services\FeeService;
use App\Services\ResultService;
use Illuminate\View\View;
use App\Models\Student;

class StudentController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected ResultService $resultService,
        protected FeeService $feeService
    ) {}

    public function dashboard(): View
    {
        $student = auth()->user()->student;

        $data = [
            'student' => $student->load('schoolClass'),
            'attendances' => $this->attendanceService->getAttendanceByStudent($student->id),
            'attendancePercentage' => $this->attendanceService->getAttendancePercentage($student->id),
            'results' => $this->resultService->getResultsByStudent($student->id),
            'fees' => $this->feeService->getFeesByStudent($student->id),
            'classInfo' => $student->schoolClass->load('subjects'),
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ];

        return view('student.dashboard', $data);
    }

    // --- Admin CRUD Operations ---

    public function index(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $students = Student::with(['user', 'schoolClass', 'parents.user'])->latest()->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $classes = \App\Models\SchoolClass::all();
        $parents = \App\Models\ParentModel::with('user')->get();
        return view('students.create', compact('classes', 'parents'));
    }

    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'class_id' => 'required|exists:school_classes,id',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:parents,id'
        ]);

        $role = \App\Models\Role::where('slug', 'student')->firstOrFail();

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $validated['class_id'],
        ]);

        if (!empty($validated['parent_ids'])) {
            $student->parents()->attach($validated['parent_ids']);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $classes = \App\Models\SchoolClass::all();
        $parents = \App\Models\ParentModel::with('user')->get();
        return view('students.edit', compact('student', 'classes', 'parents'));
    }

    public function update(\Illuminate\Http\Request $request, Student $student): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user->id,
            'class_id' => 'required|exists:school_classes,id',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'exists:parents,id'
        ]);

        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $student->update([
            'class_id' => $validated['class_id'],
        ]);

        $student->parents()->sync($validated['parent_ids'] ?? []);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $student->user->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
