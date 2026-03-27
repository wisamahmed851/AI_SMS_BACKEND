@extends('layouts.app')
@section('title', 'Manage Fees')
@section('page-title', 'Fees')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link">Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link">Teachers</a>
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link active">Fees</a>
    <a href="{{ route('admin.teacher-attendances.index') }}" class="sidebar-link">Teacher Attendance</a>
    <a href="{{ route('admin.leaves.index') }}" class="sidebar-link">Leave Requests</a>
@endsection
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-800">Fee Records</h3>
        <a href="{{ route('admin.fees.create') }}" class="btn-primary">Create Fee Record</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fees as $fee)
                @php $paidAmount = $fee->payments->sum('amount'); @endphp
                <tr>
                    <td class="font-semibold text-slate-800">{{ $fee->student->user->name }}</td>
                    <td class="font-bold">Rs {{ number_format($fee->amount) }}</td>
                    <td class="text-emerald-600 font-semibold">Rs {{ number_format($paidAmount) }}</td>
                    <td>
                        <span class="badge {{ $fee->status === 'paid' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($fee->status) }}
                        </span>
                    </td>
                    <td class="text-sm text-slate-500">{{ $fee->created_at->format('M d, Y') }}</td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.fees.show', $fee) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Manage</a>
                        <a href="{{ route('admin.fees.edit', $fee) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.fees.destroy', $fee) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Delete this fee record?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $fees->links() }}</div>
</div>
@endsection
