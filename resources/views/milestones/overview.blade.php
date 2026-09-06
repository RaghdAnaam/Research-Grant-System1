@extends('layouts.app')

@section('title', $title)

@section('content')
<x-page-header :title="$title" :subtitle="$subtitle" />

<div class="card-panel">
    <x-table-toolbar :search="$search" placeholder="Search by milestone or grant title..." />

    <table class="data-table">
        <thead>
            <tr>
                <th>Grant</th>
                <th>Milestone</th>
                <th>Target Date</th>
                <th>Deliverable</th>
                <th>Status</th>
                <th>Remarks</th>
                @if (! $readOnly)
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($milestones as $milestone)
                <tr>
                    <td>{{ $milestone->grant?->project_title ?? '-' }}</td>
                    <td>{{ $milestone->milestone_name }}</td>
                    <td>{{ $milestone->target_completion_date }}</td>
                    <td>{{ $milestone->deliverable }}</td>
                    <td><x-status-badge :status="$milestone->displayStatus()" /></td>
                    <td>{{ $milestone->remarks ?? '-' }}</td>
                    @if (! $readOnly)
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('milestones.index', $milestone->grant_id) }}" class="btn btn-sm btn-accent">Manage</a>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $readOnly ? 6 : 7 }}" class="empty-state">No milestones found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrapper">{{ $milestones->links() }}</div>
</div>
@endsection
