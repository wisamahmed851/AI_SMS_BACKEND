<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role->slug === 'teacher' || auth()->user()->role->slug === 'admin';
    }

    public function rules(): array
    {
        return [
            'results' => ['required', 'array'],
            'results.*.student_id' => ['required', 'exists:students,id'],
            'results.*.subject_id' => ['required', 'exists:subjects,id'],
            'results.*.exam_id' => ['required', 'exists:exams,id'],
            'results.*.marks' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }
}
