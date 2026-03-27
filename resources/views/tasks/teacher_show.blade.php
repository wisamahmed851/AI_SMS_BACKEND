@extends('layouts.app')
@section('title', 'Tasks Submissions')
@section('page-title', 'Submissions')
@section('page-subtitle', 'Task: ' . $task->title)

@section('sidebar')
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link active">Back to Tasks</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-1 border border-slate-100 rounded-2xl p-6 bg-slate-50 shadow-sm self-start">
        <h3 class="font-bold text-lg mb-2 text-slate-800">Task Details</h3>
        <p class="text-sm text-slate-600 mb-4">{{ $task->description }}</p>
        
        <div class="text-sm space-y-2">
            <div>
                <span class="block text-slate-400">Class</span>
                <span class="font-semibold">{{ $task->schoolClass->name }}</span>
            </div>
            <div>
                <span class="block text-slate-400">Subject</span>
                <span class="font-semibold">{{ $task->subject->name }}</span>
            </div>
            <div>
                <span class="block text-slate-400">Deadline</span>
                <span class="font-semibold {{ $task->deadline->isPast() ? 'text-red-500' : 'text-slate-800' }}">
                    {{ $task->deadline->format('M d, Y h:i A') }}
                </span>
            </div>
        </div>
    </div>

    <div class="md:col-span-3">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Student Submissions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submission Time</th>
                            <th>Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td class="font-medium">{{ optional($submission->student->user)->name }}</td>
                            <td class="text-sm text-slate-500">
                                {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                @if($submission->submitted_at->gt($task->deadline))
                                    <span class="badge badge-danger ml-2 text-[10px]">Late</span>
                                @endif
                            </td>
                            <td>
                                <div class="p-3 bg-slate-50 rounded-xl text-sm italic border border-slate-100 max-h-40 overflow-y-auto">
                                    {{ $submission->content }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-slate-500">No submissions yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
