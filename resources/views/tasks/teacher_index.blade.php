@extends('layouts.app')
@section('title', 'My Tasks')
@section('page-title', 'Manage Tasks')
@section('page-subtitle', 'Assignments for your classes')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="sidebar-link">Mark Attendance</a>
    <a href="{{ route('teacher.results') }}" class="sidebar-link">Exam Results</a>
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link active">Assignments</a>
    <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link">My Leaves</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">Your Created Tasks</h3>
        <a href="{{ route('teacher.tasks.create') }}" class="btn-primary">Create New Task</a>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Deadline</th>
                    <th>Created At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="font-semibold text-slate-800">{{ $task->title }}</td>
                    <td><span class="badge badge-info text-xs">{{ $task->schoolClass->name }}</span></td>
                    <td>{{ $task->subject->name }}</td>
                    <td class="{{ $task->deadline->isPast() ? 'text-red-500 font-medium' : 'text-slate-600' }}">
                        {{ $task->deadline->format('M d, Y h:i A') }}
                    </td>
                    <td class="text-sm text-slate-500">{{ $task->created_at->format('M d, Y') }}</td>
                    
                    <td class="text-right space-x-2">
                        <a href="{{ route('teacher.tasks.submissions', $task) }}" class="text-emerald-600 hover:text-emerald-900 text-sm font-medium">Submissions</a>
                        <a href="{{ route('teacher.tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('teacher.tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete task completely?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                
                @if($tasks->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-6 text-slate-500">No tasks created yet.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $tasks->links() }}</div>
</div>
@endsection
