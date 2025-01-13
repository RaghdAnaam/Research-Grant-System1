@extends('layouts.app')

@section('title', 'Edit Milestone')

@section('content')
<h1>Edit Milestone</h1>

<!-- Display Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('milestones.update', $milestone->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="milestone_name" class="form-label">Milestone Name</label>
        <input type="text" name="milestone_name" id="milestone_name" class="form-control" 
               value="{{ $milestone->milestone_name }}" required>
    </div>

    <div class="mb-3">
        <label for="target_completion_date" class="form-label">Target Completion Date</label>
        <input type="date" name="target_completion_date" id="target_completion_date" 
               class="form-control" value="{{ $milestone->target_completion_date }}" required>
    </div>

    <div class="mb-3">
        <label for="deliverable" class="form-label">Deliverable</label>
        <input type="text" name="deliverable" id="deliverable" class="form-control" 
               value="{{ $milestone->deliverable }}" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select">
            <option value="Pending" @if($milestone->status == 'Pending') selected @endif>Pending</option>
            <option value="In Progress" @if($milestone->status == 'In Progress') selected @endif>In Progress</option>
            <option value="Completed" @if($milestone->status == 'Completed') selected @endif>Completed</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" class="form-control">{{ $milestone->remarks }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Milestone</button>
</form>
@endsection
