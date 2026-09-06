@extends('layouts.app')

@section('title', 'My Grants')

@section('content')
<x-page-header
    title="My Grants"
    subtitle="Research grants you are assigned to as a team member."
/>

<div class="card-panel">
    @if ($grants->isEmpty())
        <div class="empty-state">You are not a member of any grants yet.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Project Leader</th>
                    <th>Team</th>
                    <th>Budget</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grants as $grant)
                    <tr>
                        <td>{{ $grant->project_title }}</td>
                        <td>{{ $grant->leader?->name ?? 'Not assigned' }}</td>
                        <td>{{ $grant->members->pluck('name')->join(', ') ?: 'No members' }}</td>
                        <td>RM{{ number_format($grant->grant_amount, 2) }}</td>
                        <td>{{ $grant->milestones->where('status', 'Completed')->count() }} / {{ $grant->milestones->count() }} completed</td>
                        <td>
                            <a href="{{ route('grants.show', $grant) }}" class="btn btn-sm btn-outline-accent">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">{{ $grants->links() }}</div>
    @endif
</div>
@endsection
