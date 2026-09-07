@if ($opportunities->isEmpty())
    <div class="board-empty">No matching programs within that budget.</div>
@else
    <div class="board-row header">
        <div>Program / Journal</div><div>Type</div><div>Country</div><div>Cost</div><div>Deadline</div>
    </div>
    @foreach ($opportunities as $opportunity)
        @php $days = $opportunity->daysLeft(); @endphp
        <div class="board-row">
            <div>
                <div class="board-prog">{{ $opportunity->name }}</div>
                <div class="board-req">{{ $opportunity->requirements }}</div>
            </div>
            <div>
                <span class="type-tag {{ $opportunity->type === 'study_abroad' ? 'sa' : 'rp' }}">
                    {{ $opportunity->type === 'study_abroad' ? 'STUDY' : 'PAPER' }}
                </span>
            </div>
            <div class="board-country">{{ $opportunity->country }}</div>
            <div class="flap">{{ $opportunity->currency }} {{ number_format($opportunity->cost, 0) }}</div>
            <div class="countdown {{ $days < 0 ? 'late' : ($days <= 14 ? 'soon' : 'ok') }}">
                {{ $days < 0 ? 'T+'.abs($days).'D LATE' : ($days === 0 ? 'TODAY' : 'T-'.$days.'D') }}
            </div>
        </div>
    @endforeach
@endif
