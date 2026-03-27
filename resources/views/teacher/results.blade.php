@extends('layouts.app')

@section('title', 'Enter Results')
@section('page-title', 'Enter Results')
@section('page-subtitle', 'Record exam marks for students')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link">Dashboard</a>
    <a href="{{ route('teacher.attendance') }}" class="sidebar-link">Mark Attendance</a>
    <a href="{{ route('teacher.results') }}" class="sidebar-link active">Enter Results</a>
    <a href="{{ route('teacher.tasks.index') }}" class="sidebar-link">Assignments</a>
    <a href="{{ route('teacher.leaves.index') }}" class="sidebar-link">My Leaves</a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Select Subject & Exam</h3>
        <form method="GET" action="{{ route('teacher.results') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-600 mb-1">Subject</label>
                <select name="subject_id" class="form-select">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-600 mb-1">Exam</label>
                <select name="exam_id" class="form-select">
                    <option value="">Select Exam</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ $selectedExamId == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Load Students</button>
        </form>
    </div>

    {{-- Results Form --}}
    @if(isset($students) && $students->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Enter Marks</h3>
                <span class="badge badge-info">{{ $students->count() }} students</span>
            </div>
            <form method="POST" action="{{ route('teacher.results.store') }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Marks (0-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @php
                                    $existingMark = isset($existingResults) ? ($existingResults->get($student->id)?->marks ?? '') : '';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-medium">{{ $student->user->name }}</td>
                                    <td><span class="badge badge-info">{{ $student->schoolClass->name ?? 'N/A' }}</span></td>
                                    <td>
                                        <input type="hidden" name="results[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        <input type="hidden" name="results[{{ $index }}][subject_id]" value="{{ $selectedSubjectId }}">
                                        <input type="hidden" name="results[{{ $index }}][exam_id]" value="{{ $selectedExamId }}">
                                        <input type="number" name="results[{{ $index }}][marks]"
                                            value="{{ $existingMark }}"
                                            min="0" max="100" required
                                            class="form-input w-24">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button type="submit" class="btn-primary">Save Results</button>
                </div>
            </form>
        </div>
    @elseif($selectedSubjectId && $selectedExamId)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
            <p class="text-slate-400 text-sm">No students found for this subject.</p>
        </div>
    @endif
</div>
@endsection
