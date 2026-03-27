@extends('layouts.app')

@section('title', 'Manage Students')
@section('page-title', 'Students')
@section('page-subtitle', 'Manage all students across classes')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link active">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">All Students</h3>
        <a href="{{ route('admin.students.create') }}" class="btn-primary">Add Student</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Class</th>
                    <th>Parents</th>
                    <th>Joined</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="font-semibold text-slate-800">{{ $student->user->name }}</td>
                    <td>{{ $student->user->email }}</td>
                    <td><span class="badge badge-info">{{ optional($student->schoolClass)->name }}</span></td>
                    <td>
                        @foreach($student->parents as $parent)
                            <div class="text-xs">{{ $parent->user->name }}</div>
                        @endforeach
                    </td>
                    <td class="text-sm text-slate-500">{{ $student->created_at->format('M d, Y') }}</td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete student?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $students->links() }}</div>
</div>
@endsection
