@extends('layouts.app')

@section('title', auth()->user()->hasBackOfficeAccess() ? 'Research Publication — All Submissions' : 'My Research Papers')
@section('subtitle', auth()->user()->hasBackOfficeAccess() ? 'Time in review is tracked from submission date' : 'Click status to advance through review')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Submissions</h2><p>&nbsp;</p></div>
            @if (auth()->user()->isCounselor())
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#paperForm">
                    + Log Submission
                </button>
            @endif
        </div>

        @if (auth()->user()->isCounselor())
            <div class="collapse" id="paperForm">
                <form method="POST" action="{{ route('papers.store') }}" class="form-row">
                    @csrf
                    <input class="form-control" name="author_name" placeholder="Author / student name" required>
                    <select class="form-select" name="opportunity_id" required>
                        <option value="">Choose journal&hellip;</option>
                        @foreach ($journals as $journal)
                            <option value="{{ $journal->id }}">{{ $journal->name }}</option>
                        @endforeach
                    </select>
                    <input class="form-control" type="date" name="submitted_date" required>
                    <button class="btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Author</th>
                        @if (auth()->user()->hasBackOfficeAccess())
                            <th>Counselor</th>
                        @endif
                        <th>Journal</th>
                        <th>Submitted</th>
                        <th>In review</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($papers as $paper)
                        <tr>
                            <td class="name-cell">{{ $paper->author_name }}</td>
                            @if (auth()->user()->hasBackOfficeAccess())
                                <td>{{ $paper->counselor->name }}</td>
                            @endif
                            <td>{{ $paper->opportunity->name }}</td>
                            <td class="date-cell">{{ $paper->submitted_date->toDateString() }}</td>
                            <td class="review-days">{{ $paper->daysInReview() }}d</td>
                            <td>
                                <span class="chip paper-status-chip
                                    {{ match($paper->status) {
                                        'Accepted' => 'teal',
                                        'Rejected' => 'coral',
                                        default => 'amber',
                                    } }}" data-url="{{ route('papers.cycle-status', $paper) }}">
                                    {{ $paper->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $(document).on('click', '.paper-status-chip', function () {
            const $chip = $(this);
            $.ajax({ url: $chip.data('url'), method: 'PATCH', success: function (data) {
                $chip.text(data.status);
                $chip.removeClass('teal coral amber');
                const map = { Accepted: 'teal', Rejected: 'coral' };
                $chip.addClass(map[data.status] || 'amber');
            }});
        });
    });
</script>
@endpush
