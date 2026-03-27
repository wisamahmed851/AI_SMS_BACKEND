<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\FeeService;
use Illuminate\View\View;

class SuperAdminController extends Controller
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
            'totalRevenue' => $this->feeService->getTotalRevenue(),
            'totalPending' => $this->feeService->getTotalPending(),
            'attendancePercentage' => $this->attendanceService->getOverallAttendancePercentage(),
            'totalUsers' => User::count(),
            'recentNotifications' => auth()->user()->notifications()->latest()->take(5)->get(),
        ];

        return view('super-admin.dashboard', $data);
    }
}
