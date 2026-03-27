@extends('layouts.app')
@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher: ' . $teacher->user->name)
@section('sidebar')
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link active">Back to Teachers</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Teacher Name</label>
                <input type="text" name="name" value="{{ old('name', $teacher->user->name) }}" required class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $teacher->user->email) }}" required class="form-input">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Assign Subjects</label>
                <select name="subject_ids[]" multiple class="form-input h-32">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(in_array($subject->id, old('subject_ids', $teacher->subjects->pluck('id')->toArray())))>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary">Update Teacher</button>
        </div>
    </form>
</div>
@endsection
