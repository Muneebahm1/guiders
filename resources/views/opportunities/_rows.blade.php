@forelse ($opportunities as $opportunity)
    @php $days = $opportunity->daysLeft(); @endphp
    <div class="board-row">
        <div data-label="Program">
            <div class="board-prog">{{ $opportunity->name }}</div>
            <div class="board-meta">
                <span class="cost-tag">{{ $opportunity->currency }} {{ number_format($opportunity->cost, 0) }}</span>
                @if (!auth()->user()->isCounselor())
                    @if ($opportunity->official_link)
                        <a href="{{ $opportunity->official_link }}" target="_blank" rel="noopener" class="board-link">Official &#8599;</a>
                    @endif
                    @if ($opportunity->application_link)
                        <a href="{{ $opportunity->application_link }}" target="_blank" rel="noopener" class="board-link">Apply &#8599;</a>
                    @endif
                @endif
                <span class="board-link download-package"
                      data-name="{{ $opportunity->name }}"
                      data-country="{{ $opportunity->country }}"
                      data-type="{{ $opportunity->type === 'study_abroad' ? 'Study Abroad' : 'Research Publication' }}"
                      data-deadline="{{ $opportunity->deadline->toDateString() }}"
                      data-requirements="{{ $opportunity->requirements }}"
                      data-cost="{{ $opportunity->currency }} {{ number_format($opportunity->cost, 2) }}"
                      data-guidance="{{ $opportunity->guidance }}"
                      data-official="{{ $opportunity->official_link }}"
                      data-application="{{ $opportunity->application_link }}">
                    Download Package &#11015;
                </span>
            </div>
            @if (auth()->user()->isAdmin() || auth()->user()->isProcessingTeam())
                <div class="sessions-section" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--line);">
                    <div style="font-size: 11px; color: var(--muted); margin-bottom: 4px;">Sessions:</div>
                    @if ($opportunity->sessions->isNotEmpty())
                        @foreach ($opportunity->sessions as $session)
                            <div class="session-item" style="font-size: 11px; padding: 2px 0;">
                                <span>{{ $session->session_name }}</span>
                                @if ($session->university) <span style="color: var(--muted);">· {{ $session->university }}</span> @endif
                                @if ($session->country) <span style="color: var(--muted);">· {{ $session->country }}</span> @endif
                                @if ($session->deadline) <span style="color: var(--muted);">· {{ $session->deadline->toDateString() }}</span> @endif
                                <form method="POST" action="{{ route('opportunities.sessions.destroy', $session) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 10px; margin-left: 4px;">✕</button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <div style="font-size: 11px; color: var(--muted);">No sessions added</div>
                    @endif
                    <button type="button" class="btn btn-outline-secondary btn-sm" style="font-size: 10px; padding: 2px 6px; margin-top: 4px;" data-bs-toggle="collapse" data-bs-target="#sessionForm-{{ $opportunity->id }}">+ Add Session</button>
                    <div class="collapse" id="sessionForm-{{ $opportunity->id }}" style="margin-top: 4px;">
                        <form method="POST" action="{{ route('opportunities.sessions.store', $opportunity) }}" style="display: flex; gap: 4px; flex-wrap: wrap;">
                            @csrf
                            <input type="text" name="session_name" placeholder="Session name (e.g., Fall 2026)" style="flex: 1; min-width: 120px; padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; font-size: 11px;" required>
                            <input type="text" name="university" placeholder="University" style="flex: 1; min-width: 100px; padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; font-size: 11px;">
                            <input type="text" name="country" placeholder="Country" style="flex: 1; min-width: 80px; padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; font-size: 11px;">
                            <input type="date" name="deadline" style="flex: 1; min-width: 100px; padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; font-size: 11px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 10px; padding: 4px 8px;">Add</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <div class="board-req" data-label="Requirements">{{ $opportunity->requirements ?: '—' }}</div>
        <div data-label="Type">
            <span class="type-tag {{ $opportunity->type === 'study_abroad' ? 'sa' : 'rp' }}">
                {{ $opportunity->type === 'study_abroad' ? 'STUDY' : 'PAPER' }}
            </span>
        </div>
        <div class="board-country" data-label="Country">{{ $opportunity->country }}</div>
        <div class="flap" data-label="Deadline">{{ $opportunity->deadline->toDateString() }}</div>
        @if (!auth()->user()->isCounselor())
            <div class="countdown {{ $days < 0 ? 'late' : ($days <= 14 ? 'soon' : 'ok') }}" data-label="Countdown">
                {{ $days < 0 ? 'T+'.abs($days).'D LATE' : ($days === 0 ? 'TODAY' : 'T-'.$days.'D') }}
            </div>
        @endif
    </div>
    @if (auth()->user()->isAdmin())
        <div class="board-admin-bar">
            <button type="button" class="btn btn-outline-secondary btn-sm btn-opp-edit"
                    data-update-url="{{ route('opportunities.update', $opportunity) }}"
                    data-name="{{ $opportunity->name }}"
                    data-country="{{ $opportunity->country }}"
                    data-type="{{ $opportunity->type }}"
                    data-deadline="{{ $opportunity->deadline->toDateString() }}"
                    data-official="{{ $opportunity->official_link }}"
                    data-application="{{ $opportunity->application_link }}"
                    data-cost="{{ $opportunity->cost }}"
                    data-currency="{{ $opportunity->currency }}"
                    data-requirements="{{ $opportunity->requirements }}"
                    data-guidance="{{ $opportunity->guidance }}">Edit</button>
            <button type="button" class="btn btn-outline-danger btn-sm btn-opp-delete"
                    data-delete-url="{{ route('opportunities.destroy', $opportunity) }}"
                    data-name="{{ $opportunity->name }}">Delete</button>
        </div>
    @endif
@empty
    <div class="board-empty">No opportunities match your search.</div>
@endforelse
