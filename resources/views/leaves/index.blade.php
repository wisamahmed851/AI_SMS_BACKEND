@extends('layouts.app')
@section('title', 'Leave Management')
@section('page-title', 'Leaves & Absences')
@section('page-subtitle', 'Manage leave applications')

@section('sidebar')
    @php $userRole = auth()->user()->role->slug; @endphp
    @if($userRole === 'admin' || $userRole === 'super_admin')
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
        <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
        <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
        <a href="{{ route('admin.fees.index') }}" class="sidebar-link">Fees</a>
        <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
        <a href="{{ route('admin.leaves.index') }}" class="sidebar-link active">Leave Requests</a>
    @elseif($userRole === 'teacher')
        <a href="{{ route('teacher.dashboard') }}" class="sidebar-link">Dashboard</a>
        <a href="{{ route('teacher.attendance') }}" class="sidebar-link">Mark Attendance</a>
        <a href="{{ route('teacher.results') }}" class="sidebar-link">Exam Results</a>
        <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link">Assignments</a>
        <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link active">My Leaves</a>
    @elseif($userRole === 'student')
        <a href="{{ route('student.dashboard') }}" class="sidebar-link">Dashboard</a>
        <a href="{{ route('student.tasks.index') }}" class="sidebar-link">My Tasks</a>
        <a href="{{ route('student.leaves.index') }}" class="sidebar-link active">Leave Applications</a>
    @elseif($userRole === 'parent')
        <a href="{{ route('parent.dashboard') }}" class="sidebar-link">Dashboard</a>
        <a href="{{ route('parent.leaves.index') }}" class="sidebar-link active">Child Leaves</a>
    @endif
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">Leave Records</h3>
        @if(in_array(auth()->user()->role->slug, ['student', 'teacher', 'parent']))
            <a href="{{ route(auth()->user()->role->slug.'.leaves.create') }}" class="btn-primary">Apply for Leave</a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Role</th>
                    <th>Student (If Parent)</th>
                    <th>Reason</th>
                    <th>Duration</th>
                    <th>Status</th>
                    @if(in_array(auth()->user()->role->slug, ['admin', 'super_admin']))
                        <th class="text-right">Admin Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td class="font-semibold text-slate-800">{{ $leave->user->name }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($leave->role) }}</span></td>
                    <td>{{ $leave->student ? $leave->student->user->name : '-' }}</td>
                    <td class="max-w-xs truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                    <td class="text-sm text-slate-600">
                        {{ $leave->from_date->format('M d') }} to {{ $leave->to_date->format('M d, Y') }}
                    </td>
                    <td>
                        <span class="badge 
                            @if($leave->status === 'approved') badge-success
                            @elseif($leave->status === 'rejected') badge-danger
                            @else badge-warning @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    
                    @if(in_array(auth()->user()->role->slug, ['admin', 'super_admin']))
                    <td class="text-right space-x-2">
                        @if($leave->status === 'pending')
                            <form action="{{ route('admin.leaves.update', $leave) }}" method="POST" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="text-emerald-600 hover:text-emerald-900 text-sm font-medium">Approve</button>
                            </form>
                            <span class="text-slate-300">|</span>
                            <form action="{{ route('admin.leaves.update', $leave) }}" method="POST" class="inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Reject</button>
                            </form>
                        @else
                            <span class="text-sm text-slate-400">Processed</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
                
                @if($leaves->isEmpty())
                <tr>
                    <td colspan="7" class="text-center py-6 text-slate-500">No leave requests found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $leaves->links() }}</div>
</div>
@endsection
