@extends('layouts.app')

@section('title', 'My Led Grants')

@section('content')
<x-page-header
    title="My Grants"
    subtitle="Grants you are leading as project leader."
/>

<div class="card-panel">
    @if ($grants->isEmpty())
        <div class="empty-state">You are not leading any grants yet.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Budget</th>
                    <th>Team Members</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grants as $grant)
                    <tr>
                        <td>{{ $grant->project_title }}</td>
                        <td>RM{{ number_format($grant->grant_amount, 2) }}</td>
                        <td>{{ $grant->members->pluck('name')->join(', ') ?: 'No members' }}</td>
                        <td>{{ $grant->milestones->where('status', 'Completed')->count() }} / {{ $grant->milestones->count() }} completed</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('grants.show', $grant) }}" class="btn btn-sm btn-outline-accent">View</a>
                                <a href="{{ route('milestones.index', $grant) }}" class="btn btn-sm btn-accent">Milestones</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper">{{ $grants->links() }}</div>
    @endif
</div>
@endsection
