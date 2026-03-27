@extends('layouts.app')
@section('title', 'Manage Fee Payments')
@section('page-title', 'Fee Details & Payments')
@section('sidebar')
    <a href="{{ route('admin.fees.index') }}" class="sidebar-link active">Back to Fees</a>
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Record Info</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-500 block">Student</span>
                    <span class="font-semibold text-slate-800">{{ $fee->student->user->name }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Total Due</span>
                    <span class="font-bold text-slate-800 text-lg">Rs {{ number_format($fee->amount) }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Status</span>
                    <span class="badge {{ $fee->status === 'paid' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($fee->status) }}</span>
                </div>
            </div>
        </div>

        @if($fee->status !== 'paid' && $fee->amount > $fee->payments->sum('amount'))
        <div class="bg-indigo-50 rounded-2xl border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-indigo-900 mb-4">Add Payment</h3>
            <form action="{{ route('admin.fees.payments.store', $fee) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-indigo-900 mb-1">Amount</label>
                    <input type="number" name="amount" required min="1" max="{{ $fee->amount - $fee->payments->sum('amount') }}" step="0.01" class="form-input">
                    <p class="text-xs text-indigo-600 mt-1">Remaining: Rs {{ number_format($fee->amount - $fee->payments->sum('amount')) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-indigo-900 mb-1">Method</label>
                    <select name="method" required class="form-input">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Online">Online / Card</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Record Payment</button>
            </form>
        </div>
        @endif
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Payment History</h3>
                <span class="text-sm font-semibold text-emerald-600">Total Paid: Rs {{ number_format($fee->payments->sum('amount')) }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fee->payments as $payment)
                        <tr>
                            <td class="text-slate-600">{{ $payment->paid_at->format('M d, Y h:i A') }}</td>
                            <td class="font-bold text-emerald-600">+Rs {{ number_format($payment->amount) }}</td>
                            <td><span class="badge badge-info">{{ $payment->method }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-slate-500">No payments recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
