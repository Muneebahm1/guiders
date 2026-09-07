@extends('layouts.app')

@section('title', $student->name)
@section('subtitle', 'All applications for this student')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div>
                <h2>{{ $student->name }}</h2>
                <p>Counselor: {{ $student->counselor->name ?? ($student->partner ? 'Partner: '.$student->partner->name : 'Unassigned') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('invoices.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm">Generate Invoice</a>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#addApplication">
                    + Add Application
                </button>
            </div>
        </div>

        <div class="collapse" id="addApplication">
            <form method="POST" action="{{ route('applications.store', $student) }}" class="form-row p-3 border-top">
                @csrf
                <select class="form-select" name="opportunity_id" required>
                    <option value="">Choose program/journal&hellip;</option>
                    @foreach ($opportunities as $opportunity)
                        <option value="{{ $opportunity->id }}">{{ $opportunity->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Add</button>
            </form>
        </div>
    </div>

    @forelse ($student->applications as $application)
        @include('students._application_timeline', ['application' => $application, 'editable' => true])
    @empty
        <div class="panel"><p class="empty-state">No applications yet — add one above.</p></div>
    @endforelse
@endsection

@push('scripts')
<script>
    $(function () {
        const appColors = { Accepted: 'teal', Rejected: 'coral', 'Not Applied': 'gray' };
        const visaColors = { Approved: 'teal', Rejected: 'coral', 'Not Started': 'gray' };
        const travelColors = { Travelled: 'teal', 'Cleared to Travel': 'gold' };
        const allColors = ['teal', 'coral', 'gray', 'amber', 'gold'];

        $(document).on('click', '.app-stage-chip', function () {
            const $chip = $(this);
            $.ajax({ url: $chip.data('url'), method: 'PATCH', success: function (data) {
                $chip.text(data.app_stage);
                $chip.removeClass(allColors.join(' ')).addClass(appColors[data.app_stage] || 'amber');
            }});
        });

        $(document).on('click', '.visa-stage-chip', function () {
            const $chip = $(this);
            $.ajax({ url: $chip.data('url'), method: 'PATCH', success: function (data) {
                $chip.text(data.visa_stage);
                $chip.removeClass(allColors.join(' ')).addClass(visaColors[data.visa_stage] || 'amber');
            }});
        });

        $(document).on('click', '.travel-status-chip', function () {
            const $chip = $(this);
            $.ajax({
                url: $chip.data('url'), method: 'PATCH',
                success: function (data) {
                    $chip.text(data.travel_status);
                    $chip.removeClass(allColors.join(' ')).addClass(travelColors[data.travel_status] || 'gray');
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.error || 'Could not update travel status.');
                },
            });
        });
    });
</script>
@endpush
