@extends('layouts.app')
@section('title', 'Edit Fee')
@section('page-title', 'Edit Fee Record')
@section('sidebar')
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link active">Back to Fees</a>
@endsection
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.fees.update', $fee) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
            <p class="text-sm text-slate-600"><strong>Student:</strong> {{ $fee->student->user->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Total Fee Amount (Rs)</label>
                <input type="number" name="amount" value="{{ old('amount', $fee->amount) }}" required min="0" step="0.01" class="form-input">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" required class="form-input">
                    <option value="unpaid" @selected(old('status', $fee->status) === 'unpaid')>Unpaid</option>
                    <option value="paid" @selected(old('status', $fee->status) === 'paid')>Paid</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-primary">Update Record</button>
        </div>
    </form>
</div>
@endsection
