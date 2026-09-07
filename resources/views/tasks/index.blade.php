@extends('layouts.app')

@section('title', 'Tasks')
@section('subtitle', 'Reminders and to-dos for the team')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-head">
                    <div><h2>New Task</h2><p>&nbsp;</p></div>
                </div>
                <form method="POST" action="{{ route('tasks.store') }}" class="p-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input class="form-control" name="title" required placeholder="e.g. Follow up on Hamza's visa docs">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Any extra detail..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign to</label>
                        <select class="form-select" name="assigned_to_id" required>
                            @foreach ($assignees as $assignee)
                                <option value="{{ $assignee->id }}" {{ $assignee->id === auth()->id() ? 'selected' : '' }}>{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Due date</label>
                            <input class="form-control" type="date" name="due_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Due time</label>
                            <input class="form-control" type="time" name="due_time">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create task</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-head">
                    <div class="btn-group">
                        <a href="{{ route('tasks.index', ['filter' => 'mine']) }}" class="btn btn-sm {{ $filter === 'mine' ? 'btn-primary' : 'btn-outline-secondary' }}">Assigned to me</a>
                        <a href="{{ route('tasks.index', ['filter' => 'created']) }}" class="btn btn-sm {{ $filter === 'created' ? 'btn-primary' : 'btn-outline-secondary' }}">Created by me</a>
                        @if (auth()->user()->hasBackOfficeAccess())
                            <a href="{{ route('tasks.index', ['filter' => 'all']) }}" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">Everyone's</a>
                        @endif
                    </div>
                </div>

                <div class="p-3">
                    @if ($tasks->isEmpty())
                        <div class="empty-state">No tasks here yet.</div>
                    @else
                        @foreach ($tasks as $task)
                            <div class="d-flex justify-content-between align-items-start gap-3 py-3 {{ !$loop->first ? 'border-top' : '' }}">
                                <div>
                                    <div class="{{ $task->done ? 'text-decoration-line-through text-muted' : '' }} fw-semibold">{{ $task->title }}</div>
                                    @if ($task->notes)
                                        <div class="text-muted small mt-1">{{ $task->notes }}</div>
                                    @endif
                                    <div class="d-flex gap-2 align-items-center mt-2 flex-wrap">
                                        <span class="badge {{ !$task->done && $task->isOverdue() ? 'bg-danger' : 'bg-secondary-subtle text-dark' }}">
                                            {{ !$task->done && $task->isOverdue() ? 'Overdue' : 'Due' }}
                                            {{ $task->due_date ? '· '.$task->due_date->format('M j, Y').($task->due_time ? ' '.\Illuminate\Support\Carbon::parse($task->due_time)->format('g:i A') : '') : '· No due date' }}
                                        </span>
                                        <span class="badge bg-light text-dark border text-uppercase">{{ $task->priority }}</span>
                                        <span class="text-muted small">Assigned to <strong>{{ $task->assignee->name }}</strong> &middot; by {{ $task->creator->name ?? '—' }}</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $task->done ? 'Mark as not done' : 'Mark as done' }}">{{ $task->done ? 'Reopen' : 'Done' }}</button>
                                    </form>
                                    @if (auth()->user()->hasBackOfficeAccess() || $task->created_by_id === auth()->id())
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
