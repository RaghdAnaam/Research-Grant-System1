@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">Administration</p>
        <h1>Welcome, Admin Executive!</h1>
        <p class="dashboard-subtitle">Manage academic staff, user accounts, grants, and project progress.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-accent">Add User</a>
</div>

<div class="dashboard-stat-grid">
    <div class="dashboard-stat-card">
        <span class="dashboard-stat-label">Users</span>
        <strong>{{ $userCount }}</strong>
        <span>system accounts</span>
    </div>
    <div class="dashboard-stat-card">
        <span class="dashboard-stat-label">Academicians</span>
        <strong>{{ $academicianCount }}</strong>
        <span>staff profiles</span>
    </div>
    <div class="dashboard-stat-card">
        <span class="dashboard-stat-label">Active grants</span>
        <strong>{{ $activeProjects }}</strong>
        <span>projects in progress</span>
    </div>
    <div class="dashboard-stat-card">
        <span class="dashboard-stat-label">Completed milestones</span>
        <strong>{{ $completedMilestones }}</strong>
        <span>finished deliverables</span>
    </div>
</div>

<div class="dashboard-actions card-panel">
    <div>
        <h2>Quick actions</h2>
        <p>Use these shortcuts to manage the system.</p>
    </div>
    <div class="dashboard-action-links">
        <a href="{{ route('users.index') }}" class="btn btn-outline-accent">Manage Users</a>
        <a href="{{ route('academicians.index') }}" class="btn btn-outline-accent">Manage Academicians</a>
        <a href="{{ route('grants.index') }}" class="btn btn-accent">Manage Grants</a>
    </div>
</div>
@endsection
