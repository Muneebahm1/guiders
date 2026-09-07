@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Your pipeline at a glance')

@section('content')
    @include('partials._reminder_banner', ['reminders' => $reminders ?? collect()])

    <div class="stat-row">
        @foreach ($stats as $label => $value)
            <div class="stat-card">
                <div class="stat-num">{{ $value }}</div>
                <div class="stat-label">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="panel-head">
            <div><h2>Quick check</h2><p>Confirms the stack (AJAX + Eloquent) is wired end-to-end</p></div>
        </div>
        <div class="p-3">
            <button id="ping-btn" type="button" class="btn btn-sm btn-secondary">
                Ping (AJAX + Eloquent)
            </button>
            <div id="ping-result" class="mt-3"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#ping-btn').on('click', function () {
            $.get('{{ route('home.ping') }}', function (data) {
                $('#ping-result').html(
                    '<pre class="bg-light p-2 border rounded">' + JSON.stringify(data, null, 2) + '</pre>'
                );
            });
        });
    });
</script>
@endpush
