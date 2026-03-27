@extends('layouts.app')

@section('title', 'Add Parent')
@section('page-title', 'Add Parent')

@section('sidebar')
    <a href="{{ route('admin.parents.index') }}" class="sidebar-link active">Back to Parents</a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.parents.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Parent Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required minlength="6" class="form-input">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Link Students</label>
                <select name="student_ids[]" multiple class="form-input h-32">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(in_array($student->id, old('student_ids', [])))>
                            {{ $student->user->name }} ({{ $student->user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Hold Ctrl/Cmd to select multiple students.</p>
                @error('student_ids') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" class="btn-primary">Create Parent</button>
        </div>
    </form>
</div>
@endsection
