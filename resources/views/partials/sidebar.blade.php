@php
    $user = auth()->user();
    $roleLabels = \App\Models\User::roleLabels();
@endphp

<div class="sidebar">
    <div class="brand-block">
        <img src="{{ asset('img/logo/logoguidr.png') }}" alt="The Guiders" class="brand-logo">
        <div class="brand-text">
            <div class="brand">THE GUIDERS</div>
            <div class="brand-sub">Overseas Educational Services</div>
        </div>
    </div>

    <div class="nav-label">{{ $roleLabels[$user->role] }}</div>

    @if ($user->hasBackOfficeAccess())
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="nav-dot"></span>Dashboard
        </a>
    @endif

    @if ($user->hasBackOfficeAccess())
        <a href="{{ route('opportunities.index') }}" class="nav-item {{ request()->routeIs('opportunities.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Opportunities
        </a>
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Study Abroad Students
        </a>
        <a href="{{ route('papers.index') }}" class="nav-item {{ request()->routeIs('papers.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Research Papers
        </a>
        <a href="{{ route('invoices.index') }}" class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Invoices
        </a>
        <a href="{{ route('contracts.index') }}" class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Contracts
        </a>
    @endif

    @if ($user->isAdmin())
        <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Tasks
        </a>
        <div class="nav-label">Administration</div>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>User Management
        </a>
        <a href="{{ route('company-settings.edit') }}" class="nav-item {{ request()->routeIs('company-settings.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Company Settings
        </a>
        <a href="{{ route('reports.staff') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Staff Report
        </a>
        <a href="{{ route('activity.index') }}" class="nav-item {{ request()->routeIs('activity.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Activity Log
        </a>
        <a href="{{ route('leads.all') }}" class="nav-item {{ request()->routeIs('leads.all') ? 'active' : '' }}">
            <span class="nav-dot"></span>All Leads
        </a>
        <a href="{{ route('followups.all') }}" class="nav-item {{ request()->routeIs('followups.all') ? 'active' : '' }}">
            <span class="nav-dot"></span>All Follow-ups
        </a>
    @endif

    @if ($user->isCounselor())
        <a href="{{ route('leads.index') }}" class="nav-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>My Leads
        </a>
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>My Study Abroad Students
        </a>
        <a href="{{ route('papers.index') }}" class="nav-item {{ request()->routeIs('papers.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>My Research Papers
        </a>
        <a href="{{ route('invoices.index') }}" class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Invoices
        </a>
        <a href="{{ route('contracts.index') }}" class="nav-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Contracts
        </a>
        <a href="{{ route('opportunities.index') }}" class="nav-item {{ request()->routeIs('opportunities.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Browse Opportunities
        </a>
        <a href="{{ route('suggest.index') }}" class="nav-item {{ request()->routeIs('suggest.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Suggest for Client
        </a>
        <a href="{{ route('leads.follow-ups') }}" class="nav-item {{ request()->routeIs('leads.follow-ups') ? 'active' : '' }}">
            <span class="nav-dot"></span>Follow-ups
        </a>
    @endif

    @if ($user->isStudent())
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>My Progress
        </a>
    @endif

    @if ($user->isPartner())
        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>My Registered Students
        </a>
        <a href="{{ route('opportunities.index') }}" class="nav-item {{ request()->routeIs('opportunities.*') ? 'active' : '' }}">
            <span class="nav-dot"></span>Opportunities
        </a>
    @endif

    <div class="access-note">
        @if ($user->isAdmin())
            Full access: opportunities, students, papers, user accounts, the activity log, and read-only visibility into every counselor's leads and follow-ups.
        @elseif ($user->isProcessingTeam())
            Can add and edit opportunities, and update application, fee, visa, and paper-review progress for any student across all counselors. No access to leads.
        @elseif ($user->isCounselor())
            Can manage own leads, students, and paper submissions. Can browse all opportunities, get budget-matched suggestions, and track follow-ups. Cannot add opportunities.
        @elseif ($user->isStudent())
            Read-only view of your own case. Nothing here can be edited by you.
        @else
            Can view all opportunities and register new students you've referred. Only sees your own referred students.
        @endif
    </div>
</div>
