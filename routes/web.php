<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\CompanySettingsController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadDocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuggestController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/ping', [HomeController::class, 'ping'])->name('home.ping');

Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
Route::post('/opportunities', [OpportunityController::class, 'store'])->name('opportunities.store');
Route::post('/opportunities/{opportunity}/sessions', [OpportunityController::class, 'storeSession'])->name('opportunities.sessions.store');
Route::delete('/opportunities/sessions/{session}', [OpportunityController::class, 'destroySession'])->name('opportunities.sessions.destroy');

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/all', [LeadController::class, 'all'])->name('leads.all');
Route::get('/leads/follow-ups', [LeadController::class, 'followUps'])->name('leads.follow-ups');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::patch('/leads/{lead}/cycle-status', [LeadController::class, 'cycleStatus'])->name('leads.cycle-status');
Route::patch('/leads/{lead}/profile', [LeadController::class, 'updateProfile'])->name('leads.update-profile');
Route::post('/leads/{lead}/move-to-followup', [LeadController::class, 'moveToFollowUp'])->name('leads.move-to-followup');
Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
Route::post('/leads/{lead}/email', [LeadController::class, 'sendEmail'])->name('leads.email');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
Route::post('/leads/{lead}/reminders', [LeadController::class, 'storeReminder'])->name('leads.reminders.store');
Route::patch('/reminders/{reminder}/complete', [LeadController::class, 'completeReminder'])->name('reminders.complete');
Route::patch('/reminders/{reminder}/snooze', [LeadController::class, 'snoozeReminder'])->name('reminders.snooze');
Route::delete('/reminders/{reminder}', [LeadController::class, 'destroyReminder'])->name('reminders.destroy');
Route::post('/leads/{lead}/documents', [LeadDocumentController::class, 'store'])->name('leads.documents.store');
Route::delete('/documents/{document}', [LeadDocumentController::class, 'destroy'])->name('documents.destroy');
Route::post('/leads/upload', [LeadController::class, 'upload'])->name('leads.upload');
Route::get('/leads/template', [LeadController::class, 'template'])->name('leads.template');

Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::post('/students/register', [StudentController::class, 'registerPartnerStudent'])->name('students.register');
Route::post('/students/{student}/claim', [StudentController::class, 'claim'])->name('students.claim');
Route::get('/students/{student}/detail', [StudentController::class, 'detail'])->name('students.detail');

Route::post('/students/{student}/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::patch('/applications/{application}/cycle-app-stage', [ApplicationController::class, 'cycleAppStage'])->name('applications.cycle-app-stage');
Route::patch('/applications/{application}/cycle-visa-stage', [ApplicationController::class, 'cycleVisaStage'])->name('applications.cycle-visa-stage');
Route::patch('/applications/{application}/cycle-travel-status', [ApplicationController::class, 'cycleTravelStatus'])->name('applications.cycle-travel-status');
Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

Route::post('/applications/{application}/installments', [InstallmentController::class, 'store'])->name('installments.store');
Route::patch('/installments/{installment}/mark-paid', [InstallmentController::class, 'markPaid'])->name('installments.mark-paid');
Route::delete('/installments/{installment}', [InstallmentController::class, 'destroy'])->name('installments.destroy');

Route::get('/applications/{application}/clearance', [ClearanceController::class, 'show'])->name('clearance.show');
Route::patch('/applications/{application}/clearance', [ClearanceController::class, 'update'])->name('clearance.update');

Route::get('/papers', [PaperController::class, 'index'])->name('papers.index');
Route::post('/papers', [PaperController::class, 'store'])->name('papers.store');
Route::patch('/papers/{paper}/cycle-status', [PaperController::class, 'cycleStatus'])->name('papers.cycle-status');

Route::get('/followups', [FollowUpController::class, 'index'])->name('followups.index');
Route::get('/followups/all', [FollowUpController::class, 'all'])->name('followups.all');
Route::post('/followups', [FollowUpController::class, 'store'])->name('followups.store');
Route::patch('/followups/{followUp}/cycle-action', [FollowUpController::class, 'cycleAction'])->name('followups.cycle-action');

Route::get('/suggest', [SuggestController::class, 'index'])->name('suggest.index');
Route::get('/suggest/search', [SuggestController::class, 'search'])->name('suggest.search');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::get('/company-settings', [CompanySettingsController::class, 'edit'])->name('company-settings.edit');
Route::patch('/company-settings', [CompanySettingsController::class, 'update'])->name('company-settings.update');

Route::get('/reports/staff', [ReportController::class, 'staffMonthly'])->name('reports.staff');

Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
