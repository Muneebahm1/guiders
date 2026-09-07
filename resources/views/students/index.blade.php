@extends('layouts.app')

@section('title', auth()->user()->hasBackOfficeAccess() ? 'Study Abroad — All Students' : 'My Study Abroad Students')
@section('subtitle', auth()->user()->hasBackOfficeAccess() ? 'Application through visa, across all counselors' : 'Open a student to manage their applications')

@section('content')
    @include('partials._reminder_banner', ['reminders' => $reminders ?? collect()])

    @if (auth()->user()->isCounselor() && $unassigned->isNotEmpty())
        <div class="panel">
            <div class="panel-head">
                <div><h2>Unassigned students</h2><p>Self-registered, not yet claimed by a counselor</p></div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($unassigned as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span>{{ $student->name }}</span>
                        <form method="POST" action="{{ route('students.claim', $student) }}" class="d-flex gap-2">
                            @csrf
                            <select name="opportunity_id" class="form-select form-select-sm">
                                <option value="">Choose program/journal&hellip;</option>
                                @foreach ($opportunities as $opportunity)
                                    <option value="{{ $opportunity->id }}">{{ $opportunity->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary" type="submit">Claim</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div><h2>Students</h2><p>&nbsp;</p></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        @if (auth()->user()->hasBackOfficeAccess())
                            <th>Counselor</th>
                        @endif
                        <th>Applications</th>
                        <th>Most urgent status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        @php
                            $urgent = $student->applications->sortBy(function ($a) {
                                return match(true) {
                                    $a->fee_status === 'Overdue' => 0,
                                    $a->visa_stage === 'Rejected' || $a->app_stage === 'Rejected' => 1,
                                    $a->travel_status !== 'Not Ready' => 2,
                                    default => 3,
                                };
                            })->first();
                        @endphp
                        <tr>
                            <td class="name-cell">{{ $student->name }}</td>
                            @if (auth()->user()->hasBackOfficeAccess())
                                <td>{{ $student->counselor->name ?? ($student->partner ? 'Partner: '.$student->partner->name : '—') }}</td>
                            @endif
                            <td>{{ $student->applications->count() }}</td>
                            <td>
                                @if ($urgent)
                                    <span class="chip static {{ $urgent->fee_status === 'Overdue' ? 'coral' : 'amber' }}">
                                        {{ $urgent->opportunity->name ?? 'Application' }} — {{ $urgent->app_stage }}
                                    </span>
                                @else
                                    <span class="chip static gray">No applications yet</span>
                                @endif
                            </td>
                            <td><a href="{{ route('students.detail', $student) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No students yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
