@extends('layouts.app')

@section('title', 'Activity Log')
@section('subtitle', 'Recent actions across the platform — most recent 200 events')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Activity</h2><p>Opportunities, leads, students, papers, and user accounts</p></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="date-cell">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="name-cell">{{ $log->user->name ?? 'System' }}</td>
                            <td><span class="chip static gray">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No activity recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
