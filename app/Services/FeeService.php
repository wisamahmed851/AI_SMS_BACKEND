<?php

namespace App\Services;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Collection;

class FeeService
{
    public function createFee(array $data): Fee
    {
        return Fee::create($data);
    }

    public function getFeesByStudent(int $studentId): Collection
    {
        return Fee::where('student_id', $studentId)->get();
    }

    public function markAsPaid(int $feeId): Fee
    {
        $fee = Fee::findOrFail($feeId);
        $fee->update(['status' => 'paid']);
        return $fee;
    }

    public function getTotalRevenue(): float
    {
        return Fee::where('status', 'paid')->sum('amount');
    }

    public function getTotalPending(): float
    {
        return Fee::where('status', 'unpaid')->sum('amount');
    }

    public function getAllFees(): Collection
    {
        return Fee::with('student.user')->get();
    }
}
