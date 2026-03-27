@extends('layouts.app')
@section('title', 'Create Task')
@section('page-title', 'Create New Assignment')
@section('sidebar')
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link active">Back to Assignments</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('teacher.tasks.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Task Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="e.g. Chapter 4 Math Exercises">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Subject</label>
                <select name="subject_id" id="subject_id" required class="form-input">
                    <option value="">Select subject...</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Target Class</label>
                <!-- This should dynamically filter based on subject, but for simplicity we list all linked classes -->
                <select name="class_id" required class="form-input">
                    <option value="">Select class...</option>
                    @php $allClasses = $subjects->pluck('classes')->flatten()->unique('id'); @endphp
                    @foreach($allClasses as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Task Instructions / Description</label>
                <textarea name="description" required class="form-input h-32" placeholder="Describe what the students need to do...">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Submission Deadline</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="form-input">
                @error('deadline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary">Publish Task</button>
        </div>
    </form>
</div>
@endsection
