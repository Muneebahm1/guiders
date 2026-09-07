@extends('layouts.app')

@section('title', 'Follow-ups')
@section('subtitle', 'Your own tracking sheet, turned into call/visit reminders')

@section('content')
    <div class="stat-row">
        @foreach ($stats as $label => $value)
            <div class="stat-card">
                <div class="stat-num">{{ $value }}</div>
                <div class="stat-label">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    {{-- <div class="panel">
        <div class="panel-head">
            <div><h2>Bulk Import (optional)</h2><p>Upload a legacy tracking sheet — rows match to existing leads by name, or create new ones</p></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#emailComposer">
                    📧 Email New Opportunity
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addLeadForm">
                    + Add Lead
                </button>
            </div>
        </div>

        <div class="collapse" id="emailComposer">
            <div class="p-3 border-bottom" style="background:#F0FBF7">
                <div class="mb-2">
                    <label style="font-size:11px;color:var(--muted);">New opportunity:</label>
                    <select id="emailOpp" class="form-select" style="padding:6px 10px;border:1px solid var(--line);border-radius:6px;font-size:12px;"></select>
                </div>
                <input id="emailSubject" class="form-control mb-2" placeholder="Subject" style="width:100%;padding:8px 10px;border:1px solid var(--line);border-radius:6px;font-size:12.5px;">
                <textarea id="emailBody" class="form-control mb-2" rows="3">Hi {name}, we wanted to let you know about a new opportunity that might interest you...</textarea>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <button id="emailSendBtn" class="btn btn-sm btn-primary" type="button">Send Email to Selected</button>
                    <span id="emailCount" class="text-muted small">0 selected</span>
                </div>
                <div id="emailConfirm" class="small text-success mt-2" style="display:none"></div>
            </div>
        </div>

        <div class="collapse" id="addLeadForm">
            <form method="POST" action="{{ route('leads.store') }}" class="form-row">
                @csrf
                <input class="form-control" name="name" placeholder="Full name" required>
                <input class="form-control" name="contact" placeholder="Phone or email">
                <select class="form-select" name="source">
                    @foreach (\App\Models\Lead::SOURCES as $source)
                        <option value="{{ $source }}">{{ $source }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>

        <div class="upload-row">
            <input type="file" id="followupFile" accept=".xlsx,.xls,.csv">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadTemplate()">Download blank template</button>
        </div>
        <div id="importSummary" class="empty-state">No sheet imported yet — or just work with leads below directly.</div>
    </div> --}}

    <div id="followupCards">
        @forelse ($leads as $lead)
            <div class="fu-card">
                <div class="fu-head">
                    <div class="fu-name">
                        <input type="checkbox" class="followup-check" value="{{ $lead->id }}" onclick="updateEmailCount()" style="margin-right:4px;">
                        <button class="star-btn {{ $lead->highly_interested ?? false ? 'on' : 'off' }}" onclick="toggleInterested({{ $lead->id }})" title="Mark highly interested">⭐</button>
                        {{ $lead->name }}
                        @if ($lead->highly_interested ?? false)
                            <span class="highly-interested-tag">HOT LEAD</span>
                        @endif
                        <span style="font-size:11px;color:var(--muted);font-weight:400;">— {{ $lead->source }}</span>
                    </div>
                    <div class="d-flex gap-1 flex-wrap">
                        <span class="chip status-chip
                            {{ match($lead->status) {
                                'Registered' => 'teal',
                                'Visit' => 'gold',
                                'New' => 'gray',
                                default => 'amber',
                            } }}" data-cycle-url="{{ route('leads.cycle-status', $lead) }}">
                            {{ $lead->status }}
                        </span>
                        <button class="btn btn-outline-secondary btn-sm" onclick="logCall({{ $lead->id }})">📞 Log Call</button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="logActivity({{ $lead->id }}, 'visits')">📍 Log Visit</button>
                        <button class="btn btn-outline-secondary btn-sm" type="button" disabled title="WhatsApp integration coming soon">🟢 WhatsApp</button>
                    </div>
                </div>
                <div class="fu-body">
                    <div style="margin-bottom:12px;">{!! $lead->getCallSequenceHtml() !!}</div>

                    <div style="margin-bottom:12px;padding:12px;background:#FAFBFD;border-radius:8px;">
                        <label style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:5px;">ACTIVITY TIMELINE</label>
                        @php
                            $activities = collect();
                            // Add call logs
                            foreach($lead->callLogs as $call) {
                                $activities->push([
                                    'type' => 'call',
                                    'date' => $call->call_date ? \Carbon\Carbon::parse($call->call_date) : now(),
                                    'icon' => '📞',
                                    'title' => 'Call logged',
                                    'details' => $call->outcome . ($call->duration_seconds ? ' (' . $call->duration_seconds . 's)' : ''),
                                    'notes' => $call->notes
                                ]);
                            }
                            // Add communications
                            foreach($lead->communications as $comm) {
                                $activities->push([
                                    'type' => $comm->type,
                                    'date' => $comm->sent_at ? \Carbon\Carbon::parse($comm->sent_at) : now(),
                                    'icon' => $comm->type === 'whatsapp' ? '💬' : ($comm->type === 'email' ? '📧' : '📞'),
                                    'title' => ucfirst($comm->type) . ' ' . ucfirst($comm->direction),
                                    'details' => ucfirst($comm->status),
                                    'notes' => $comm->content
                                ]);
                            }
                            // Add documents
                            foreach($lead->documents as $doc) {
                                $activities->push([
                                    'type' => 'document',
                                    'date' => $doc->created_at ? \Carbon\Carbon::parse($doc->created_at) : now(),
                                    'icon' => '📎',
                                    'title' => 'Document uploaded',
                                    'details' => $doc->document_type . ' - ' . $doc->file_name,
                                    'notes' => $doc->notes
                                ]);
                            }
                            $activities = $activities->sortByDesc('date');
                        @endphp
                        @if ($activities->count() > 0)
                            <div style="display:flex;flex-direction:column;gap:8px;max-height:200px;overflow-y:auto;">
                                @foreach ($activities as $activity)
                                    <div style="padding:8px;background:white;border:1px solid var(--line);border-radius:6px;font-size:12px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;">
                                            <div style="font-weight:600;color:var(--primary);">
                                                {{ $activity['icon'] }} {{ $activity['title'] }}
                                            </div>
                                            <span style="font-size:10px;color:var(--muted);">{{ $activity['date']->format('M d, H:i') }}</span>
                                        </div>
                                        <div style="color:var(--muted);margin-top:2px;">{{ $activity['details'] }}</div>
                                        @if ($activity['notes'])
                                            <div style="margin-top:4px;font-size:11px;color:var(--muted);font-style:italic;">{{ $activity['notes'] }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="font-size:12px;color:var(--muted);">No activity recorded yet.</div>
                        @endif
                    </div>

                    
                    <div class="profile-grid">
                        <div>
                            <label>AGE</label>
                            <input type="number" value="{{ $lead->age ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'age', this.value)">
                        </div>
                        <div>
                            <label>CITY</label>
                            <input type="text" value="{{ $lead->city ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'city', this.value)">
                        </div>
                        <div>
                            <label>LAST QUALIFICATION</label>
                            <input type="text" value="{{ $lead->last_qualification ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'last_qualification', this.value)">
                        </div>
                        <div>
                            <label>CGPA / MARKS %</label>
                            <input type="text" value="{{ $lead->cgpa ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'cgpa', this.value)">
                        </div>
                        <div>
                            <label>ENGLISH TEST</label>
                            <select onchange="updateProfile({{ $lead->id }}, 'english_test', this.value)">
                                <option {{ ($lead->english_test ?? 'None') === 'None' ? 'selected' : '' }}>None</option>
                                <option {{ ($lead->english_test ?? 'None') === 'IELTS' ? 'selected' : '' }}>IELTS</option>
                                <option {{ ($lead->english_test ?? 'None') === 'TOEFL' ? 'selected' : '' }}>TOEFL</option>
                                <option {{ ($lead->english_test ?? 'None') === 'PTE' ? 'selected' : '' }}>PTE</option>
                                <option {{ ($lead->english_test ?? 'None') === 'Duolingo' ? 'selected' : '' }}>Duolingo</option>
                            </select>
                        </div>
                        <div>
                            <label>ENGLISH SCORE</label>
                            <input type="text" value="{{ $lead->english_score ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'english_score', this.value)">
                        </div>
                        <div>
                            <label>DESIRED PROGRAM</label>
                            <input type="text" value="{{ $lead->desired_program ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'desired_program', this.value)">
                        </div>
                        <div>
                            <label>BUDGET</label>
                            <input type="number" value="{{ $lead->budget ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'budget', this.value)">
                        </div>
                        <div>
                            <label>DESIRED COUNTRY</label>
                            <input type="text" value="{{ $lead->country ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'country', this.value)">
                        </div>
                        <div>
                            <label>CURRENCY</label>
                            <select onchange="updateProfile({{ $lead->id }}, 'currency', this.value)">
                                <option {{ ($lead->currency ?? 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                                <option {{ ($lead->currency ?? 'USD') === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option {{ ($lead->currency ?? 'USD') === 'GBP' ? 'selected' : '' }}>GBP</option>
                                <option {{ ($lead->currency ?? 'USD') === 'PKR' ? 'selected' : '' }}>PKR</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom:12px;padding:12px;background:#FAFBFD;border-radius:8px;">
                        <label style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:5px;">MATCHING OPPORTUNITIES</label>
                        @php $matchingOpps = $lead->getMatchingOpportunities(); @endphp
                        @if ($matchingOpps->count() > 0)
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                @foreach ($matchingOpps as $opp)
                                    <div style="padding:8px;background:white;border:1px solid var(--line);border-radius:6px;font-size:12px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;">
                                            <div style="font-weight:600;color:var(--primary);">{{ $opp->name }}</div>
                                            <span class="chip {{ $opp->match_score >= 70 ? 'teal' : ($opp->match_score >= 40 ? 'amber' : 'gray') }}" style="font-size:10px;">{{ $opp->match_score }}% Match</span>
                                        </div>
                                        <div style="color:var(--muted);margin-top:2px;">{{ $opp->country }} · {{ $opp->type }}</div>
                                        @if ($opp->cost)
                                            <div style="color:var(--gold-dark);margin-top:2px;">{{ $opp->currency ?? 'USD' }} {{ number_format($opp->cost, 2) }}</div>
                                        @endif
                                        <div style="margin-top:4px;font-size:10px;color:var(--muted);">
                                            {{ implode(', ', $opp->match_reasons) }}
                                        </div>
                                        <div style="margin-top:4px;">
                                            <a href="{{ $opp->official_link }}" target="_blank" style="color:var(--primary);font-size:11px;">View Details →</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="font-size:12px;color:var(--muted);">No matches — try widening budget or program/country above.</div>
                        @endif
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:5px;">REMARKS (VISIT, REGISTRATION, NOTES)</label>
                        <textarea onchange="updateProfile({{ $lead->id }}, 'remarks', this.value)" placeholder="Visit notes, registration details..." style="width:100%;min-height:56px;padding:8px 10px;border:1px solid var(--line);border-radius:6px;font-size:12.5px;">{{ $lead->remarks ?? '' }}</textarea>
                    </div>

                    <div style="margin-bottom:12px;padding:12px;background:#FAFBFD;border-radius:8px;">
                        <label style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:5px;">DOCUMENTS</label>
                        <div class="document-upload">
                            <select id="docType-{{ $lead->id }}" style="padding:6px 10px;border:1px solid var(--line);border-radius:6px;font-size:12px;margin-right:8px;">
                                @foreach (\App\Models\LeadDocument::DOCUMENT_TYPES as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            <input type="file" id="docFile-{{ $lead->id }}" style="display:none;" onchange="uploadDocument({{ $lead->id }})">
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('docFile-{{ $lead->id }}').click()">📎 Upload Document</button>
                        </div>
                        <div id="docProgress-{{ $lead->id }}" class="doc-progress" style="display:none;">
                            <span class="doc-spinner"></span>
                            <span class="doc-progress-text">Uploading…</span>
                        </div>
                        <div id="docList-{{ $lead->id }}" class="document-list">
                            @foreach ($lead->documents as $doc)
                                <div class="document-item">
                                    <span class="doc-type">{{ $doc->document_type }}</span>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="doc-name">{{ $doc->file_name }}</a>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteDocument({{ $doc->id }})">✕</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:14px;padding-top:14px;border-top:1px solid var(--line);flex-wrap:wrap;">
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <button class="chip {{ $lead->contract_signed ?? false ? 'teal' : 'gray' }}" onclick="toggleContract({{ $lead->id }})">
                                {{ $lead->contract_signed ?? false ? '✓ Contract Signed ' . ($lead->contract_date ?? '') : '☐ Contract Signed' }}
                            </button>
                            <button class="chip {{ $lead->payment_done ?? false ? 'teal' : 'gray' }}" onclick="togglePayment({{ $lead->id }})">
                                {{ $lead->payment_done ?? false ? '✓ Initial Payment ' . ($lead->payment_date ?? '') : '☐ Initial Payment' }}
                            </button>
                        </div>
                        @php $readyToConvert = ($lead->contract_signed ?? false) && ($lead->payment_done ?? false); @endphp
                        <button class="btn {{ $readyToConvert ? '' : 'btn-outline-secondary' }}" {{ $readyToConvert ? '' : 'disabled style="opacity:0.5;cursor:not-allowed;"' }}
                            onclick="{{ $readyToConvert ? "convertToStudent({$lead->id})" : '' }}">
                            {{ $readyToConvert ? 'Convert to Registered Student →' : 'Awaiting contract + payment' }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">No one in Follow-ups yet — move a hot lead here from My Leads.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    const FOLLOWUP_GAP_DAYS = 3;
    const TODAY = '{{ now()->format('Y-m-d') }}';

    function daysBetween(a, b) {
        return Math.round((new Date(b + 'T00:00:00') - new Date(a + 'T00:00:00')) / 86400000);
    }

    function toggleInterested(leadId) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { highly_interested: true },
            success: function () {
                location.reload();
            }
        });
    }

    function logCall(leadId) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { log_call: true },
            success: function () {
                location.reload();
            }
        });
    }

    function logActivity(leadId, kind) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { log_activity: kind },
            success: function () {
                location.reload();
            }
        });
    }

    function updateProfile(leadId, field, value) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { [field]: value },
            success: function () {
                // Profile saved
            }
        });
    }

    function toggleContract(leadId) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { toggle_contract: true },
            success: function () {
                location.reload();
            }
        });
    }

    function togglePayment(leadId) {
        $.ajax({
            url: '{{ route('leads.update-profile', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'PATCH',
            data: { toggle_payment: true },
            success: function () {
                location.reload();
            }
        });
    }

    function convertToStudent(leadId) {
        $.post('{{ route('leads.convert', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId), {}, function () {
            location.reload();
        });
    }

    function uploadDocument(leadId) {
        const fileInput = document.getElementById('docFile-' + leadId);
        const docType = document.getElementById('docType-' + leadId).value;
        const file = fileInput.files[0];

        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('document_type', docType);
        formData.append('_token', '{{ csrf_token() }}');

        const $progress = $('#docProgress-' + leadId);
        $progress.removeClass('is-error').find('.doc-progress-text').text('Uploading…');
        $progress.show();

        $.ajax({
            url: '{{ route('leads.documents.store', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', leadId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $progress.hide();

                const docList = document.getElementById('docList-' + leadId);
                const docItem = document.createElement('div');
                docItem.className = 'document-item';
                docItem.innerHTML = `
                    <span class="doc-type">${docType}</span>
                    <a href="${data.url}" target="_blank" class="doc-name">${file.name}</a>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteDocument(${data.document.id})">✕</button>
                `;
                docList.appendChild(docItem);
                fileInput.value = '';
            },
            error: function () {
                $progress.addClass('is-error').find('.doc-progress-text').text('Upload failed');
                setTimeout(function () { $progress.hide(); }, 1500);
                alert('Failed to upload document');
            }
        });
    }

    function deleteDocument(docId) {
        if (!confirm('Are you sure you want to delete this document?')) return;

        $.ajax({
            url: '{{ route('documents.destroy', ['document' => '__DOC_ID__']) }}'.replace('__DOC_ID__', docId),
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                location.reload();
            },
            error: function () {
                alert('Failed to delete document');
            }
        });
    }

    function updateEmailCount() {
        $('#emailCount').text($('.followup-check:checked').length + ' selected');
    }

    function downloadTemplate() {
        const csv = "Student Name,Action,Due Date,Notes\nJohn Doe,Call,2026-07-15,Confirm documents received\n";
        const blob = new Blob([csv], {type: "text/csv"});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = "followup_template.csv";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(() => URL.revokeObjectURL(url), 1000);
    }

    $(function () {
        $(document).on('click', '.status-chip', function () {
            const $chip = $(this);
            $.ajax({
                url: $chip.data('cycle-url'),
                method: 'PATCH',
                success: function (data) {
                    $chip.text(data.status);
                    $chip.removeClass('teal gold gray amber');
                    const map = { Registered: 'teal', Visit: 'gold', New: 'gray' };
                    $chip.addClass(map[data.status] || 'amber');
                }
            });
        });

        $('#emailSendBtn').on('click', function () {
            const names = $('.followup-check:checked').map(function () {
                return $(this).closest('.fu-card').find('.fu-name').text().trim();
            }).get();
            if (names.length === 0) return;
            $('#emailConfirm').show().text('✓ Emailed ' + names.length + ': ' + names.join(', ') + ' — (demo only; connect an email provider like SendGrid/SES to send for real)');
        });
    });
</script>
@endpush
