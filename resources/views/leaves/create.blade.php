@extends('layouts.app')

@section('title', 'Apply for Leave')
@section('page-title', 'Apply for Leave')

@section('sidebar')
    @php $userRole = auth()->user()->role->slug; @endphp
    @if($userRole === 'teacher')
        <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link active">Back to Leaves</a>
    @elseif($userRole === 'student')
        <a href="{{ route('student.leaves.index') }}" class="sidebar-link active">Back to Leaves</a>
    @elseif($userRole === 'parent')
        <a href="{{ route('parent.leaves.index') }}" class="sidebar-link active">Back to Leaves</a>
    @endif
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    @php $userRole = auth()->user()->role->slug; @endphp
    <form action="{{ route($userRole.'.leaves.store') }}" method="POST" class="space-y-6">
        @csrf
        
        @if($role === 'parent')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Select Child</label>
                <select name="student_id" required class="form-input">
                    <option value="">Select your child...</option>
                    @foreach($children as $child)
                        <option value="{{ $child->id }}">{{ $child->user->name }} - Class {{ $child->schoolClass->name }}</option>
                    @endforeach
                </select>
                @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ old('from_date') }}" required min="{{ now()->toDateString() }}" class="form-input">
                @error('from_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ old('to_date') }}" required min="{{ now()->toDateString() }}" class="form-input">
                @error('to_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Reason for Leave</label>
            <textarea name="reason" required class="form-input h-32" placeholder="Explain the reason for taking leave..."></textarea>
            @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" class="btn-primary">Submit Application</button>
        </div>
    </form>
</div>
@endsection
