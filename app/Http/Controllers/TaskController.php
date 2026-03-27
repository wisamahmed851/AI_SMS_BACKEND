<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(): View
    {
        $role = auth()->user()->role->slug;

        if ($role === 'teacher') {
            $tasks = Task::where('teacher_id', auth()->user()->teacher->id)
                ->with(['schoolClass', 'subject'])
                ->latest()
                ->paginate(10);
            return view('tasks.teacher_index', compact('tasks'));
        } elseif ($role === 'student') {
            $tasks = Task::where('class_id', auth()->user()->student->class_id)
                ->with(['teacher.user', 'subject', 'submissions' => function ($q) {
                    $q->where('student_id', auth()->user()->student->id);
                }])
                ->latest()
                ->paginate(10);
            return view('tasks.student_index', compact('tasks'));
        }

        abort(403);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->role->slug === 'teacher', 403);
        $teacher = auth()->user()->teacher;
        $subjects = $teacher->subjects()->with('classes')->get();
        return view('tasks.create', compact('subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->role->slug === 'teacher', 403);

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date'
        ]);

        $validated['teacher_id'] = auth()->user()->teacher->id;
        Task::create($validated);

        return redirect()->route('teacher.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $role = auth()->user()->role->slug;
        if ($role === 'teacher') {
            abort_unless($task->teacher_id === auth()->user()->teacher->id, 403);
            $submissions = $task->submissions()->with('student.user')->get();
            return view('tasks.teacher_show', compact('task', 'submissions'));
        } elseif ($role === 'student') {
            abort_unless($task->class_id === auth()->user()->student->class_id, 403);
            $submission = $task->submissions()->where('student_id', auth()->user()->student->id)->first();
            return view('tasks.student_show', compact('task', 'submission'));
        }
        
        abort(403);
    }

    public function edit(Task $task): View
    {
        abort_unless(auth()->user()->role->slug === 'teacher', 403);
        abort_unless($task->teacher_id === auth()->user()->teacher->id, 403);
        
        $teacher = auth()->user()->teacher;
        $subjects = $teacher->subjects()->with('classes')->get();
        return view('tasks.edit', compact('task', 'subjects'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        abort_unless(auth()->user()->role->slug === 'teacher', 403);
        abort_unless($task->teacher_id === auth()->user()->teacher->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date'
        ]);

        $task->update($validated);
        return redirect()->route('teacher.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        abort_unless(auth()->user()->role->slug === 'teacher', 403);
        abort_unless($task->teacher_id === auth()->user()->teacher->id, 403);
        
        $task->delete();
        return redirect()->route('teacher.tasks.index')->with('success', 'Task deleted.');
    }

    public function submit(Request $request, Task $task): RedirectResponse
    {
        abort_unless(auth()->user()->role->slug === 'student', 403);
        abort_unless($task->class_id === auth()->user()->student->class_id, 403);

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        TaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'student_id' => auth()->user()->student->id
            ],
            [
                'content' => $validated['content'],
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Task submitted successfully.');
    }
}
