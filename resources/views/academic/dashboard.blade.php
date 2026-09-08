@extends('layouts.app')

@section('title', 'Academic Dashboard')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">Academic</p>
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p class="dashboard-subtitle">View the grants and research projects assigned to you.</p>
    </div>
</div>

@if ($grants->isEmpty())
    <div class="card-panel empty-state">You are not a member of any grants yet.</div>
@else
    <div class="card-panel">
        <div class="section-heading">
            <div>
                <h2>Your grants</h2>
                <p>{{ $grants->count() }} {{ Str::plural('grant', $grants->count()) }} assigned</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Leader</th>
                        <th>Grant Provider</th>
                        <th>Grant Amount</th>
                        <th>Start Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grants as $grant)
                        <tr>
                            <td><strong>{{ $grant->project_title }}</strong></td>
                            <td>{{ $grant->leader->name }}</td>
                            <td>{{ $grant->grant_provider }}</td>
                            <td>${{ number_format($grant->grant_amount, 2) }}</td>
                            <td>{{ $grant->start_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
