@extends('layouts.app')

@section('title', 'All Leads')
@section('subtitle', 'Every lead across all counselors — read-only')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Leads</h2><p>{{ $leads->count() }} total</p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Counselor</th>
                        <th>Contact number</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td class="name-cell">{{ $lead->name }}</td>
                            <td>{{ $lead->counselor->name ?? '—' }}</td>
                            <td>{{ $lead->contact ?: '—' }}</td>
                            <td>{{ $lead->source }}</td>
                            <td>
                                <span class="chip static {{ match($lead->status) {
                                    'Registered' => 'teal',
                                    'Visit' => 'gold',
                                    'New' => 'gray',
                                    default => 'amber',
                                } }}">{{ $lead->status }}</span>
                            </td>
                            <td>
                                @if ($lead->hasProfile())
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#leadProfile{{ $lead->id }}">
                                        View profile
                                    </button>
                                @else
                                    <span class="sub-cell">No profile yet</span>
                                @endif
                            </td>
                        </tr>
                        @if ($lead->hasProfile())
                            <tr class="collapse" id="leadProfile{{ $lead->id }}">
                                <td colspan="6" class="p-3" style="background:#FAFBFD">
                                    <div class="row g-2 small">
                                        <div class="col-md-3"><strong>Desired program:</strong> {{ $lead->desired_program ?: '—' }}</div>
                                        <div class="col-md-3"><strong>Country:</strong> {{ $lead->country ?: '—' }}</div>
                                        <div class="col-md-3"><strong>Budget:</strong> {{ $lead->budget ? 'USD '.$lead->budget : '—' }}</div>
                                        <div class="col-md-3"><strong>City:</strong> {{ $lead->city ?: '—' }}</div>
                                        <div class="col-md-3"><strong>Last qualification:</strong> {{ $lead->last_qualification ?: '—' }}</div>
                                        <div class="col-md-3"><strong>CGPA:</strong> {{ $lead->cgpa ?: '—' }}</div>
                                        <div class="col-md-3"><strong>Age:</strong> {{ $lead->age ?: '—' }}</div>
                                    </div>
                                    @if ($lead->suggestion)
                                        <div class="mt-2 small"><strong>Suggestion:</strong> {{ $lead->suggestion }}</div>
                                    @endif
                                    @if ($lead->remarks)
                                        <div class="mt-1 small"><strong>Remarks:</strong> {{ $lead->remarks }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="6" class="empty-state">No leads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
