@extends('layouts.app')

@section('title', 'Teacher Attendance')
@section('page-title', 'Teacher Attendance')
@section('page-subtitle', 'Manage daily staff attendance')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link active">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
        <h3 class="text-base font-bold text-slate-800">Attendance for {{ \Carbon\Carbon::parse($date)->format('D, M d, Y') }}</h3>
        
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}" class="form-input py-1.5 text-sm" onchange="this.form.submit()">
        </form>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.teacher-attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="overflow-x-auto rounded-xl border border-slate-200 mb-6">
                <table class="data-table mb-0">
                    <thead>
                        <tr>
                            <th>Teacher Name</th>
                            <th>Email</th>
                            <th>Status Matrix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            @php
                                $status = $attendances->get($teacher->id)?->status ?? 'present'; // Default to present
                            @endphp
                            <tr>
                                <td class="font-semibold">{{ $teacher->user->name }}</td>
                                <td class="text-sm text-slate-500">{{ $teacher->user->email }}</td>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="present" @checked($status === 'present') class="text-emerald-500 focus:ring-emerald-500">
                                            <span class="text-sm font-medium text-emerald-700">Present</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="absent" @checked($status === 'absent') class="text-red-500 focus:ring-red-500">
                                            <span class="text-sm font-medium text-red-700">Absent</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $teacher->id }}][status]" value="late" @checked($status === 'late') class="text-amber-500 focus:ring-amber-500">
                                            <span class="text-sm font-medium text-amber-700">Late</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Save Attendance</button>
            </div>
        </form>
    </div>
</div>
@endsection
