<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Services\FeeService;
use App\Services\ResultService;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected ResultService $resultService,
        protected FeeService $feeService
    ) {}

    public function dashboard(): View
    {
        $parent = auth()->user()->parent;
        $children = $parent->students()->with(['user', 'schoolClass'])->get();

        $childrenData = [];
        foreach ($children as $child) {
            $childrenData[] = [
                'student' => $child,
                'attendancePercentage' => $this->attendanceService->getAttendancePercentage($child->id),
                'attendances' => $this->attendanceService->getAttendanceByStudent($child->id),
                'results' => $this->resultService->getResultsByStudent($child->id),
                'fees' => $this->feeService->getFeesByStudent($child->id),
            ];
        }

        $data = [
            'parent' => $parent,
            'childrenData' => $childrenData,
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ];

        return view('parent.dashboard', $data);
    }

    // --- Admin CRUD Operations for Parents ---
    
    public function index(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $parents = \App\Models\ParentModel::with(['user', 'students.user'])->latest()->paginate(15);
        return view('parents.index', compact('parents'));
    }

    public function create(): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $students = \App\Models\Student::with('user')->get();
        return view('parents.create', compact('students'));
    }

    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $role = \App\Models\Role::where('slug', 'parent')->firstOrFail();

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);

        $parent = \App\Models\ParentModel::create([
            'user_id' => $user->id,
        ]);

        if (!empty($validated['student_ids'])) {
            $parent->students()->attach($validated['student_ids']);
        }

        return redirect()->route('admin.parents.index')->with('success', 'Parent account created successfully.');
    }

    public function edit(\App\Models\ParentModel $parent): View
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $students = \App\Models\Student::with('user')->get();
        return view('parents.edit', compact('parent', 'students'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\ParentModel $parent): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user->id,
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $parent->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $parent->students()->sync($validated['student_ids'] ?? []);

        return redirect()->route('admin.parents.index')->with('success', 'Parent account updated successfully.');
    }

    public function destroy(\App\Models\ParentModel $parent): \Illuminate\Http\RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        $parent->user->delete();
        return redirect()->route('admin.parents.index')->with('success', 'Parent deleted successfully.');
    }
}
