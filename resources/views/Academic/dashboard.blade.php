@extends('layouts.app')

@section('title', 'Academic Dashboard')

@section('content')
<h1>Welcome, {{ auth()->user()->name }}!</h1>

<h3>Your Grants</h3>
@if ($grants->isEmpty())
    <p>You are not a member of any grants yet.</p>
@else
    <table class="table table-bordered">
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
                    <td>{{ $grant->project_title }}</td>
                    <td>{{ $grant->leader->name }}</td>
                    <td>{{ $grant->grant_provider }}</td>
                    <td>${{ number_format($grant->grant_amount, 2) }}</td>
                    <td>{{ $grant->start_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
