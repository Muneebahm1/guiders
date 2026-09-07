@extends('layouts.app')

@section('title', 'User Management')
@section('subtitle', 'Create accounts and manage roles across the platform')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div><h2>All Users</h2><p>{{ $users->count() }} accounts</p></div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#userForm">
                + Add User
            </button>
        </div>

        <div class="collapse" id="userForm">
            <form method="POST" action="{{ route('users.store') }}" class="form-row">
                @csrf
                <input class="form-control" name="name" placeholder="Full name" required>
                <input class="form-control" type="email" name="email" placeholder="Email address" required>
                <input class="form-control" type="password" name="password" placeholder="Password (min 8 characters)" required>
                <select class="form-select" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="processing_team">Processing Team</option>
                    <option value="counselor" selected>Counselor</option>
                    <option value="student">Student</option>
                    <option value="partner">Partner Company</option>
                </select>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
            @error('role')
                <div class="px-3 pb-3 text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="name-cell">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="chip static {{ match($user->role) {
                                    'admin' => 'amber',
                                    'processing_team' => 'teal',
                                    'counselor' => 'gray',
                                    'partner' => 'gray',
                                    default => 'gray',
                                } }}">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                                @if ($user->is(auth()->user()))
                                    <span class="sub-cell">(you)</span>
                                @endif
                            </td>
                            <td class="date-cell">{{ $user->created_at->toDateString() }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#editUser{{ $user->id }}">
                                    Edit
                                </button>
                                @unless ($user->is(auth()->user()))
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                        <tr class="collapse" id="editUser{{ $user->id }}">
                            <td colspan="5" class="p-0">
                                <form method="POST" action="{{ route('users.update', $user) }}" class="form-row">
                                    @csrf
                                    @method('PATCH')
                                    <input class="form-control" name="name" value="{{ $user->name }}" required>
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                                    <input class="form-control" type="password" name="password" placeholder="New password (leave blank to keep current)">
                                    <select class="form-select" name="role" required>
                                        @foreach ($roleLabels as $value => $label)
                                            <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
