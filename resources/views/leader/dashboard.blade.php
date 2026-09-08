@extends('layouts.app')

@section('title', 'Project Leader Dashboard')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">Project Leader</p>
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p class="dashboard-subtitle">Manage the grants you lead and keep their milestones up to date.</p>
    </div>
</div>

@if ($grants->isEmpty())
    <div class="card-panel empty-state">You are not leading any grants yet.</div>
@else
    <div class="card-panel">
        <div class="section-heading">
            <div>
                <h2>Grants you lead</h2>
                <p>{{ $grants->count() }} {{ Str::plural('grant', $grants->count()) }}</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Grant Provider</th>
                        <th>Grant Amount</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grants as $grant)
                        <tr>
                            <td><strong>{{ $grant->project_title }}</strong></td>
                            <td>{{ $grant->grant_provider }}</td>
                            <td>${{ number_format($grant->grant_amount, 2) }}</td>
                            <td>{{ $grant->start_date }}</td>
                            <td>{{ $grant->duration_months }} months</td>
                            <td>
                                <a href="{{ route('milestones.create', $grant->id) }}" class="btn btn-sm btn-accent">Add Milestone</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
