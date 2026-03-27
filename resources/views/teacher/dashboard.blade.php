@extends('layouts.app')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link active">Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="sidebar-link">Mark Attendance</a>
    <a href="{{ route('teacher.results') }}" class="sidebar-link">Enter Results</a>
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link">Assignments</a>
    <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link">My Leaves</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <x-stat-card title="My Subjects" :value="$subjects->count()" color="indigo"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>' />

        <x-stat-card title="My Classes" :value="$classes->count()" color="emerald"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' />

        <x-stat-card title="Exams" :value="$exams->count()" color="amber"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Assigned Subjects --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Assigned Subjects</h3>
            </div>
            <div class="p-6 space-y-3">
                @foreach($subjects as $subject)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                        <div>
                            <h4 class="font-bold text-indigo-800 text-sm">{{ $subject->name }}</h4>
                            <p class="text-xs text-indigo-500 mt-0.5">
                                Classes: {{ $subject->classes->pluck('name')->join(', ') }}
                            </p>
                        </div>
                        <span class="badge badge-info">{{ $subject->classes->count() }} classes</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Assigned Classes --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Assigned Classes</h3>
            </div>
            <div class="p-6 space-y-3">
                @foreach($classes as $class)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                        <div>
                            <h4 class="font-bold text-emerald-800 text-sm">{{ $class->name }}</h4>
                            <p class="text-xs text-emerald-500 mt-0.5">{{ $class->students_count }} students</p>
                        </div>
                        <a href="{{ route('teacher.attendance', ['class_id' => $class->id]) }}" class="btn-primary text-xs !py-1.5 !px-3">
                            Mark Attendance
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('teacher.attendance') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-700 text-sm">Mark Attendance</p>
                    <p class="text-xs text-slate-400">Record daily attendance</p>
                </div>
            </a>
            <a href="{{ route('teacher.results') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 transition-all group">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-700 text-sm">Enter Results</p>
                    <p class="text-xs text-slate-400">Record exam marks</p>
                </div>
            </a>
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
