@extends('layouts.app')

@section('title', 'Clearance Report — '.$application->student->name)
@section('subtitle', $application->opportunity->name ?? 'Application')

@section('content')
    <div class="panel print-area">
        <div class="panel-head">
            <div>
                <h2>Dues Clearance Report</h2>
                <p>{{ $application->student->name }} &mdash; {{ $application->opportunity->name ?? 'No program' }}</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm no-print" onclick="window.print()">Print</button>
        </div>

        <div class="p-3">
            @if ($clearance->signed_off)
                <div class="alert alert-success">
                    Signed off by {{ $clearance->signedOffBy->name ?? 'staff' }} on {{ $clearance->signed_off_at?->toDateString() }} &mdash; cleared to travel.
                </div>
            @endif

            <table class="table table-sm mb-4">
                <thead><tr><th>Installment</th><th>Amount</th><th>Due</th><th>Paid</th></tr></thead>
                <tbody>
                    @forelse ($application->installments as $installment)
                        <tr>
                            <td>{{ $installment->title }}</td>
                            <td>{{ $installment->currency }} {{ number_format($installment->amount, 2) }}</td>
                            <td>{{ $installment->due_date->toDateString() }}</td>
                            <td>{{ $installment->paid_date?->toDateString() ?? 'Unpaid' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No installments recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <form method="POST" action="{{ route('clearance.update', $application) }}" class="no-print">
                @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="tuition_cleared" value="1" id="tuition_cleared" {{ $clearance->tuition_cleared ? 'checked' : '' }}>
                    <label class="form-check-label" for="tuition_cleared">Tuition fees cleared</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="service_dues_cleared" value="1" id="service_dues_cleared" {{ $clearance->service_dues_cleared ? 'checked' : '' }}>
                    <label class="form-check-label" for="service_dues_cleared">Service dues cleared</label>
                </div>

                <textarea class="form-control mb-3" name="remarks" rows="3" placeholder="Remarks">{{ $clearance->remarks }}</textarea>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="signed_off" value="1" id="signed_off" {{ $clearance->signed_off ? 'checked' : '' }}>
                    <label class="form-check-label" for="signed_off">Sign off &mdash; cleared to travel (requires all installments paid)</label>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
    }
</style>
@endpush
