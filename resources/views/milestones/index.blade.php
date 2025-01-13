@extends('layouts.app')

@section('title', 'Milestones for Grant')

@section('content')
<h1>Milestones for {{ $grant->project_title }}</h1>

<!-- Inline form to add a new milestone -->
<div class="mb-4">
    <form action="{{ route('milestones.store') }}" method="POST">
        @csrf
        <input type="hidden" name="grant_id" value="{{ $grant->id }}">

        <div class="mb-3">
            <label for="name" class="form-label">Milestone Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter milestone name" required>
        </div>
        <div class="mb-3">
            <label for="target_completion_date" class="form-label">Target Completion Date</label>
            <input type="date" id="target_completion_date" name="target_completion_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deliverable" class="form-label">Deliverable</label>
            <textarea id="deliverable" name="deliverable" class="form-control" rows="2" placeholder="Enter deliverable details" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Add Milestone</button>
    </form>
</div>

<!-- Display success message -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Display validation errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Milestones Table -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Milestone Name</th>
            <th>Target Completion Date</th>
            <th>Deliverable</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($grant->milestones as $milestone)
            <tr>
                <td>{{ $milestone->milestone_name }}</td>
                <td>{{ $milestone->target_completion_date }}</td>
                <td>{{ $milestone->deliverable }}</td>
                <td>
                    <!-- Inline status update form -->
                    <form action="{{ route('milestones.updateStatus', $milestone->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <select name="status" class="form-select form-select-sm">
        <option value="Pending" {{ $milestone->status == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Completed" {{ $milestone->status == 'Completed' ? 'selected' : '' }}>Completed</option>
    </select>
    <textarea name="remarks" class="form-control form-control-sm mt-1" placeholder="Add remarks (optional)">{{ $milestone->remarks }}</textarea>
    <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
</form>

                </td>
                <td>{{ $milestone->remarks }}</td>
                <td>
                    <a href="{{ route('milestones.edit', $milestone->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST" style="display:inline;">
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
