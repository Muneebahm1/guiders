@extends('layouts.app')

@section('title', 'My Progress')
@section('subtitle', 'Where your case stands right now')

@section('content')
    @if (! $student)
        <p class="text-muted">No student profile found.</p>
    @else
        <div class="readonly-banner">
            This is read-only. Your counselor updates each stage as your case moves forward &mdash; reach out to them with questions.
        </div>

        @include('partials._reminder_banner', ['reminders' => $reminders ?? collect()])

        <p class="text-muted">Counselor: {{ $student->counselor->name ?? 'Not yet assigned' }}</p>

        @forelse ($student->applications as $application)
            @include('students._application_timeline', ['application' => $application, 'editable' => false])
        @empty
            <div class="panel"><p class="empty-state">No applications on file yet.</p></div>
        @endforelse
    @endif
@endsection
