<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Fee;
use App\Models\ParentModel;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\AttendanceService;
use App\Services\FeeService;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected FeeService $feeService
    ) {}

    public function dashboard(): View
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalClasses' => SchoolClass::count(),
            'totalParents' => ParentModel::count(),
            'totalSubjects' => Subject::count(),
            'totalExams' => Exam::count(),
            'totalRevenue' => $this->feeService->getTotalRevenue(),
            'totalPending' => $this->feeService->getTotalPending(),
            'attendancePercentage' => $this->attendanceService->getOverallAttendancePercentage(),
            'students' => Student::with(['user', 'schoolClass'])->get(),
            'teachers' => Teacher::with(['user', 'subjects'])->get(),
            'classes' => SchoolClass::withCount('students')->get(),
            'fees' => Fee::with('student.user')->latest()->take(10)->get(),
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
