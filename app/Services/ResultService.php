<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Database\Eloquent\Collection;

class ResultService
{
    public function storeResult(array $data): Result
    {
        return Result::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'subject_id' => $data['subject_id'],
                'exam_id' => $data['exam_id'],
            ],
            ['marks' => $data['marks']]
        );
    }

    public function bulkStoreResults(array $results): void
    {
        foreach ($results as $result) {
            $this->storeResult($result);
        }
    }

    public function getResultsByStudent(int $studentId): Collection
    {
        return Result::where('student_id', $studentId)
            ->with(['subject', 'exam'])
            ->get();
    }

    public function getResultsByExam(int $examId): Collection
    {
        return Result::where('exam_id', $examId)
            ->with(['student.user', 'subject'])
            ->get();
    }

    public function getResultsByStudentAndExam(int $studentId, int $examId): Collection
    {
        return Result::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject'])
            ->get();
    }
}
