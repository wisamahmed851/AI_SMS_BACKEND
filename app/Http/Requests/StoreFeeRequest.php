<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role->slug === 'admin' || auth()->user()->role->slug === 'super_admin';
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:paid,unpaid'],
        ];
    }
}
