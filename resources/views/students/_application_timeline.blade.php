@php
    $editable = $editable ?? false;

    $steps = [
        ['label' => 'Application Submitted'],
        ['label' => 'Waiting for Acceptance'],
        ['label' => 'Accepted'],
        ['label' => 'Fee — '.$application->fee_status, 'date' => $application->fee_date?->toDateString()],
        ['label' => 'Visa Documentation'],
        ['label' => 'Visa Submitted', 'date' => $application->visa_submission_date?->toDateString()],
        ['label' => 'Visa Approved'],
        ['label' => $application->travel_status === 'Travelled' ? 'Travelled' : 'Cleared to Travel', 'date' => $application->travel_date?->toDateString()],
    ];

    $currentIndex = match(true) {
        $application->app_stage === 'Not Applied' => -1,
        $application->app_stage === 'Submitted' => 0,
        $application->app_stage === 'Waiting for Acceptance' => 1,
        $application->app_stage === 'Rejected' => 0,
        $application->fee_status !== 'Paid' => 3,
        $application->visa_stage === 'Not Started' => 4,
        $application->visa_stage === 'Documentation' => 4,
        $application->visa_stage === 'Submitted' => 5,
        $application->visa_stage === 'Rejected' => 5,
        $application->visa_stage === 'Approved' && $application->travel_status === 'Not Ready' => 6,
        default => 7,
    };

    $rejected = $application->app_stage === 'Rejected' ? 1 : ($application->visa_stage === 'Rejected' ? 5 : -1);
@endphp

<div class="panel application-panel" data-application-id="{{ $application->id }}">
    <div class="panel-head">
        <div>
            <h2>{{ $application->opportunity->name ?? 'No program assigned yet' }}</h2>
            <p>
                @if ($application->opportunity)
                    {{ $application->opportunity->country }} &middot;
                @endif
                Fee: {{ $application->fee_status }} &middot; Visa: {{ $application->visa_stage }} &middot; Travel: {{ $application->travel_status }}
            </p>
        </div>
        @if ($editable)
            <div class="d-flex gap-2 flex-wrap">
                <span class="chip app-stage-chip {{ match($application->app_stage) { 'Accepted' => 'teal', 'Rejected' => 'coral', 'Not Applied' => 'gray', default => 'amber' } }}"
                      data-url="{{ route('applications.cycle-app-stage', $application) }}">{{ $application->app_stage }}</span>
                <span class="chip visa-stage-chip {{ match($application->visa_stage) { 'Approved' => 'teal', 'Rejected' => 'coral', 'Not Started' => 'gray', default => 'amber' } }}"
                      data-url="{{ route('applications.cycle-visa-stage', $application) }}">{{ $application->visa_stage }}</span>
                <span class="chip travel-status-chip {{ match($application->travel_status) { 'Travelled' => 'teal', 'Cleared to Travel' => 'gold', default => 'gray' } }}"
                      data-url="{{ route('applications.cycle-travel-status', $application) }}">{{ $application->travel_status }}</span>
                <a href="{{ route('clearance.show', $application) }}" class="chip static gray">Clearance report</a>
                <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Remove this application?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="chip static coral" style="border:none;">Remove</button>
                </form>
            </div>
        @endif
    </div>

    <div class="timeline">
        @foreach ($steps as $i => $step)
            @php
                $state = $rejected === $i ? 'rejected' : ($i < $currentIndex ? 'done' : ($i === $currentIndex ? 'current' : ''));
            @endphp
            <div class="tl-step {{ $state }}">
                <div class="tl-line"></div>
                <div class="tl-dot">{{ $state === 'done' ? '✓' : ($state === 'rejected' ? '✕' : $i + 1) }}</div>
                <div class="tl-body">
                    <div class="tl-title">{{ $step['label'] }}</div>
                    @if (!empty($step['date']))
                        <div class="tl-date">{{ $step['date'] }}</div>
                    @endif
                    @if ($state === 'current')
                        <div class="tl-note">In progress</div>
                    @endif
                    @if ($state === 'rejected')
                        <div class="tl-note">Not moving forward at this stage</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($application->installments->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>Installment</th><th>Amount</th><th>Due</th><th>Paid</th>@if($editable)<th></th>@endif</tr></thead>
                <tbody>
                    @foreach ($application->installments as $installment)
                        <tr>
                            <td>{{ $installment->title }}</td>
                            <td>{{ $installment->currency }} {{ number_format($installment->amount, 2) }}</td>
                            <td class="{{ ! $installment->paid_date && $installment->due_date->isPast() ? 'text-danger fw-bold' : '' }}">{{ $installment->due_date->toDateString() }}</td>
                            <td>{{ $installment->paid_date?->toDateString() ?? '—' }}</td>
                            @if ($editable)
                                <td>
                                    @if (! $installment->paid_date)
                                        <form method="POST" action="{{ route('installments.mark-paid', $installment) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Mark paid</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($editable)
        <form method="POST" action="{{ route('installments.store', $application) }}" class="form-row p-3 border-top">
            @csrf
            <input class="form-control" name="title" placeholder="Installment title" required>
            <input class="form-control" name="amount" type="number" step="0.01" min="0" placeholder="Amount" required>
            <input class="form-control" name="currency" placeholder="Currency" value="USD" style="max-width:100px" required>
            <input class="form-control" name="due_date" type="date" required>
            <button class="btn btn-outline-primary" type="submit">+ Add Installment</button>
        </form>
    @endif
</div>
