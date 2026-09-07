@extends('layouts.app')

@section('title', 'Company Settings')
@section('subtitle', 'Bank/payment account details shown on every generated invoice')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>Company &amp; Bank Details</h2><p>Only admins can change these</p></div>
        </div>

        <form method="POST" action="{{ route('company-settings.update') }}" class="p-3">
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Company name</label>
                <input class="form-control" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2">{{ old('address', $company->address) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Bank name</label>
                    <input class="form-control" name="bank_name" value="{{ old('bank_name', $company->bank_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account number</label>
                    <input class="form-control" name="account_number" value="{{ old('account_number', $company->account_number) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Branch code</label>
                    <input class="form-control" name="branch_code" value="{{ old('branch_code', $company->branch_code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">IBAN</label>
                    <input class="form-control" name="iban" value="{{ old('iban', $company->iban) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Default invoice notes</label>
                <textarea class="form-control" name="notes" rows="2">{{ old('notes', $company->notes) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Default invoice terms</label>
                <textarea class="form-control" name="terms" rows="2">{{ old('terms', $company->terms) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
