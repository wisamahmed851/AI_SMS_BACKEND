@extends('layouts.app')
@section('title', 'Task Details')
@section('page-title', 'Task: ' . $task->title)

@section('sidebar')
    <a href="{{ route('student.tasks.index') }}" class="sidebar-link active">Back to Tasks</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg text-slate-800">Instructions</h3>
            <span class="badge badge-info">{{ $task->subject->name }}</span>
        </div>
        
        <p class="text-slate-700 leading-relaxed mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
            {{ nl2br(e($task->description)) }}
        </p>
        
        <div class="flex items-center justify-between border-t border-slate-100 pt-4">
            <div>
                <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Teacher</span>
                <span class="font-medium text-slate-800">{{ optional($task->teacher->user)->name ?? 'Unknown' }}</span>
            </div>
            <div class="text-right">
                <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Deadline</span>
                <span class="font-bold {{ $task->deadline->isPast() ? 'text-red-500' : 'text-slate-800' }}">
                    {{ $task->deadline->format('M d, Y h:i A') }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
        <h3 class="font-bold text-lg text-slate-800 mb-4">Your Submission</h3>
        
        @if($submission)
            <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100 text-center mb-6">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="font-bold text-emerald-800">Task Submitted</h4>
                <p class="text-sm text-emerald-600 mt-1">Submitted on {{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
            </div>
            
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <h5 class="text-sm font-bold text-slate-500 mb-2">Your Answer:</h5>
                <p class="text-slate-800 whitespace-pre-wrap">{{ $submission->content }}</p>
            </div>
        @else
            @if($task->deadline->isPast())
                <div class="bg-red-50 rounded-xl p-6 border border-red-100 text-center mb-6">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="font-bold text-red-800">Deadline Passed</h4>
                    <p class="text-sm text-red-600 mt-1">You can no longer submit this task.</p>
                </div>
            @else
                <form action="{{ route('student.tasks.submit', $task) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Write your answer or provide a link</label>
                        <textarea name="content" required class="form-input h-32" placeholder="Your submission goes here..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center">Submit Task</button>
                    <p class="text-center text-xs text-slate-400 mt-3">You won't be able to edit this after submission.</p>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection
