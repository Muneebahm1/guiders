@extends('layouts.app')

@section('title', 'Contract — '.$contract->student->name)
@section('subtitle', 'Student Service Agreement')

@section('content')
    <div class="panel print-area">
        <div class="panel-head no-print">
            <div><h2>Contract for {{ $contract->student->name }}</h2><p>&nbsp;</p></div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
        </div>

        <div class="contract-sheet p-4">
            <div class="text-center mb-2" style="border-bottom:2px solid #1F4E86;padding-bottom:8px;">
                <div class="fw-bold" style="font-size:18px;">{{ $company->company_name ?? 'THE GUIDERS' }}</div>
                <div class="text-muted small fst-italic">Overseas Educational Services</div>
            </div>

            <h2 class="text-center mb-3" style="letter-spacing:1px;font-size:19px;">STUDENT SERVICE AGREEMENT</h2>

            <div class="row row-cols-2 g-1 mb-3" style="font-size:12.5px;">
                <div class="col"><span class="text-muted">Student Name:</span> <strong>{{ $contract->student->name }}</strong></div>
                <div class="col"><span class="text-muted">Father / Guardian:</span> <strong>{{ $contract->guardian_name ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">CNIC / Passport #:</span> <strong>{{ $contract->cnic ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">Contact Number:</span> <strong>{{ $contract->phone ?: '—' }}</strong></div>
                <div class="col no-print"><span class="text-muted">Client Email:</span> <strong>{{ $contract->client_email ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">Destination Country:</span> <strong>{{ $contract->country ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">University / Institution:</span> <strong>{{ $contract->university_tbd ? 'To be finalized by The Guiders' : ($contract->university ?: '—') }}</strong></div>
                <div class="col"><span class="text-muted">Program / Course:</span> <strong>{{ $contract->course ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">Intake / Session:</span> <strong>{{ $contract->intake ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">Service Type:</span> <strong>{{ $contract->service_type ?: '—' }}</strong></div>
                <div class="col"><span class="text-muted">Agreement Date:</span> <strong>{{ $contract->created_at->format('M j, Y') }}</strong></div>
                <div class="col"><span class="text-muted">Total Contract Fee:</span> <strong>{{ $contract->total_fee ? number_format($contract->total_fee, 2).' '.$contract->currency : '—' }}</strong></div>
            </div>

            <div class="fw-bold text-uppercase small mb-1" style="color:#1F4E86;letter-spacing:.04em;font-size:11px;">Service Details for This Student</div>
            <p class="mb-2" style="font-size:12.5px;white-space:pre-wrap;">{{ $contract->service_detail }}</p>

            <div class="fw-bold text-uppercase small mb-1 mt-2" style="color:#1F4E86;letter-spacing:.04em;font-size:11px;">Terms &amp; Conditions</div>
            @if ($contract->selectedClauses())
                <ol class="mb-2" style="font-size:12.5px;">
                    @foreach ($contract->selectedClauses() as $clause)
                        <li class="mb-1"><strong>{{ $clause['title'] }}.</strong> {{ $clause['text'] }}</li>
                    @endforeach
                </ol>
            @endif

            @if ($contract->custom_terms)
                <div class="fw-bold text-uppercase small mb-1 mt-2" style="color:#1F4E86;letter-spacing:.04em;font-size:11px;">Additional Terms</div>
                <p class="mb-2" style="font-size:12.5px;white-space:pre-wrap;">{{ $contract->custom_terms }}</p>
            @endif

            <div class="fw-bold text-uppercase small mb-1 mt-2" style="color:#1F4E86;letter-spacing:.04em;font-size:11px;">Payment Installment Schedule</div>
            @if ($contract->installments->isEmpty())
                <p class="text-muted small mb-2">No installments added.</p>
            @else
                <table class="table table-sm mb-2" style="font-size:12px;">
                    <thead style="background:#E7EEF7;">
                        <tr><th>#</th><th>Payment Remarks</th><th>Amount ({{ $contract->currency }})</th><th>Due date</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($contract->installments as $i => $installment)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $installment->description ?: '—' }}</td>
                                <td>{{ number_format($installment->amount, 2) }}</td>
                                <td>{{ $installment->due_date?->format('M j, Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="fw-bold text-uppercase small mb-1 mt-2" style="color:#1F4E86;letter-spacing:.04em;font-size:11px;">Requirements to be Fulfilled by Student</div>
            @if (empty($contract->requirements))
                <p class="text-muted small mb-2">No specific requirements listed.</p>
            @else
                <ul class="mb-2" style="font-size:12px;">
                    @foreach ($contract->requirements as $req)
                        <li class="mb-0">{{ $req['text'] }}@if (empty($req['required'])) <span class="text-muted small">(optional)</span>@endif</li>
                    @endforeach
                </ul>
            @endif

            <div class="row g-3 mt-2">
                <div class="col-6">
                    <div style="border-top:1px solid #1B1F22;padding-top:4px;" class="small text-muted">Signature — The Guiders Representative</div>
                </div>
                <div class="col-6">
                    <div style="border-top:1px solid #1B1F22;padding-top:4px;" class="small text-muted">Signature — Student / Guardian</div>
                </div>
            </div>

            <div class="text-muted small mt-3 no-print">Generated by {{ $contract->creator->name }}</div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        .sidebar, .topbar, .no-print { display: none !important; }

        body, .content, .main-content { margin: 0 !important; padding: 0 !important; }

        .panel.print-area {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }

        .contract-sheet {
            padding: 0 !important;
            font-size: 11px;
            line-height: 1.3;
        }

        .contract-sheet table { font-size: 10.5px; }
        .contract-sheet table th, .contract-sheet table td { padding: .25rem .4rem !important; }
        .contract-sheet p, .contract-sheet ol, .contract-sheet ul { margin-bottom: .3rem !important; }
        .contract-sheet li { margin-bottom: .1rem !important; }
    }
</style>
@endpush
