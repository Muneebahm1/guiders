@extends('layouts.app')

@section('title', 'Staff Monthly Report')
@section('subtitle', 'Leads, follow-ups, applications, and collections by counselor')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Filters</h2><p>&nbsp;</p></div>
        </div>
        <form method="GET" action="{{ route('reports.staff') }}" class="form-row p-3">
            <input class="form-control" type="month" name="month" value="{{ $month }}">
            <select class="form-select" name="counselor_id">
                <option value="">All counselors</option>
                @foreach ($allCounselors as $counselor)
                    <option value="{{ $counselor->id }}" {{ $selectedCounselorId == $counselor->id ? 'selected' : '' }}>{{ $counselor->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Apply</button>
        </form>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div><h2>Counselor Activity — {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h2><p>&nbsp;</p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Counselor</th>
                        <th>Leads added</th>
                        <th>Follow-ups logged</th>
                        <th>Students registered</th>
                        <th>Applications added</th>
                        <th>Amount collected</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td class="name-cell">{{ $row['counselor']->name }}</td>
                            <td>{{ $row['leads_added'] }}</td>
                            <td>{{ $row['follow_ups_logged'] }}</td>
                            <td>{{ $row['students_registered'] }}</td>
                            <td>{{ $row['applications_added'] }}</td>
                            <td>{{ number_format($row['amount_collected'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No counselors found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
