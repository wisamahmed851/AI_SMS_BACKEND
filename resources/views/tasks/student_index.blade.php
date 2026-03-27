@extends('layouts.app')
@section('title', 'My Assigned Tasks')
@section('page-title', 'Class Assignments')
@section('page-subtitle', 'Pending and past assignments')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('student.tasks.index') }}" class="sidebar-link active">My Tasks</a>
    <a href="{{ route('student.leaves.index') }}" class="sidebar-link">Leave Applications</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="text-base font-bold text-slate-800">Your Assignments</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Task Title</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                @php 
                    $hasSubmitted = $task->submissions->count() > 0;
                    $isPast = $task->deadline->isPast();
                @endphp
                <tr>
                    <td class="font-semibold text-slate-800">
                        <span class="badge badge-indigo">{{ $task->subject->name }}</span>
                    </td>
                    <td>{{ optional($task->teacher->user)->name ?? 'Unknown Teacher' }}</td>
                    <td class="font-medium max-w-xs truncate" title="{{ $task->title }}">{{ $task->title }}</td>
                    <td class="{{ $isPast && !$hasSubmitted ? 'text-red-500 font-bold' : 'text-slate-600' }}">
                        {{ $task->deadline->format('M d, Y h:i A') }}
                    </td>
                    <td>
                        @if($hasSubmitted)
                            <span class="badge badge-success">Submitted</span>
                        @elseif($isPast)
                            <span class="badge badge-danger">Missing</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    
                    <td class="text-right">
                        <a href="{{ route('student.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View & Submit</a>
                    </td>
                </tr>
                @endforeach
                
                @if($tasks->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-6 text-slate-500">No active assignments right now. Awesome!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $tasks->links() }}</div>
</div>
@endsection
