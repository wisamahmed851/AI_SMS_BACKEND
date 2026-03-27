<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\StoreResultRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Exam;
use App\Services\AttendanceService;
use App\Services\ResultService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected ResultService $resultService
    ) {}

    public function dashboard(): View
    {
        $teacher = auth()->user()->teacher;
        $subjects = $teacher->subjects()->with('classes')->get();

        // Get unique class IDs from assigned subjects
        $classIds = $subjects->pluck('classes')->flatten()->pluck('id')->unique();
        $classes = SchoolClass::whereIn('id', $classIds)->withCount('students')->get();

        $data = [
            'teacher' => $teacher,
            'subjects' => $subjects,
            'classes' => $classes,
            'exams' => Exam::all(),
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ];

        return view('teacher.dashboard', $data);
    }

    public function attendance(Request $request): View
    {
        $teacher = auth()->user()->teacher;
        $subjects = $teacher->subjects()->with('classes')->get();
        $classIds = $subjects->pluck('classes')->flatten()->pluck('id')->unique();
        $classes = SchoolClass::whereIn('id', $classIds)->get();

        $selectedClassId = $request->get('class_id');
        $date = $request->get('date', now()->toDateString());
        $students = collect();
        $existingAttendance = collect();

        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)->with('user')->get();
            $existingAttendance = $this->attendanceService->getAttendanceByClass($selectedClassId, $date)
                ->keyBy('student_id');
        }

        return view('teacher.attendance', compact('classes', 'students', 'existingAttendance', 'selectedClassId', 'date'));
    }

    public function storeAttendance(StoreAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->attendanceService->bulkMarkAttendance(
            $validated['attendance'],
            $validated['date']
        );

        return redirect()->route('teacher.attendance', [
            'class_id' => $validated['class_id'],
            'date' => $validated['date'],
        ])->with('success', 'Attendance marked successfully!');
    }

    public function results(Request $request): View
    {
        $teacher = auth()->user()->teacher;
        $subjects = $teacher->subjects;
        $exams = Exam::all();

        $selectedSubjectId = $request->get('subject_id');
        $selectedExamId = $request->get('exam_id');
        $results = collect();

        if ($selectedSubjectId && $selectedExamId) {
            // Get classes that have this subject
            $classIds = DB::table('class_subject')
                ->where('subject_id', $selectedSubjectId)
                ->pluck('class_id');

            $students = Student::whereIn('class_id', $classIds)->with('user')->get();

            $existingResults = \App\Models\Result::where('subject_id', $selectedSubjectId)
                ->where('exam_id', $selectedExamId)
                ->get()
                ->keyBy('student_id');

            return view('teacher.results', compact('subjects', 'exams', 'students', 'existingResults', 'selectedSubjectId', 'selectedExamId'));
        }

        return view('teacher.results', compact('subjects', 'exams', 'results', 'selectedSubjectId', 'selectedExamId'));
    }

    public function storeResults(StoreResultRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->resultService->bulkStoreResults($validated['results']);

        return back()->with('success', 'Results saved successfully!');
    }

    // --- Admin CRUD Operations ---

    public function index(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $teachers = \App\Models\Teacher::with(['user', 'subjects'])->latest()->paginate(15);
        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $subjects = \App\Models\Subject::all();
        return view('teachers.create', compact('subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        $role = \App\Models\Role::where('slug', 'teacher')->firstOrFail();

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);

        $teacher = \App\Models\Teacher::create([
            'user_id' => $user->id,
        ]);

        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->attach($validated['subject_ids']);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function edit(\App\Models\Teacher $teacher): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $subjects = \App\Models\Subject::all();
        return view('teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, \App\Models\Teacher $teacher): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $teacher->subjects()->sync($validated['subject_ids'] ?? []);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(\App\Models\Teacher $teacher): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $teacher->user->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
