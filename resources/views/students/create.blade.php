@extends('layouts.app')

@section('title', 'Add Student')
@section('page-title', 'Add Student')

@section('sidebar')
    <a href="{{ route('admin.students.index') }}" class="sidebar-link active">Back to Students</a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Student Name</label>
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

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Assign Class</label>
                <select name="class_id" required class="form-input">
                    <option value="">Select a class...</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('class_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Assign Parents</label>
                <select name="parent_ids[]" multiple class="form-input h-32">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(in_array($parent->id, old('parent_ids', [])))>
                            {{ $parent->user->name }} ({{ $parent->user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Hold Ctrl/Cmd to select multiple parents.</p>
                @error('parent_ids') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100">
            <button type="submit" class="btn-primary">Create Student</button>
        </div>
    </form>
</div>
@endsection
