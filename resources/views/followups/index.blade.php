@extends('layouts.app')

@section('title', 'Follow-ups')
@section('subtitle', "Each lead's profile, suggestion, and remarks — saved as you type")

@section('content')
    @forelse ($leads as $lead)
        @php $reminders = $followUpsByLead->get($lead->id, collect()); @endphp
        <div class="fu-card" data-lead-id="{{ $lead->id }}">
            <div class="fu-head">
                <div class="fu-name">
                    {{ $lead->name }}
                    <span class="sub-cell">{{ $lead->contact ?: 'No contact on file' }} &middot; {{ $lead->source }}</span>
                    <button class="btn btn-outline-secondary btn-sm ms-2" type="button" disabled title="WhatsApp integration coming soon">🟢 WhatsApp</button>
                </div>
                <span class="chip status-chip
                    {{ match($lead->status) {
                        'Registered' => 'teal',
                        'Visit' => 'gold',
                        'New' => 'gray',
                        default => 'amber',
                    } }}" data-cycle-url="{{ route('leads.cycle-status', $lead) }}">
                    {{ $lead->status }}
                </span>
            </div>
            <div class="fu-body">
                <div class="profile-grid">
                    <input class="form-control profile-field" name="desired_program" placeholder="Desired program" value="{{ $lead->desired_program }}">
                    <input class="form-control profile-field" name="country" placeholder="Desired country" value="{{ $lead->country }}">
                    <input class="form-control profile-field" type="number" step="0.01" name="budget" placeholder="Budget (USD)" value="{{ $lead->budget }}">
                    <input class="form-control profile-field" name="last_qualification" placeholder="Last qualification" value="{{ $lead->last_qualification }}">
                    <input class="form-control profile-field" name="cgpa" placeholder="CGPA" value="{{ $lead->cgpa }}">
                    <input class="form-control profile-field" type="number" name="age" placeholder="Age" value="{{ $lead->age }}">
                    <input class="form-control profile-field" name="city" placeholder="City" value="{{ $lead->city }}">
                </div>
                <div class="profile-grid profile-grid-notes">
                    <textarea class="form-control profile-field" name="suggestion" placeholder="Suggestion — what you recommended" rows="2">{{ $lead->suggestion }}</textarea>
                    <textarea class="form-control profile-field" name="remarks" placeholder="Remarks — how it went" rows="2">{{ $lead->remarks }}</textarea>
                </div>
                <div class="fu-save-status" data-for="{{ $lead->id }}"></div>

                <div class="fu-reminders">
                    @forelse ($reminders as $reminder)
                        <div class="fu-reminder-row {{ $reminder->isOverdue() ? 'overdue-row' : ($reminder->isDueSoon() ? 'due-soon-row' : '') }}">
                            <span class="chip action-chip {{ $reminder->action === 'Visit' ? 'gold' : 'amber' }}"
                                  data-url="{{ route('followups.cycle-action', $reminder) }}">
                                {{ $reminder->action }}
                            </span>
                            <span class="date-cell due-date">{{ $reminder->due_date->toDateString() }}{{ $reminder->isOverdue() ? ' (overdue)' : '' }}</span>
                        </div>
                    @empty
                        <div class="sub-cell">No reminders scheduled yet.</div>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('followups.store') }}" class="fu-add-reminder">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <input type="hidden" name="student_name" value="{{ $lead->name }}">
                    <select class="form-select form-select-sm" name="action">
                        <option value="Call">Call</option>
                        <option value="Visit">Visit</option>
                    </select>
                    <input class="form-control form-control-sm" type="date" name="due_date" required>
                    <button class="btn btn-outline-primary btn-sm" type="submit">+ Add reminder</button>
                </form>
            </div>
        </div>
    @empty
        <div class="panel"><div class="empty-state">No leads yet — add one from My Leads to start tracking follow-ups.</div></div>
    @endforelse

    @if ($unlinked->isNotEmpty())
        <div class="panel">
            <div class="panel-head"><div><h2>Other reminders</h2><p>Not linked to a lead (e.g. imported from a sheet)</p></div></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Student</th><th>Action</th><th>Due</th></tr></thead>
                    <tbody>
                        @foreach ($unlinked as $reminder)
                            <tr class="{{ $reminder->isOverdue() ? 'overdue-row' : ($reminder->isDueSoon() ? 'due-soon-row' : '') }}">
                                <td class="name-cell">{{ $reminder->student_name }}</td>
                                <td>
                                    <span class="chip action-chip {{ $reminder->action === 'Visit' ? 'gold' : 'amber' }}"
                                          data-url="{{ route('followups.cycle-action', $reminder) }}">
                                        {{ $reminder->action }}
                                    </span>
                                </td>
                                <td class="date-cell due-date">{{ $reminder->due_date->toDateString() }}{{ $reminder->isOverdue() ? ' (overdue)' : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="modal fade" id="visitDateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule visit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label small">Visit date</label>
                    <input type="date" id="visitDateInput" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="visitDateConfirm" class="btn btn-primary btn-sm">Confirm visit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Inline auto-save: any change to a profile/suggestion/remarks field saves the whole card
        let saveTimers = {};
        $(document).on('input change', '.profile-field', function () {
            const $card = $(this).closest('.fu-card');
            const leadId = $card.data('lead-id');
            const $status = $card.find('.fu-save-status');

            clearTimeout(saveTimers[leadId]);
            saveTimers[leadId] = setTimeout(function () {
                const payload = {};
                $card.find('.profile-field').each(function () {
                    payload[$(this).attr('name')] = $(this).val();
                });

                $status.text('Saving…');
                $.ajax({
                    url: '/leads/' + leadId + '/profile',
                    method: 'PATCH',
                    dataType: 'json',
                    headers: { Accept: 'application/json' },
                    data: payload,
                    success: function () {
                        $status.text('Saved ✓');
                        setTimeout(() => $status.text(''), 1500);
                    },
                    error: function () {
                        $status.text('Could not save — check required fields.');
                    }
                });
            }, 600);
        });

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

        let $pendingChip = null;
        const visitModal = new bootstrap.Modal(document.getElementById('visitDateModal'));

        $(document).on('click', '.action-chip', function () {
            const $chip = $(this);
            const current = $chip.text().trim();
            const next = current === 'Call' ? 'Visit' : 'Call';

            if (next === 'Visit') {
                $pendingChip = $chip;
                $('#visitDateInput').val($chip.closest('.fu-reminder-row, tr').find('.due-date').text().trim().split(' ')[0]);
                visitModal.show();
                return;
            }

            $.ajax({ url: $chip.data('url'), method: 'PATCH', success: function (data) {
                applyAction($chip, data);
            }});
        });

        $('#visitDateConfirm').on('click', function () {
            const date = $('#visitDateInput').val();
            if (!date || !$pendingChip) return;

            $.ajax({
                url: $pendingChip.data('url'),
                method: 'PATCH',
                data: { due_date: date },
                success: function (data) {
                    applyAction($pendingChip, data);
                    visitModal.hide();
                    $pendingChip = null;
                }
            });
        });

        function applyAction($chip, data) {
            $chip.text(data.action);
            $chip.removeClass('amber gold').addClass(data.action === 'Visit' ? 'gold' : 'amber');
            $chip.closest('.fu-reminder-row, tr').find('.due-date').text(data.due_date);
        }
    });
</script>
@endpush
