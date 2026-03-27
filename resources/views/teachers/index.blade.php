@extends('layouts.app')

@section('title', 'Manage Teachers')
@section('page-title', 'Teachers')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link active">Teachers</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">All Teachers</h3>
        <a href="{{ route('admin.teachers.create') }}" class="btn-primary">Add Teacher</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Assigned Subjects</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <td class="font-semibold text-slate-800">{{ $teacher->user->name }}</td>
                    <td>{{ $teacher->user->email }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                        @foreach($teacher->subjects as $subject)
                            <span class="badge badge-indigo">{{ $subject->name }}</span>
                        @endforeach
                        </div>
                    </td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete teacher?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $teachers->links() }}</div>
</div>
@endsection
