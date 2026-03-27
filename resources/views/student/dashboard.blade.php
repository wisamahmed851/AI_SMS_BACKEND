@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link active">Dashboard</a>
    <a href="{{ route('student.tasks.index') }}" class="sidebar-link">My Tasks</a>
    <a href="{{ route('student.leaves.index') }}" class="sidebar-link">Leave Applications</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card title="My Class" :value="$classInfo->name" color="indigo"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' />

        <x-stat-card title="Attendance" :value="$attendancePercentage . '%'" color="emerald"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>' />

        <x-stat-card title="Subjects" :value="$classInfo->subjects->count()" color="amber"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>' />

        <x-stat-card title="Fee Status" :value="$fees->where('status', 'paid')->count() . '/' . $fees->count() . ' Paid'" color="teal"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
    </div>

    {{-- Class Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Class Information - {{ $classInfo->name }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($classInfo->subjects as $subject)
                    <div class="flex items-center gap-3 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="font-semibold text-indigo-800 text-sm">{{ $subject->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Attendance Record --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Attendance Record</h3>
                <span class="badge {{ $attendancePercentage >= 75 ? 'badge-success' : 'badge-danger' }}">
                    {{ $attendancePercentage }}%
                </span>
            </div>
            <div class="overflow-x-auto max-h-80">
                <table class="data-table">
                    <thead class="sticky top-0">
                        <tr><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('D, M d Y') }}</td>
                                <td>
                                    <span class="badge {{ $attendance->status === 'present' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Fees --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Fee Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr><th>Amount</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($fees as $fee)
                            <tr>
                                <td class="font-semibold">Rs {{ number_format($fee->amount) }}</td>
                                <td>
                                    <span class="badge {{ $fee->status === 'paid' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($fee->status) }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-400">{{ $fee->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Exam Results</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr><th>Exam</th><th>Subject</th><th>Marks</th><th>Grade</th></tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        @php
                            $grade = $result->marks >= 90 ? 'A+' : ($result->marks >= 80 ? 'A' : ($result->marks >= 70 ? 'B' : ($result->marks >= 60 ? 'C' : 'D')));
                            $gradeClass = $result->marks >= 80 ? 'badge-success' : ($result->marks >= 60 ? 'badge-warning' : 'badge-danger');
                        @endphp
                        <tr>
                            <td class="font-medium">{{ $result->exam->name }}</td>
                            <td>{{ $result->subject->name }}</td>
                            <td class="font-bold">{{ $result->marks }}/100</td>
                            <td><span class="badge {{ $gradeClass }}">{{ $grade }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Notifications</h3>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentNotifications as $notification)
                <div class="px-6 py-4 flex items-start gap-3">
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
