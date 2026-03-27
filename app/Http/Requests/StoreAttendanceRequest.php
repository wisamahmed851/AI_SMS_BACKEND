<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role->slug === 'teacher' || auth()->user()->role->slug === 'admin';
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'class_id' => ['required', 'exists:school_classes,id'],
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'in:present,absent'],
        ];
    }
}
