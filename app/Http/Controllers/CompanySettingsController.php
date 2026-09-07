<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CompanySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function edit()
    {
        $company = CompanySettings::current();

        return view('company-settings.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
        ]);

        $company = CompanySettings::current();
        $data['updated_by_id'] = Auth::id();
        $company->update($data);

        ActivityLog::record('company_settings.updated', 'Updated company invoice/bank settings');

        return redirect()->route('company-settings.edit')->with('status', 'Company settings updated.');
    }
}
