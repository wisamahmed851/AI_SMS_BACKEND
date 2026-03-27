<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    public function markAttendance(int $studentId, string $date, string $status): Attendance
    {
        return Attendance::updateOrCreate(
            ['student_id' => $studentId, 'date' => $date],
            ['status' => $status]
        );
    }

    public function bulkMarkAttendance(array $attendanceData, string $date): void
    {
        foreach ($attendanceData as $studentId => $status) {
            $this->markAttendance($studentId, $date, $status);
        }
    }

    public function getAttendanceByStudent(int $studentId): Collection
    {
        return Attendance::where('student_id', $studentId)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getAttendanceByClass(int $classId, string $date): Collection
    {
        return Attendance::whereHas('student', function ($q) use ($classId) {
            $q->where('class_id', $classId);
        })->where('date', $date)->get();
    }

    public function getAttendancePercentage(?int $studentId = null): float
    {
        $query = Attendance::query();

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $total = $query->count();
        if ($total === 0) return 0;

        $present = (clone $query)->where('status', 'present')->count();
        // Need to recount present from scratch
        $presentQuery = Attendance::where('status', 'present');
        if ($studentId) {
            $presentQuery->where('student_id', $studentId);
        }
        $present = $presentQuery->count();

        return round(($present / $total) * 100, 2);
    }

    public function getOverallAttendancePercentage(): float
    {
        $total = Attendance::count();
        if ($total === 0) return 0;

        $present = Attendance::where('status', 'present')->count();
        return round(($present / $total) * 100, 2);
    }
}
