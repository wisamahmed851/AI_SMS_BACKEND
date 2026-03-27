@extends('layouts.app')

@section('title', 'Manage Parents')
@section('page-title', 'Parents')
@section('page-subtitle', 'Manage parent accounts and student linkages.')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.parents.index') }}" class="sidebar-link active">Parents</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">All Parents</h3>
        <a href="{{ route('admin.parents.create') }}" class="btn-primary">Add Parent</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Linked Students</th>
                    <th>Joined</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parents as $parent)
                <tr>
                    <td class="font-semibold text-slate-800">{{ $parent->user->name }}</td>
                    <td>{{ $parent->user->email }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                        @foreach($parent->students as $student)
                            <span class="badge badge-info text-[10px]">{{ $student->user->name ?? 'Unknown' }}</span>
                        @endforeach
                        </div>
                    </td>
                    <td class="text-sm text-slate-500">{{ $parent->created_at->format('M d, Y') }}</td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.parents.edit', $parent) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete parent?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $parents->links() }}</div>
</div>
@endsection
