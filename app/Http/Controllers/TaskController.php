<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = in_array($request->query('filter'), ['mine', 'created', 'all'])
            ? $request->query('filter')
            : 'mine';

        if ($filter === 'all' && ! $user->hasBackOfficeAccess()) {
            $filter = 'mine';
        }

        $query = Task::with(['assignee', 'creator']);

        $tasks = match ($filter) {
            'created' => $query->where('created_by_id', $user->id)->get(),
            'all' => $query->get(),
            default => $query->where('assigned_to_id', $user->id)->get(),
        };

        $tasks = $tasks->sortBy([
            fn ($a, $b) => $a->done <=> $b->done,
            fn ($a, $b) => ($a->due_date?->toDateString() ?? '9999-99-99') <=> ($b->due_date?->toDateString() ?? '9999-99-99'),
        ])->values();

        $assignees = User::whereIn('role', ['admin', 'counselor', 'processing_team'])->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'filter', 'assignees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'assigned_to_id' => ['required', 'exists:users,id', Rule::in(User::whereIn('role', ['admin', 'counselor', 'processing_team'])->pluck('id'))],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $task = Task::create([
            ...$data,
            'created_by_id' => $user->id,
        ]);

        ActivityLog::record('task.created', "Created task \"{$task->title}\" assigned to \"{$task->assignee->name}\"");

        return redirect()->route('tasks.index')->with('status', 'Task created.');
    }

    public function complete(Task $task)
    {
        $user = Auth::user();

        if (! $user->hasBackOfficeAccess() && $task->assigned_to_id !== $user->id && $task->created_by_id !== $user->id) {
            abort(403);
        }

        $task->done = ! $task->done;
        $task->completed_at = $task->done ? now() : null;
        $task->save();

        ActivityLog::record($task->done ? 'task.completed' : 'task.reopened', "\"{$task->title}\" marked as ".($task->done ? 'done' : 'not done'));

        return back()->with('status', $task->done ? 'Task marked as done.' : 'Task reopened.');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        if (! $user->hasBackOfficeAccess() && $task->created_by_id !== $user->id) {
            abort(403);
        }

        $task->delete();

        return back()->with('status', 'Task deleted.');
    }
}
