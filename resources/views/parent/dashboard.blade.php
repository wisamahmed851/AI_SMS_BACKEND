@extends('layouts.app')

@section('title', 'Parent Dashboard')
@section('page-title', 'Parent Dashboard')
@section('page-subtitle', 'Monitor your children\'s progress')

@section('sidebar')
    <a href="{{ route('parent.dashboard') }}" class="sidebar-link active">Dashboard</a>
    <a href="{{ route('parent.leaves.index') }}" class="sidebar-link">Child Leaves</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Children Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-{{ count($childrenData) }} gap-5">
        @foreach($childrenData as $childData)
            <x-stat-card
                :title="$childData['student']->user->name"
                :value="$childData['student']->schoolClass->name"
                :subtitle="'Attendance: ' . $childData['attendancePercentage'] . '%'"
                color="indigo"
                icon='<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />
        @endforeach
    </div>

    {{-- Per-Child Detail --}}
    @foreach($childrenData as $childData)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($childData['student']->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">{{ $childData['student']->user->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $childData['student']->schoolClass->name }} &bull; Attendance: {{ $childData['attendancePercentage'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Attendance --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Recent Attendance
                        </h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($childData['attendances']->take(7) as $att)
                                <div class="flex items-center justify-between py-1.5 px-3 rounded-lg {{ $att->status === 'present' ? 'bg-emerald-50' : 'bg-red-50' }}">
                                    <span class="text-xs text-slate-600">{{ $att->date->format('M d') }}</span>
                                    <span class="badge {{ $att->status === 'present' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Results --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Exam Results
                        </h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($childData['results'] as $result)
                                @php
                                    $grade = $result->marks >= 90 ? 'A+' : ($result->marks >= 80 ? 'A' : ($result->marks >= 70 ? 'B' : ($result->marks >= 60 ? 'C' : 'D')));
                                    $gradeClass = $result->marks >= 80 ? 'badge-success' : ($result->marks >= 60 ? 'badge-warning' : 'badge-danger');
                                @endphp
                                <div class="flex items-center justify-between py-1.5 px-3 rounded-lg bg-slate-50">
                                    <div>
                                        <p class="text-xs font-medium text-slate-700">{{ $result->subject->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $result->exam->name }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-700">{{ $result->marks }}</span>
                                        <span class="badge {{ $gradeClass }}">{{ $grade }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fees --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Fees
                        </h4>
                        <div class="space-y-2">
                            @foreach($childData['fees'] as $fee)
                                <div class="flex items-center justify-between py-2 px-3 rounded-lg {{ $fee->status === 'paid' ? 'bg-emerald-50' : 'bg-red-50' }}">
                                    <span class="text-sm font-semibold text-slate-700">Rs {{ number_format($fee->amount) }}</span>
                                    <span class="badge {{ $fee->status === 'paid' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($fee->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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
