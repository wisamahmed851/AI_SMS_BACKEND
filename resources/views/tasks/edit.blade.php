@extends('layouts.app')
@section('title', 'Edit Task')
@section('page-title', 'Edit Assignment')
@section('sidebar')
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link active">Back to Assignments</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('teacher.tasks.update', $task) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Task Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required class="form-input">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Task Instructions</label>
                <textarea name="description" required class="form-input h-32">{{ old('description', $task->description) }}</textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Submission Deadline</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required class="form-input">
                @error('deadline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <p class="text-xs text-slate-400 italic">Target specific class and subject cannot be changed after creation.</p>
        </div>
        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" class="btn-primary">Update Task</button>
        </div>
    </form>
</div>
@endsection
