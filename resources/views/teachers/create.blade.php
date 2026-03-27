@extends('layouts.app')
@section('title', 'Add Teacher')
@section('page-title', 'Add Teacher')
@section('sidebar')
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link active">Back to Teachers</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.teachers.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teacher Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required minlength="6" class="form-input">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Assign Subjects</label>
                <select name="subject_ids[]" multiple class="form-input h-32">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(in_array($subject->id, old('subject_ids', [])))>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Hold Ctrl/Cmd to select multiple.</p>
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary">Create Teacher</button>
        </div>
    </form>
</div>
@endsection
