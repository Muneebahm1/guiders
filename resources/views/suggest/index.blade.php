@extends('layouts.app')

@section('title', 'Suggest for Client')
@section('subtitle', "Enter a client's interest and budget to get matching programs")

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Match Finder</h2><p>&nbsp;</p></div>
        </div>

        <div class="suggest-form">
            <div>
                <label>Client name (optional)</label>
                <input id="suggestClient" class="form-control form-control-sm" placeholder="e.g. Hamza Tariq">
            </div>
            <div>
                <label>Interest / program</label>
                <input id="suggestKeyword" class="form-control form-control-sm" placeholder="e.g. MBA, Data Science">
            </div>
            <div>
                <label>Budget</label>
                <input id="suggestBudget" type="number" class="form-control form-control-sm" placeholder="e.g. 2000">
            </div>
            <div>
                <button id="suggestBtn" class="btn btn-primary btn-sm" type="button">Find Matches</button>
            </div>
        </div>

        <div class="board" id="suggestBoard">
            <div class="board-empty">Enter a keyword or budget above and click Find Matches.</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#suggestBtn').on('click', function () {
            $.get('{{ route('suggest.search') }}', {
                q: $('#suggestKeyword').val(),
                budget: $('#suggestBudget').val(),
            }, function (html) {
                $('#suggestBoard').html(html);
            });
        });
    });
</script>
@endpush
