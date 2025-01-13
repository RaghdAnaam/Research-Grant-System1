@extends('layouts.app')

@section('title', 'Project Leader Dashboard')

@section('content')
<h1>Welcome, {{ auth()->user()->name }}!</h1>

<p>Below are the grants you are leading</p>

@if ($grants->isEmpty())
    <p>You are not leading any grants yet.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Project Title</th>
                <th>Grant Provider</th>
                <th>Grant Amount</th>
                <th>Start Date</th>
                <th>Duration (Months)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grants as $grant)
                <tr>
                    <td>{{ $grant->project_title }}</td>
                    <td>{{ $grant->grant_provider }}</td>
                    <td>${{ number_format($grant->grant_amount, 2) }}</td>
                    <td>{{ $grant->start_date }}</td>
                    <td>{{ $grant->duration_months }}</td>
                    <td>
                       
                        <a href="{{ route('milestones.create', $grant->id) }}" class="btn btn-primary btn-sm">Add Milestone</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
