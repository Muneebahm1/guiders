@extends('layouts.app')

@section('title', 'My Leads')
@section('subtitle', "Prospective students you're following up with")

@section('content')
    @php
        $overdueLeads = $leads->filter(function($lead) {
            $log = $lead->getCallLogArray();
            if (empty($log)) return true; // First call is always due
            $lastCall = end($log);
            $daysSince = (strtotime(now()->format('Y-m-d')) - strtotime($lastCall)) / 86400;
            return $daysSince >= 3;
        });
    @endphp

    @if ($overdueLeads->count() > 0)
        <div id="reminderPopup" class="alert alert-warning alert-dismissible fade show" style="margin-bottom: 20px;">
            <strong>⏰ Follow-up Reminder!</strong> You have {{ $overdueLeads->count() }} lead(s) with overdue calls:
            <ul style="margin: 8px 0 0 20px;">
                @foreach ($overdueLeads as $lead)
                    <li>{{ $lead->name }} {{ $lead->contact ? '(' . $lead->contact . ')' : '' }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div><h2>Bulk Import (optional)</h2><p>Upload a legacy tracking sheet — rows match to existing leads by name, or create new ones</p></div>
        </div>
        <form method="POST" action="{{ route('leads.upload') }}" enctype="multipart/form-data" class="upload-row">
            @csrf
            <input type="file" name="file" accept=".csv,.txt" class="form-control form-control-sm" style="max-width:280px" required>
            <button class="btn btn-outline-primary btn-sm" type="submit">Upload sheet</button>
            <a href="{{ route('leads.template') }}" class="btn btn-outline-secondary btn-sm">Download blank template</a>
        </form>
    </div>

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
            <div><h2>My Leads</h2><p>Prospective students you're following up with</p></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#emailComposer">
                    📧 Email Selected Leads
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addLeadForm">
                    + Add Lead
                </button>
            </div>
        </div>

        <div class="collapse" id="emailComposer">
            <div class="p-3 border-bottom" style="background:#F0FBF7">
                <div class="small text-muted mb-2">Check the leads you want to email below, then compose and send. Use <code>{name}</code> in the body to personalize per lead. Only leads with a valid email address on file will receive it.</div>
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
                <input class="form-control" name="contact" placeholder="Contact number">
                <input class="form-control" name="email" type="email" placeholder="Email (optional)">
                <select class="form-select" name="source">
                    @foreach (\App\Models\Lead::SOURCES as $source)
                        <option value="{{ $source }}">{{ $source }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>

        <div class="filter-row">
            <input type="text" id="searchInput" placeholder="Search by name or contact..." style="flex:1;padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
            <select id="statusFilter" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
                <option value="">All Statuses</option>
                <option value="New">New</option>
                <option value="Call">Call</option>
                <option value="Visit">Visit</option>
                <option value="Registered">Registered</option>
            </select>
            <select id="sourceFilter" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
                <option value="">All Sources</option>
                @foreach (\App\Models\Lead::SOURCES as $source)
                    <option value="{{ $source }}">{{ $source }}</option>
                @endforeach
            </select>
            <select id="interestFilter" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
                <option value="">All Leads</option>
                <option value="hot">Hot Leads Only</option>
                <option value="normal">Normal Leads Only</option>
            </select>
            <select id="engagementFilter" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
                <option value="">All Engagement</option>
                <option value="high">High Engagement</option>
                <option value="medium">Medium Engagement</option>
                <option value="low">Low Engagement</option>
            </select>
            <input type="date" id="dateFrom" placeholder="From" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
            <input type="date" id="dateTo" placeholder="To" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;">
            <input type="number" id="budgetMin" placeholder="Min Budget" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;width:100px;">
            <input type="number" id="budgetMax" placeholder="Max Budget" style="padding:8px 12px;border:1px solid var(--line);border-radius:6px;font-size:13px;width:100px;">
            <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">Reset</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="sortByEngagement()">Sort by Engagement</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="exportLeads()">Export CSV</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="bulkMoveToFollowUp()">Bulk Move to Follow-up</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="bulkUpdateStatus()">Bulk Update Status</button>
            <button class="btn btn-outline-danger btn-sm" onclick="bulkDelete()">Bulk Delete</button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="check-col"><input type="checkbox" id="checkAll"></th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Engagement</th>
                        <th>Call Follow-ups</th>
                        <th>Other Activity</th>
                        <th>Log</th>
                        <th>Profile</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr data-lead-id="{{ $lead->id }}" data-name="{{ $lead->name }}">
                            <td><input type="checkbox" class="lead-check" value="{{ $lead->id }}"></td>
                            <td class="name-cell">
                                <button class="star-btn {{ $lead->highly_interested ?? false ? 'on' : 'off' }}" onclick="toggleInterested({{ $lead->id }})" title="Mark highly interested">⭐</button>
                                {{ $lead->name }}
                                @if ($lead->highly_interested ?? false)
                                    <span class="highly-interested-tag">HOT LEAD</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $lead->contact ?: '—' }}</div>
                                @if ($lead->email)
                                    <div class="text-muted small">{{ $lead->email }}</div>
                                @endif
                            </td>
                            <td>{{ $lead->source }}</td>
                            <td>
                                <span class="chip status-chip
                                    {{ match($lead->status) {
                                        'Registered' => 'teal',
                                        'Visit' => 'gold',
                                        'New' => 'gray',
                                        default => 'amber',
                                    } }}" data-cycle-url="{{ route('leads.cycle-status', $lead) }}">
                                    {{ $lead->status }}
                                </span>
                            </td>
                            <td>
                                @php $score = $lead->getEngagementScore(); $level = $lead->getEngagementLevel(); @endphp
                                <span class="chip {{ $level === 'High' ? 'teal' : ($level === 'Medium' ? 'amber' : 'gray') }}" style="font-size:10px;">{{ $score }}% {{ $level }}</span>
                            </td>
                            <td>{!! $lead->getCallSequenceHtml() !!}</td>
                            <td class="date-cell">{{ ($lead->messages ?? 0) }} msgs · {{ ($lead->visits ?? 0) }} visits</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="logCall({{ $lead->id }})">📞 Log Call</button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="logActivity({{ $lead->id }}, 'messages')">💬 Message</button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="logActivity({{ $lead->id }}, 'visits')">📍 Visit</button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" disabled title="WhatsApp integration coming soon">🟢 WhatsApp</button>
                                </div>
                            </td>
                            <td><button class="btn btn-outline-secondary btn-sm" onclick="toggleProfile({{ $lead->id }})">👤 Profile</button></td>
                            <td>
                                @if ($lead->status !== 'Registered')
                                    <button class="btn btn-outline-secondary btn-sm convert-btn" data-convert-url="{{ route('leads.move-to-followup', $lead) }}">
                                        Move to Follow-up →
                                    </button>
                                @else
                                    <span class="sub-cell">Registered</span>
                                @endif
                            </td>
                        </tr>
                        <tr id="profile-row-{{ $lead->id }}" class="profile-row" style="display:none;">
                            <td colspan="11">
                                <div class="profile-grid">
                                    <div><label>Contact Number</label><input type="text" value="{{ $lead->contact ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'contact', this.value)"></div>
                                    <div><label>Email</label><input type="email" value="{{ $lead->email ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'email', this.value)"></div>
                                    <div><label>Age</label><input type="number" value="{{ $lead->age ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'age', this.value)"></div>
                                    <div><label>City</label><input type="text" value="{{ $lead->city ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'city', this.value)"></div>
                                    <div><label>Desired Program</label><input type="text" value="{{ $lead->desired_program ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'desired_program', this.value)"></div>
                                    <div><label>Desired Country</label><input type="text" value="{{ $lead->country ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'country', this.value)"></div>
                                    <div><label>Last Qualification</label><input type="text" value="{{ $lead->last_qualification ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'last_qualification', this.value)"></div>
                                    <div><label>CGPA / Marks %</label><input type="text" value="{{ $lead->cgpa ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'cgpa', this.value)"></div>
                                    <div><label>Budget</label><input type="number" value="{{ $lead->budget ?? '' }}" onchange="updateProfile({{ $lead->id }}, 'budget', this.value)"></div>
                                </div>
                                <div class="profile-remarks">
                                    <label>Remarks</label>
                                    <textarea onchange="updateProfile({{ $lead->id }}, 'remarks', this.value)" placeholder="Visit notes, registration details...">{{ $lead->remarks ?? '' }}</textarea>
                                </div>
                                <div class="profile-documents">
                                    <label>Documents</label>
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
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="empty-state">No leads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const FOLLOWUP_GAP_DAYS = 3;
    const TODAY = '{{ now()->format('Y-m-d') }}';

    function daysBetween(a, b) {
        return Math.round((new Date(b + 'T00:00:00') - new Date(a + 'T00:00:00')) / 86400000);
    }

    function callSequenceHtml(lead) {
        const log = lead.call_log ? JSON.parse(lead.call_log) : [];
        let html = '<div style="display:flex;gap:6px;flex-wrap:wrap;">';
        for (let i = 0; i < 3; i++) {
            const label = `Call ${i + 1}`;
            if (log[i]) {
                html += `<span class="chip teal static">${label} ✓ ${log[i]}</span>`;
            } else if (i === 0 || log[i - 1]) {
                const sinceDate = i === 0 ? null : log[i - 1];
                const overdue = sinceDate ? daysBetween(sinceDate, TODAY) >= FOLLOWUP_GAP_DAYS : (i === 0);
                html += overdue
                    ? `<span class="chip amber static">⏰ ${label} Due</span>`
                    : `<span class="chip gray static">${label} — in ${FOLLOWUP_GAP_DAYS - daysBetween(sinceDate, TODAY)}d</span>`;
            } else {
                html += `<span class="chip gray static">${label} — not yet</span>`;
            }
        }
        html += '</div>';
        return html;
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

    function toggleProfile(leadId) {
        const row = document.getElementById('profile-row-' + leadId);
        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
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

    function resetFilters() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#sourceFilter').val('');
        $('#interestFilter').val('');
        $('#engagementFilter').val('');
        $('#dateFrom').val('');
        $('#dateTo').val('');
        $('#budgetMin').val('');
        $('#budgetMax').val('');
        filterLeads();
    }

    function filterLeads() {
        const search = $('#searchInput').val().toLowerCase();
        const status = $('#statusFilter').val();
        const source = $('#sourceFilter').val();
        const interest = $('#interestFilter').val();
        const engagement = $('#engagementFilter').val();
        const dateFrom = $('#dateFrom').val();
        const dateTo = $('#dateTo').val();
        const budgetMin = $('#budgetMin').val();
        const budgetMax = $('#budgetMax').val();

        $('tbody tr[data-lead-id]').each(function() {
            const $row = $(this);
            const name = $row.data('name').toLowerCase();
            const contact = $row.find('td:nth-child(3)').text().toLowerCase();
            const rowStatus = $row.find('.status-chip').text();
            const rowSource = $row.find('td:nth-child(4)').text();
            const isHot = $row.find('.highly-interested-tag').length > 0;
            const rowEngagement = $row.find('td:nth-child(6)').text().toLowerCase();
            const rowBudget = parseFloat($row.find('td:nth-child(7)').text()) || 0;

            let show = true;

            if (search && !name.includes(search) && !contact.includes(search)) {
                show = false;
            }

            if (status && rowStatus !== status) {
                show = false;
            }

            if (source && rowSource !== source) {
                show = false;
            }

            if (interest === 'hot' && !isHot) {
                show = false;
            }

            if (interest === 'normal' && isHot) {
                show = false;
            }

            if (engagement === 'high' && !rowEngagement.includes('high')) {
                show = false;
            }

            if (engagement === 'medium' && !rowEngagement.includes('medium')) {
                show = false;
            }

            if (engagement === 'low' && !rowEngagement.includes('low')) {
                show = false;
            }

            if (budgetMin && rowBudget < parseFloat(budgetMin)) {
                show = false;
            }

            if (budgetMax && rowBudget > parseFloat(budgetMax)) {
                show = false;
            }

            $row.toggle(show);
        });
    }

    function sortByEngagement() {
        const rows = $('tbody tr[data-lead-id]').get();
        rows.sort(function(a, b) {
            const scoreA = parseInt($(a).find('td:nth-child(6)').text());
            const scoreB = parseInt($(b).find('td:nth-child(6)').text());
            return scoreB - scoreA;
        });
        $.each(rows, function(index, row) {
            $('tbody').append(row);
        });
    }

    function exportLeads() {
        const rows = $('tbody tr[data-lead-id]:visible');
        if (rows.length === 0) {
            alert('No leads to export');
            return;
        }

        let csv = 'Name,Contact,Source,Status,Engagement,Messages,Visits\n';
        rows.each(function() {
            const $row = $(this);
            const name = $row.data('name');
            const contact = $row.find('td:nth-child(3)').text().trim();
            const source = $row.find('td:nth-child(4)').text().trim();
            const status = $row.find('.status-chip').text().trim();
            const engagement = $row.find('td:nth-child(6)').text().trim();
            const activity = $row.find('td:nth-child(8)').text().trim();
            csv += `"${name}","${contact}","${source}","${status}","${engagement}","${activity}"\n`;
        });

        const blob = new Blob([csv], {type: 'text/csv'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'leads_export_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(() => URL.revokeObjectURL(url), 1000);
    }

    function getSelectedLeadIds() {
        return $('.lead-check:checked').map(function() {
            return $(this).val();
        }).get();
    }

    function bulkMoveToFollowUp() {
        const ids = getSelectedLeadIds();
        if (ids.length === 0) {
            alert('Please select leads first');
            return;
        }

        if (!confirm(`Move ${ids.length} lead(s) to follow-up?`)) return;

        let completed = 0;
        ids.forEach(id => {
            $.post('{{ route('leads.move-to-followup', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', id), {}, function() {
                completed++;
                if (completed === ids.length) {
                    location.reload();
                }
            });
        });
    }

    function bulkUpdateStatus() {
        const ids = getSelectedLeadIds();
        if (ids.length === 0) {
            alert('Please select leads first');
            return;
        }

        const newStatus = prompt('Enter new status (New, Call, Visit, Registered):');
        if (!newStatus) return;

        let completed = 0;
        ids.forEach(id => {
            $.ajax({
                url: '{{ route('leads.cycle-status', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', id),
                method: 'PATCH',
                success: function() {
                    completed++;
                    if (completed === ids.length) {
                        location.reload();
                    }
                }
            });
        });
    }

    function bulkDelete() {
        const ids = getSelectedLeadIds();
        if (ids.length === 0) {
            alert('Please select leads first');
            return;
        }

        if (!confirm(`Delete ${ids.length} lead(s)? This cannot be undone.`)) return;

        let completed = 0;
        ids.forEach(id => {
            $.ajax({
                url: '/leads/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    completed++;
                    if (completed === ids.length) {
                        location.reload();
                    }
                }
            });
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

    $(function () {
        $('#searchInput').on('input', filterLeads);
        $('#statusFilter').on('change', filterLeads);
        $('#sourceFilter').on('change', filterLeads);
        $('#interestFilter').on('change', filterLeads);
        $('#engagementFilter').on('change', filterLeads);
        $('#dateFrom').on('change', filterLeads);
        $('#dateTo').on('change', filterLeads);
        $('#budgetMin').on('input', filterLeads);
        $('#budgetMax').on('input', filterLeads);

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
                    if (data.status === 'Registered') {
                        $chip.closest('tr').find('.convert-btn').replaceWith('<span class="sub-cell">Registered</span>');
                    }
                }
            });
        });

        $(document).on('click', '.convert-btn', function () {
            const $btn = $(this);
            $.post($btn.data('convert-url'), {}, function (data) {
                // Reload page to remove lead from leads list (moved to follow-ups)
                location.reload();
            });
        });

        $('#checkAll').on('click', function () {
            $('.lead-check').prop('checked', $(this).prop('checked'));
            updateSelectedCount();
        });
        $(document).on('click', '.lead-check', updateSelectedCount);

        function updateSelectedCount() {
            $('#emailCount').text($('.lead-check:checked').length + ' selected');
        }

        $('#emailSendBtn').on('click', function () {
            const ids = getSelectedLeadIds();
            if (ids.length === 0) { alert('Please select leads first'); return; }

            const subject = $('#emailSubject').val().trim();
            const body = $('#emailBody').val().trim();
            if (!subject || !body) { alert('Please fill in subject and message body'); return; }
            if (!confirm(`Send this email to ${ids.length} lead(s)?`)) return;

            const $btn = $(this);
            $btn.prop('disabled', true);
            $('#emailConfirm').hide();

            let remaining = ids.length, sent = 0, failed = 0, failedNames = [];
            ids.forEach(id => {
                $.ajax({
                    url: '{{ route('leads.email', ['lead' => '__LEAD_ID__']) }}'.replace('__LEAD_ID__', id),
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', subject: subject, body: body },
                    success: function () { sent++; },
                    error: function () {
                        failed++;
                        failedNames.push($('.lead-check[value="' + id + '"]').closest('tr').data('name'));
                    },
                    complete: function () {
                        remaining--;
                        if (remaining === 0) {
                            $btn.prop('disabled', false);
                            let msg = '✓ Sent to ' + sent + ' lead(s).';
                            if (failed > 0) { msg += ' Failed for ' + failed + ': ' + failedNames.join(', ') + ' (no valid email on file, or send error).'; }
                            $('#emailConfirm').show().text(msg);
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
