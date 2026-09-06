@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<x-page-header title="Manage Users" subtitle="Create and manage system login accounts." />

<div class="card-panel">
    <x-table-toolbar :search="$search" placeholder="Search by name, email, or role...">
        <a href="{{ route('users.create') }}" class="btn btn-accent">Add User</a>
    </x-table-toolbar>

    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Academician Profile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><x-status-badge :status="$user->role?->label() ?? '-'" class="badge-progress" /></td>
                    <td>{{ $user->academician?->name ?? '-' }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-accent">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrapper">{{ $users->links() }}</div>
</div>
@endsection
