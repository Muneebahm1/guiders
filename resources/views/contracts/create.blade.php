@extends('layouts.app')

@section('title', 'Generate Contract')
@section('subtitle', 'Standard student service agreement')

@push('styles')
<style>
    .doc-preview {
        background: #fff;
        border: 1px solid var(--line, #DEDCD3);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 28px;
        position: sticky;
        top: 24px;
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        border-radius: 12px;
        font-size: 13px;
    }
    .doc-preview .doc-letterhead { border-bottom: 2px solid #1F4E86; padding-bottom: 14px; }
    .doc-preview .doc-grid .col { padding: 4px 0; }
    .doc-preview .doc-section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #1F4E86;
        margin: 20px 0 8px;
    }
    .doc-preview .doc-inst-table th {
        background: #E7EEF7;
        font-size: 10.5px;
        text-transform: uppercase;
    }
    .doc-preview .doc-inst-table td, .doc-preview .doc-inst-table th { padding: 6px 8px; }
    .doc-preview .doc-sign-line {
        border-top: 1px solid #1B1F22;
        margin-top: 36px;
        padding-top: 6px;
        font-size: 11px;
        color: #5B6570;
    }
    @media (max-width: 991px) {
        .doc-preview { position: static; max-height: none; }
    }
</style>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Choose student</h2><p>&nbsp;</p></div>
        </div>
        <form method="GET" action="{{ route('contracts.create') }}" class="form-row p-3">
            <select class="form-select" name="student_id" onchange="this.form.submit()">
                <option value="">Select student&hellip;</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id === $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($selectedStudent)
    <div class="row g-3">
        <div class="col-lg-7">
        <form method="POST" action="{{ route('contracts.store') }}" id="contractForm">
            @csrf
            <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Student &amp; Guardian Details</h2><p>{{ $selectedStudent->name }}</p></div>
                </div>
                <div class="row g-3 p-3">
                    <div class="col-md-4">
                        <label class="form-label">Father / guardian name</label>
                        <input class="form-control" name="guardian_name" placeholder="e.g. Ishfaq Ahmed">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CNIC / Passport #</label>
                        <input class="form-control" name="cnic" placeholder="e.g. 37405-XXXXXXX-1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact number</label>
                        <input class="form-control" name="phone" placeholder="03XX XXXXXXX">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Client email</label>
                        <input class="form-control" type="email" name="client_email" placeholder="client@example.com">
                        <div class="form-text">A copy of the contract summary is emailed here, and to you as the creator.</div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Program Details</h2><p>&nbsp;</p></div>
                </div>
                <div class="row g-3 p-3">
                    <div class="col-md-4">
                        <label class="form-label">Destination country</label>
                        <select class="form-select" name="country">
                            <option>New Zealand</option>
                            <option>Finland</option>
                            <option>Netherlands</option>
                            <option>Georgia</option>
                            <option>Malaysia</option>
                            <option>Cyprus</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">University / institution</label>
                        <input class="form-control" name="university" id="universityInput" placeholder="e.g. University of Helsinki">
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" name="university_tbd" value="1" id="universityTbd">
                            <label class="form-check-label small text-muted" for="universityTbd">University/institute to be finalized by The Guiders</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Program / course</label>
                        <input class="form-control" name="course" placeholder="e.g. BSc Nursing">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Service type</label>
                        <select class="form-select" name="service_type">
                            <option>University Admission &amp; Visa Processing</option>
                            <option>Vocational Program Placement</option>
                            <option>PhD / Research Placement</option>
                            <option>Teacher Training Placement</option>
                            <option>IELTS / PTE Coaching</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Intake / session</label>
                        <input class="form-control" name="intake" placeholder="e.g. Fall 2026">
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Service Details for This Student <span class="text-danger">*</span></h2><p>&nbsp;</p></div>
                </div>
                <div class="p-3">
                    <textarea class="form-control" name="service_detail" rows="3" required placeholder="e.g. Shortlist 3 vocational institutes in Finland, prepare and submit application, arrange language test booking, handle full visa filing and interview prep."></textarea>
                    <div class="form-text">Required — describe the specific services being delivered to this student.</div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Student Requirements to Fulfill</h2><p>&nbsp;</p></div>
                </div>
                <div class="p-3">
                    <div id="reqList">
                        @foreach ([
                            ['text' => 'Valid passport (minimum 6 months validity)', 'required' => true],
                            ['text' => 'Academic transcripts and certificates (attested)', 'required' => true],
                            ['text' => 'IELTS / PTE / language test result', 'required' => true],
                            ['text' => 'Bank statement / proof of funds', 'required' => true],
                            ['text' => 'Statement of Purpose (SOP)', 'required' => false],
                        ] as $i => $req)
                            <div class="d-flex align-items-center gap-2 mb-2 req-row">
                                <input type="checkbox" class="form-check-input mt-0" name="requirements[{{ $i }}][required]" value="1" {{ $req['required'] ? 'checked' : '' }} title="Required">
                                <input type="text" class="form-control" name="requirements[{{ $i }}][text]" value="{{ $req['text'] }}" placeholder="e.g. Attested academic transcripts">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-req">&times;</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addReq">+ Add requirement</button>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Fee &amp; Payment Installment Schedule</h2><p>&nbsp;</p></div>
                </div>
                <div class="p-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Total contract fee</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="total_fee" id="totalFeeInput" placeholder="e.g. 250000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Currency</label>
                            <select class="form-select" name="currency">
                                <option value="PKR">PKR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-sm" id="instTable">
                        <thead><tr><th>Payment Remarks</th><th style="width:140px">Amount</th><th style="width:170px">Due date</th><th></th></tr></thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control" name="installments[0][description]" value="Registration Fee" placeholder="e.g. Registration Fee, 1st installment"></td>
                                <td><input class="form-control inst-amount" type="number" step="0.01" min="0" name="installments[0][amount]"></td>
                                <td><input class="form-control" type="date" name="installments[0][due_date]"></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger remove-inst">&times;</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="addInst">+ Add installment</button>

                    <div class="text-end">
                        <div>Scheduled: <strong id="instScheduled">0.00</strong></div>
                        <div id="instBalance" class="small"></div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div><h2>Standard Clauses</h2><p>&nbsp;</p></div>
                </div>
                <div class="p-3">
                    @foreach (\App\Models\Contract::STANDARD_CLAUSES as $clause)
                        <div class="d-flex align-items-start gap-2 py-2 border-top">
                            <input type="checkbox" class="form-check-input mt-1" name="clauses[]" value="{{ $clause['id'] }}" id="clause_{{ $clause['id'] }}" checked>
                            <div>
                                <label class="fw-semibold small mb-0" for="clause_{{ $clause['id'] }}">{{ $clause['title'] }}</label>
                                <div class="text-muted small">{{ $clause['text'] }}</div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3">
                        <label class="form-label">Additional / custom terms</label>
                        <textarea class="form-control" name="custom_terms" rows="2" placeholder="Add any extra terms specific to this student's case..."></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Generate Contract</button>
        </form>
        </div>

        <div class="col-lg-5">
            <div class="doc-preview" id="docPreview"></div>
        </div>
    </div>
    @endif
@endsection

@if ($selectedStudent)
@push('scripts')
<script>
    const STANDARD_CLAUSES = @json(\App\Models\Contract::STANDARD_CLAUSES);
    const studentName = @json($selectedStudent->name);

    $(function () {
        let reqIndex = {{ 5 }};
        let instIndex = 1;

        $('#universityTbd').on('change', function () {
            $('#universityInput').prop('disabled', this.checked);
            if (this.checked) { $('#universityInput').val(''); }
            renderPreview();
        });

        $('#addReq').on('click', function () {
            const row = `<div class="d-flex align-items-center gap-2 mb-2 req-row">
                <input type="checkbox" class="form-check-input mt-0" name="requirements[${reqIndex}][required]" value="1" title="Required">
                <input type="text" class="form-control" name="requirements[${reqIndex}][text]" placeholder="e.g. Attested academic transcripts">
                <button type="button" class="btn btn-sm btn-outline-danger remove-req">&times;</button>
            </div>`;
            $('#reqList').append(row);
            reqIndex++;
        });
        $(document).on('click', '.remove-req', function () { $(this).closest('.req-row').remove(); renderPreview(); });

        function recalcInst() {
            let sum = 0;
            $('#instTable .inst-amount').each(function () { sum += parseFloat($(this).val()) || 0; });
            $('#instScheduled').text(sum.toFixed(2));
            const total = parseFloat($('#totalFeeInput').val()) || 0;
            const balEl = $('#instBalance');
            if (!total) { balEl.text(''); return; }
            const remaining = total - sum;
            if (Math.abs(remaining) < 0.01) { balEl.html('<span class="text-success fw-semibold">Fully scheduled</span>'); }
            else if (remaining > 0) { balEl.html('<span class="text-danger">Unscheduled: ' + remaining.toFixed(2) + '</span>'); }
            else { balEl.html('<span class="text-danger">Over total by ' + Math.abs(remaining).toFixed(2) + '</span>'); }
        }
        $(document).on('input', '.inst-amount, #totalFeeInput', recalcInst);

        $('#addInst').on('click', function () {
            const row = `<tr>
                <td><input class="form-control" name="installments[${instIndex}][description]" placeholder="e.g. Registration Fee, 1st installment"></td>
                <td><input class="form-control inst-amount" type="number" step="0.01" min="0" name="installments[${instIndex}][amount]"></td>
                <td><input class="form-control" type="date" name="installments[${instIndex}][due_date]"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-inst">&times;</button></td>
            </tr>`;
            $('#instTable tbody').append(row);
            instIndex++;
        });
        $(document).on('click', '.remove-inst', function () {
            if ($('#instTable tbody tr').length > 1) { $(this).closest('tr').remove(); recalcInst(); renderPreview(); }
        });

        function renderPreview() {
            const guardianName = $('[name=guardian_name]').val() || '—';
            const cnic = $('[name=cnic]').val() || '—';
            const phone = $('[name=phone]').val() || '—';
            const country = $('[name=country]').val() || '—';
            const universityTbd = $('#universityTbd').is(':checked');
            const university = universityTbd ? 'To be finalized by The Guiders' : ($('#universityInput').val() || '—');
            const course = $('[name=course]').val() || '—';
            const serviceType = $('[name=service_type]').val() || '—';
            const intake = $('[name=intake]').val() || '—';
            const serviceDetail = $('[name=service_detail]').val();
            const totalFee = parseFloat($('#totalFeeInput').val());
            const currency = $('[name=currency]').val();
            const totalFeeText = totalFee ? totalFee.toLocaleString() + ' ' + currency : '—';
            const customTerms = $('[name=custom_terms]').val();
            const today = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            const checkedClauseIds = $('input[name="clauses[]"]:checked').map(function () { return this.value; }).get();
            const clausesHtml = STANDARD_CLAUSES.filter(c => checkedClauseIds.includes(c.id))
                .map(c => `<li class="mb-2"><strong>${c.title}.</strong> ${c.text}</li>`).join('');

            const installments = [];
            $('#instTable tbody tr').each(function () {
                installments.push({
                    desc: $(this).find('td:eq(0) input').val(),
                    amount: parseFloat($(this).find('.inst-amount').val()),
                    date: $(this).find('td:eq(2) input').val(),
                });
            });
            const instHtml = installments.length ? `
                <table class="table table-sm doc-inst-table">
                    <thead><tr><th>#</th><th>Payment Remarks</th><th>Amount (${currency})</th><th>Due date</th></tr></thead>
                    <tbody>${installments.map((i, idx) => `<tr><td>${idx + 1}</td><td>${i.desc || '—'}</td><td>${i.amount ? i.amount.toLocaleString() : '—'}</td><td>${i.date || '—'}</td></tr>`).join('')}</tbody>
                </table>` : '<p class="text-muted small">No installments added.</p>';

            const requirements = [];
            $('#reqList .req-row').each(function () {
                const text = $(this).find('input[type=text]').val();
                if (text && text.trim() !== '') {
                    requirements.push({ text: text, required: $(this).find('input[type=checkbox]').is(':checked') });
                }
            });
            const reqHtml = requirements.length ? `
                <ul class="mb-0">
                    ${requirements.map(r => `<li class="mb-1">${r.text}${r.required ? '' : ' <span class="text-muted small">(optional)</span>'}</li>`).join('')}
                </ul>` : '<p class="text-muted small">No specific requirements listed.</p>';

            $('#docPreview').html(`
                <div class="text-center mb-4 doc-letterhead">
                    <div class="fw-bold" style="font-size:18px;">THE GUIDERS</div>
                    <div class="text-muted small fst-italic">Overseas Educational Services</div>
                </div>
                <h2 class="text-center mb-4" style="font-size:17px;letter-spacing:1px;">STUDENT SERVICE AGREEMENT</h2>
                <div class="row row-cols-2 g-2 mb-4 doc-grid">
                    <div class="col"><span class="text-muted">Student Name</span><br><strong>${studentName}</strong></div>
                    <div class="col"><span class="text-muted">Father / Guardian</span><br><strong>${guardianName}</strong></div>
                    <div class="col"><span class="text-muted">CNIC / Passport #</span><br><strong>${cnic}</strong></div>
                    <div class="col"><span class="text-muted">Contact Number</span><br><strong>${phone}</strong></div>
                    <div class="col"><span class="text-muted">Destination Country</span><br><strong>${country}</strong></div>
                    <div class="col"><span class="text-muted">University / Institution</span><br><strong>${university}</strong></div>
                    <div class="col"><span class="text-muted">Program / Course</span><br><strong>${course}</strong></div>
                    <div class="col"><span class="text-muted">Intake / Session</span><br><strong>${intake}</strong></div>
                    <div class="col"><span class="text-muted">Service Type</span><br><strong>${serviceType}</strong></div>
                    <div class="col"><span class="text-muted">Agreement Date</span><br><strong>${today}</strong></div>
                    <div class="col"><span class="text-muted">Total Contract Fee</span><br><strong>${totalFeeText}</strong></div>
                </div>

                <div class="doc-section-title">Service Details for This Student</div>
                <div class="mb-3" style="font-size:13px;white-space:pre-wrap;">${serviceDetail ? serviceDetail : '<span class="text-danger">Not yet filled in — required before this contract is finalized.</span>'}</div>

                <div class="doc-section-title">Terms &amp; Conditions</div>
                <ol style="font-size:13px;">${clausesHtml || '<li class="text-muted">No standard clauses selected.</li>'}</ol>

                ${customTerms ? `<div class="doc-section-title">Additional Terms</div><div class="mb-3" style="font-size:13px;white-space:pre-wrap;">${customTerms}</div>` : ''}

                <div class="doc-section-title">Payment Installment Schedule</div>
                ${instHtml}

                <div class="doc-section-title">Requirements to be Fulfilled by Student</div>
                ${reqHtml}

                <div class="row g-4 mt-4">
                    <div class="col-6"><div class="doc-sign-line">Signature — The Guiders Representative</div></div>
                    <div class="col-6"><div class="doc-sign-line">Signature — Student / Guardian</div></div>
                </div>
            `);
        }

        $('#contractForm').on('input change', renderPreview);

        recalcInst();
        renderPreview();
    });
</script>
@endpush
@endif
