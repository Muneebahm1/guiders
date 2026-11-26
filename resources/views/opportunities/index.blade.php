@extends('layouts.app')

@section('title', 'Opportunities')
@section('subtitle', 'Study abroad programs and research-publication journals, sorted by nearest deadline')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Deadline Board</h2><p>Search by name, country, or requirement</p></div>
            <div class="search-wrap">
                <span class="search-icon">&#8981;</span>
                <input id="oppSearch" type="text" class="form-control form-control-sm"
                       placeholder="Search e.g. MBA, Germany..." value="{{ $term }}">
            </div>
            @if (auth()->user()->isAdmin() || auth()->user()->isProcessingTeam())
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#oppForm">
                    + Add Opportunity
                </button>
            @endif
        </div>

        @if (auth()->user()->isAdmin() || auth()->user()->isProcessingTeam())
            <div class="collapse" id="oppForm">
                <form method="POST" action="{{ route('opportunities.store') }}" class="form-row">
                    @csrf
                    <input class="form-control" name="name" placeholder="Program / Journal name" required>
                    <input class="form-control" name="country" placeholder="Country">
                    <select class="form-select" name="type">
                        <option value="study_abroad">Study Abroad</option>
                        <option value="research_publication">Research Publication</option>
                    </select>
                    <input class="form-control" type="date" name="deadline" required>
                    <input class="form-control" name="official_link" placeholder="Official website link">
                    <input class="form-control" name="application_link" placeholder="Application portal link">
                    <input class="form-control" type="number" step="0.01" name="cost" placeholder="Total process cost">
                    <select class="form-select" name="currency">
                        <option>USD</option><option>EUR</option><option>GBP</option><option>PKR</option>
                    </select>
                    <textarea class="form-control" name="requirements" placeholder="Requirements (e.g. IELTS 6.5, 2 references, statement of purpose)"></textarea>
                    <textarea class="form-control" name="guidance" placeholder="Guidance notes for counselors: cost breakdown, steps, timeline..."></textarea>
                    <button class="btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        @endif

        <div class="board" id="oppRows">
            <div class="board-row header">
                <div>Program / Journal</div><div>Requirements</div><div>Type</div><div>Country</div><div>Deadline</div>
                @if (!auth()->user()->isCounselor())
                    <div>Countdown</div>
                @endif
            </div>
            @include('opportunities._rows')
        </div>
    </div>
@if (auth()->user()->isAdmin())
    <div class="modal fade" id="oppEditModal" tabindex="-1" aria-labelledby="oppEditTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
            <form method="POST" id="oppEditForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="oppEditTitle">Edit Opportunity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Program / Journal name</label>
                            <input class="form-control" name="name" id="edit-name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input class="form-control" name="country" id="edit-country">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" id="edit-type">
                                <option value="study_abroad">Study Abroad</option>
                                <option value="research_publication">Research Publication</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deadline</label>
                            <input class="form-control" type="date" name="deadline" id="edit-deadline" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Official website link</label>
                            <input class="form-control" name="official_link" id="edit-official">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Application portal link</label>
                            <input class="form-control" name="application_link" id="edit-application">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Total process cost</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="cost" id="edit-cost">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <input class="form-control" name="currency" id="edit-currency" maxlength="3">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" name="requirements" id="edit-requirements" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Guidance notes for counselors</label>
                            <textarea class="form-control" name="guidance" id="edit-guidance" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="oppDeleteModal" tabindex="-1" aria-labelledby="oppDeleteTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="oppDeleteForm" class="modal-content">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="oppDeleteTitle">Delete opportunity?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You are about to delete <strong id="oppDeleteName"></strong>. This cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    $(function () {
        let timer = null;
        $('#oppSearch').on('input', function () {
            const q = $(this).val();
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.get('{{ route('opportunities.index') }}', { q: q }, function (html) {
                    $('#oppRows > .board-row.header').nextAll().remove();
                    $('#oppRows').append(html);
                });
            }, 250);
        });

        $(document).on('click', '.btn-opp-edit', function () {
            const d = this.dataset;
            $('#oppEditForm').attr('action', d.updateUrl);
            $('#edit-name').val(d.name);
            $('#edit-country').val(d.country);
            $('#edit-type').val(d.type);
            $('#edit-deadline').val(d.deadline);
            $('#edit-official').val(d.official);
            $('#edit-application').val(d.application);
            $('#edit-cost').val(d.cost);
            $('#edit-currency').val(d.currency);
            $('#edit-requirements').val(d.requirements);
            $('#edit-guidance').val(d.guidance);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('oppEditModal')).show();
        });

        $(document).on('click', '.btn-opp-delete', function () {
            const d = this.dataset;
            $('#oppDeleteForm').attr('action', d.deleteUrl);
            $('#oppDeleteName').text(d.name);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('oppDeleteModal')).show();
        });

        $(document).on('click', '.download-package', function () {
            const d = $(this).data();
            const isCounselor = {{ auth()->user()->isCounselor() ? 'true' : 'false' }};
            let linksText = '';
            if (!isCounselor) {
                linksText = `Official website: ${d.official || '-'}
Application portal: ${d.application || '-'}
`;
            }
            const content = `THE GUIDERS — STUDENT GUIDANCE PACKAGE
================================================
Program / Journal: ${d.name}
Country: ${d.country}
Type: ${d.type}
Application Deadline: ${d.deadline}

REQUIREMENTS
${d.requirements || 'Not yet listed'}

TOTAL PROCESS COST: ${d.cost}

GUIDANCE NOTES FOR COUNSELORS
${d.guidance || 'No guidance notes added yet.'}

LINKS
${linksText}
------------------------------------------------
Generated by The Guiders Portal for counselor use — share with the student as needed.
`;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = d.name.replace(/[^a-z0-9]/gi, '_') + '_package.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        });
    });
</script>
@endpush
