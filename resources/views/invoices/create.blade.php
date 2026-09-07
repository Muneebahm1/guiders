@extends('layouts.app')

@section('title', 'Generate Invoice')
@section('subtitle', 'Company account details are managed by admin under Company Settings')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Choose student</h2><p>&nbsp;</p></div>
        </div>
        <form method="GET" action="{{ route('invoices.create') }}" class="form-row p-3">
            <select class="form-select" name="student_id" onchange="this.form.submit()">
                <option value="">Select student&hellip;</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id === $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if ($selectedStudent)
        <div class="panel">
            <div class="panel-head">
                <div><h2>Invoice for {{ $selectedStudent->name }}</h2><p>&nbsp;</p></div>
            </div>

            <form method="POST" action="{{ route('invoices.store') }}" class="p-3" id="invoiceForm">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Application (optional)</label>
                        <select class="form-select" name="application_id">
                            <option value="">General / no specific application</option>
                            @foreach ($selectedStudent->applications as $application)
                                <option value="{{ $application->id }}" {{ $selectedApplicationId == $application->id ? 'selected' : '' }}>
                                    {{ $application->opportunity->name ?? 'Application #'.$application->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Issued date</label>
                        <input class="form-control" type="date" name="issued_date" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due date</label>
                        <input class="form-control" type="date" name="due_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Currency</label>
                        <input class="form-control" name="currency" value="PKR" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tax</label>
                        <input class="form-control" type="number" step="0.01" min="0" name="tax" value="0" id="taxInput">
                    </div>
                </div>

                <table class="table table-sm" id="itemsTable">
                    <thead><tr><th>Item</th><th style="width:110px">Currency</th><th style="width:130px">Amount</th><th style="width:130px">Exchange Rate</th><th style="width:140px">Amount</th><th></th></tr></thead>
                    <tbody>
                        <tr>
                            <td><input class="form-control" name="items[0][item]" required></td>
                            <td>
                                <select class="form-select" name="items[0][currency]" required>
                                    <option value="PKR" selected>PKR</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="AED">AED</option>
                                    <option value="CAD">CAD</option>
                                    <option value="AUD">AUD</option>
                                </select>
                            </td>
                            <td><input class="form-control foreign-amount-input" type="number" step="0.01" min="0" name="items[0][currency_value]" required></td>
                            <td><input class="form-control exchange-rate-input" type="number" step="0.01" min="0.01" name="items[0][exchange_rate]" value="1" required></td>
                            <td><span class="row-amount">0.00</span></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">&times;</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="addRow">+ Add line item</button>

                <div class="text-end mb-3">
                    <div>Subtotal: <strong id="subtotalDisplay">0.00</strong></div>
                    <div>Total: <strong id="totalDisplay">0.00</strong></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2">{{ $company->notes ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Terms</label>
                    <textarea class="form-control" name="terms" rows="2">{{ $company->terms ?? '' }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Generate Invoice</button>
            </form>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    $(function () {
        let rowIndex = 1;

        function recalc() {
            let subtotal = 0;
            $('#itemsTable tbody tr').each(function () {
                const foreignAmount = parseFloat($(this).find('.foreign-amount-input').val()) || 0;
                const exchangeRate = parseFloat($(this).find('.exchange-rate-input').val()) || 0;
                const amount = foreignAmount * exchangeRate;
                $(this).find('.row-amount').text(amount.toFixed(2));
                subtotal += amount;
            });
            const tax = parseFloat($('#taxInput').val()) || 0;
            $('#subtotalDisplay').text(subtotal.toFixed(2));
            $('#totalDisplay').text((subtotal + tax).toFixed(2));
        }

        $(document).on('input', '.foreign-amount-input, .exchange-rate-input, #taxInput', recalc);

        $('#addRow').on('click', function () {
            const row = `<tr>
                <td><input class="form-control" name="items[${rowIndex}][item]" required></td>
                <td>
                    <select class="form-select" name="items[${rowIndex}][currency]" required>
                        <option value="PKR" selected>PKR</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="AED">AED</option>
                        <option value="CAD">CAD</option>
                        <option value="AUD">AUD</option>
                    </select>
                </td>
                <td><input class="form-control foreign-amount-input" type="number" step="0.01" min="0" name="items[${rowIndex}][currency_value]" required></td>
                <td><input class="form-control exchange-rate-input" type="number" step="0.01" min="0.01" name="items[${rowIndex}][exchange_rate]" value="1" required></td>
                <td><span class="row-amount">0.00</span></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">&times;</button></td>
            </tr>`;
            $('#itemsTable tbody').append(row);
            rowIndex++;
        });

        $(document).on('click', '.remove-row', function () {
            if ($('#itemsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                recalc();
            }
        });

        recalc();
    });
</script>
@endpush
