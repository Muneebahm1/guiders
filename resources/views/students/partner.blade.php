@extends('layouts.app')

@section('title', 'My Registered Students')
@section('subtitle', "Students you've referred, and where their case stands")

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Registered Students</h2><p>&nbsp;</p></div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#partnerForm">
                + Register Student
            </button>
        </div>

        <div class="collapse" id="partnerForm">
            <form method="POST" action="{{ route('students.register') }}" class="form-row">
                @csrf
                <input class="form-control" name="name" placeholder="Student full name" required>
                <select class="form-select" name="opportunity_id" required>
                    <option value="">Choose program&hellip;</option>
                    @foreach ($opportunities as $opportunity)
                        <option value="{{ $opportunity->id }}">{{ $opportunity->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Applications</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="name-cell">{{ $student->name }}</td>
                            <td>{{ $student->applications->count() }}</td>
                            <td>
                                @forelse ($student->applications as $application)
                                    @php
                                        $appClass = match($application->app_stage) {
                                            'Accepted' => 'teal', 'Rejected' => 'coral', 'Not Applied' => 'gray', default => 'amber',
                                        };
                                    @endphp
                                    <span class="chip static {{ $appClass }}">{{ $application->opportunity->name ?? 'Application' }} — {{ $application->app_stage }}</span>
                                @empty
                                    <span class="chip static gray">No applications yet</span>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="empty-state">No students registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
