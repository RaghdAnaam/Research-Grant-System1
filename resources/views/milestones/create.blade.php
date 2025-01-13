@extends('layouts.app')

@section('title', 'Add Milestone')

@section('content')
<h1>Add Milestone for Grant: {{ $grant->project_title }}</h1>

<form action="{{ route('milestones.store') }}" method="POST">
    @csrf

    <input type="hidden" name="grant_id" value="{{ $grant->id }}">

    <div class="mb-3">
        <label for="name" class="form-label">Milestone Name</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="target_completion_date" class="form-label">Target Completion Date</label>
        <input type="date" id="target_completion_date" name="target_completion_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="deliverable" class="form-label">Deliverable</label>
        <textarea id="deliverable" name="deliverable" class="form-control" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Milestone</button>
</form>
@endsection
