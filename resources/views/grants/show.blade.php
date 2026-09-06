@extends('layouts.app')

@section('title', 'Grant Details')

@section('content')
<x-page-header :title="$grant->project_title" subtitle="Grant details, team information, and milestone progress." />

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card-panel h-100 mb-0">
            <h5 class="mb-3">Grant Details</h5>
            <p class="mb-2"><strong>Provider:</strong> {{ $grant->grant_provider }}</p>
            <p class="mb-2"><strong>Budget:</strong> RM{{ number_format($grant->grant_amount, 2) }}</p>
            <p class="mb-2"><strong>Start Date:</strong> {{ $grant->start_date }}</p>
            <p class="mb-0"><strong>Duration:</strong> {{ $grant->duration_months }} months</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-panel h-100 mb-0">
            <h5 class="mb-3">Team Information</h5>
            <p class="mb-2"><strong>Project Leader:</strong> {{ $grant->leader?->name ?? 'Not assigned' }}</p>
            <strong>Members:</strong>
            @if ($grant->members->isEmpty())
                <p class="mb-0 mt-1">No members assigned.</p>
            @else
                <ul class="mb-0 mt-1">
                    @foreach ($grant->members as $member)
                        <li>{{ $member->name }} ({{ $member->department }})</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Milestone Progress</h5>
        @if ($canManageMilestones)
            <a href="{{ route('milestones.index', $grant) }}" class="btn btn-accent btn-sm">Manage Milestones</a>
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Milestone</th>
                <th>Target Date</th>
                <th>Deliverable</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grant->milestones as $milestone)
                <tr>
                    <td>{{ $milestone->milestone_name }}</td>
                    <td>{{ $milestone->target_completion_date }}</td>
                    <td>{{ $milestone->deliverable }}</td>
                    <td><x-status-badge :status="$milestone->displayStatus()" /></td>
                    <td>{{ $milestone->remarks ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No milestones added yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    @if (auth()->user()->hasRole('Admin'))
        <a href="{{ route('grants.edit', $grant) }}" class="btn btn-outline-accent">Edit Grant</a>
        <a href="{{ route('grants.index') }}" class="btn btn-outline-secondary">Back to Grants</a>
    @elseif (auth()->user()->hasRole('Leader'))
        <a href="{{ route('leader.grants') }}" class="btn btn-outline-secondary">Back to My Grants</a>
    @elseif (auth()->user()->hasRole('Academic'))
        <a href="{{ route('academic.grants') }}" class="btn btn-outline-secondary">Back to My Grants</a>
    @endif
</div>
@endsection
