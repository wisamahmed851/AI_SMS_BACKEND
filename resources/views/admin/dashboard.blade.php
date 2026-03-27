@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Manage Students, Teachers, Classes & More')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.parents.index') }}" class="sidebar-link">Parents</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card title="Students" :value="$totalStudents" color="indigo"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>' />

        <x-stat-card title="Teachers" :value="$totalTeachers" color="emerald"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />

        <x-stat-card title="Revenue" :value="'Rs ' . number_format($totalRevenue)" color="teal"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />

        <x-stat-card title="Attendance" :value="$attendancePercentage . '%'" color="blue"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Students Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Students</h3>
                <span class="badge badge-info">{{ $totalStudents }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Class</th></tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td class="font-medium">{{ $student->user->name }}</td>
                                <td>{{ $student->user->email }}</td>
                                <td><span class="badge badge-info">{{ $student->schoolClass->name }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Teachers Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Teachers</h3>
                <span class="badge badge-success">{{ $totalTeachers }} total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Subjects</th></tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            <tr>
                                <td class="font-medium">{{ $teacher->user->name }}</td>
                                <td>{{ $teacher->user->email }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($teacher->subjects as $subject)
                                            <span class="badge badge-info">{{ $subject->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Classes --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Classes</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($classes as $class)
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                        <h4 class="font-bold text-indigo-800 text-sm">{{ $class->name }}</h4>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $class->students_count }}</p>
                        <p class="text-xs text-indigo-400">Students enrolled</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Fees --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Recent Fees</h3>
                <span class="text-xs text-slate-400">Pending: Rs {{ number_format($totalPending) }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr><th>Student</th><th>Amount</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($fees as $fee)
                            <tr>
                                <td class="font-medium">{{ $fee->student->user->name }}</td>
                                <td>Rs {{ number_format($fee->amount) }}</td>
                                <td>
                                    <span class="badge {{ $fee->status === 'paid' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($fee->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Recent Notifications</h3>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentNotifications as $notification)
                <div class="px-6 py-4 flex items-start gap-3 hover:bg-slate-50/50 transition-colors">
                    <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0
                        @if($notification->type === 'success') bg-emerald-500
                        @elseif($notification->type === 'warning') bg-amber-500
                        @elseif($notification->type === 'danger') bg-red-500
                        @else bg-blue-500
                        @endif"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $notification->title }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $notification->message }}</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-slate-400">No notifications.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
