<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->get('date', now()->toDateString());
        $teachers = Teacher::with('user')->get();
        $attendances = TeacherAttendance::where('date', $date)->get()->keyBy('teacher_id');

        return view('teacher_attendances.index', compact('teachers', 'attendances', 'date'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late'
        ]);

        $date = $validated['date'];

        foreach ($validated['attendance'] as $teacherId => $data) {
            TeacherAttendance::updateOrCreate(
                ['teacher_id' => $teacherId, 'date' => $date],
                ['status' => $data['status']]
            );
        }

        return redirect()->route('admin.teacher-attendances.index', ['date' => $date])
            ->with('success', 'Teacher attendance marked successfully.');
    }
}
