@extends('layouts.app')
@section('title', 'Create Fee')
@section('page-title', 'Create Fee Record')
@section('sidebar')
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link active">Back to Fees</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.fees.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Select Student</label>
                <select name="student_id" required class="form-input">
                    <option value="">Choose a student...</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                            {{ $student->user->name }} (Class {{ optional($student->schoolClass)->name }})
                        </option>
                    @endforeach
                </select>
                @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fee Amount (Rs)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" required min="0" step="0.01" class="form-input">
                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" required class="form-input">
                    <option value="unpaid" @selected(old('status') === 'unpaid')>Unpaid</option>
                    <option value="paid" @selected(old('status') === 'paid')>Paid (Fully)</option>
                </select>
                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary">Create Record</button>
        </div>
    </form>
</div>
@endsection
