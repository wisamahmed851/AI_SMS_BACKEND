@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')
@section('page-subtitle', 'System Overview & Analytics')

@section('sidebar')
    <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link active">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.parents.index') }}" class="sidebar-link">Parents</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card title="Total Students" :value="$totalStudents" color="indigo"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>' />

        <x-stat-card title="Total Teachers" :value="$totalTeachers" color="emerald"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>' />

        <x-stat-card title="Total Classes" :value="$totalClasses" color="amber"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' />

        <x-stat-card title="Total Revenue" :value="'Rs ' . number_format($totalRevenue)" color="teal"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <x-stat-card title="Attendance Rate" :value="$attendancePercentage . '%'" color="blue"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>' />

        <x-stat-card title="Pending Fees" :value="'Rs ' . number_format($totalPending)" color="rose"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />

        <x-stat-card title="Total Users" :value="$totalUsers" color="purple"
            icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' />
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
                        <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-slate-400">
                    No notifications yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
