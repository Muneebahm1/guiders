@extends('layouts.app')

@section('title', 'All Follow-ups')
@section('subtitle', 'Every scheduled call/visit reminder across all counselors — read-only')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Follow-ups</h2><p>{{ $followUps->count() }} total</p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Counselor</th>
                        <th>Linked lead</th>
                        <th>Action</th>
                        <th>Due</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($followUps as $followUp)
                        <tr class="{{ $followUp->isOverdue() ? 'overdue-row' : ($followUp->isDueSoon() ? 'due-soon-row' : '') }}">
                            <td class="name-cell">{{ $followUp->student_name }}</td>
                            <td>{{ $followUp->counselor->name ?? '—' }}</td>
                            <td>{{ $followUp->lead->name ?? '—' }}</td>
                            <td><span class="chip static {{ $followUp->action === 'Visit' ? 'gold' : 'amber' }}">{{ $followUp->action }}</span></td>
                            <td class="date-cell">{{ $followUp->due_date->toDateString() }}{{ $followUp->isOverdue() ? ' (overdue)' : '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No follow-ups yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
