@extends('layouts.app')

@section('title', 'Manage Grants')

@section('content')
<h1>Manage Grants</h1>
<a href="{{ route('grants.create') }}" class="btn btn-primary mb-3">Add Grant</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Project Title</th>
            <th>Grant Provider</th>
            <th>Leader</th>
            <th>Amount</th>
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
                <td>{{ $grant->leader->name }}</td>
                <td>${{ number_format($grant->grant_amount, 2) }}</td>
                <td>{{ $grant->start_date }}</td>
                <td>{{ $grant->duration_months }}</td>
                <td>
                    <a href="{{ route('grants.edit', $grant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('grants.destroy', $grant->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
