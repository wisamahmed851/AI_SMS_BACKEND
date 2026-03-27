<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LeaveController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = $user->role->slug;
        
        $query = Leave::with(['user', 'student.user']);

        if ($role === 'student') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'parent') {
            // Get leaves where student is a child of this parent
            $childIds = $user->parent->students()->pluck('students.id');
            $query->whereIn('student_id', $childIds);
        } elseif ($role === 'teacher') {
            $query->where('user_id', $user->id);
        }

        $leaves = $query->latest()->paginate(10);

        return view('leaves.index', compact('leaves', 'role'));
    }

    public function create(): View
    {
        $role = auth()->user()->role->slug;
        $children = [];
        
        if ($role === 'parent') {
            $children = auth()->user()->parent->students()->with('user')->get();
        }

        return view('leaves.create', compact('role', 'children'));
    }

    public function store(Request $request): RedirectResponse
    {
        $role = auth()->user()->role->slug;
        
        $validated = $request->validate([
            'reason' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'student_id' => 'nullable|exists:students,id',
        ]);

        $leave = new Leave();
        $leave->user_id = auth()->id();
        $leave->role = $role;
        $leave->reason = $validated['reason'];
        $leave->from_date = $validated['from_date'];
        $leave->to_date = $validated['to_date'];
        
        if ($role === 'student') {
            $leave->student_id = auth()->user()->student->id;
        } elseif ($role === 'parent' && !empty($validated['student_id'])) {
            $leave->student_id = $validated['student_id'];
        }

        $leave->save();

        return redirect()->route("$role.leaves.index")->with('success', 'Leave application submitted.');
    }

    public function update(Request $request, Leave $leave): RedirectResponse
    {
        // Admin or super_admin only
        abort_unless(in_array(auth()->user()->role->slug, ['admin', 'super_admin']), 403);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leave->update(['status' => $validated['status']]);

        // Redirect back to admin dashboard or admin leaves page
        return back()->with('success', 'Leave status updated.');
    }
}
