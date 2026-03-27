@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')
@section('page-subtitle', 'Record daily student attendance')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="sidebar-link active">Mark Attendance</a>
    <a href="{{ route('teacher.results') }}" class="sidebar-link">Enter Results</a>
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link">Assignments</a>
    <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link">My Leaves</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Select Class & Date</h3>
        <form method="GET" action="{{ route('teacher.attendance') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-600 mb-1">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Load Students</button>
        </form>
    </div>

    {{-- Attendance Form --}}
    @if($selectedClassId && $students->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Attendance for {{ $date }}</h3>
                <span class="badge badge-info">{{ $students->count() }} students</span>
            </div>
            <form method="POST" action="{{ route('teacher.attendance.store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @php
                                    $existing = $existingAttendance->get($student->id);
                                    $currentStatus = $existing ? $existing->status : 'present';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-medium">{{ $student->user->name }}</td>
                                    <td>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="present"
                                                    {{ $currentStatus === 'present' ? 'checked' : '' }}
                                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                                <span class="text-sm text-emerald-700 font-medium">Present</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="absent"
                                                    {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                                    class="w-4 h-4 text-red-600 focus:ring-red-500">
                                                <span class="text-sm text-red-700 font-medium">Absent</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button type="submit" class="btn-primary">Save Attendance</button>
                </div>
            </form>
        </div>
    @elseif($selectedClassId && $students->count() === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
            <p class="text-slate-400 text-sm">No students found in this class.</p>
        </div>
    @endif
</div>
@endsection
