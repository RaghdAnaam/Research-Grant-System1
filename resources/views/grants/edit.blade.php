@extends('layouts.app')

@section('title', 'Edit Grant')

@section('content')
<h1>Edit Grant</h1>

<form action="{{ route('grants.update', $grant->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="project_title" class="form-label">Project Title</label>
        <input type="text" name="project_title" id="project_title" class="form-control" value="{{ $grant->project_title }}" required>
    </div>
    <div class="mb-3">
        <label for="grant_provider" class="form-label">Grant Provider</label>
        <input type="text" name="grant_provider" id="grant_provider" class="form-control" value="{{ $grant->grant_provider }}" required>
    </div>
    <div class="mb-3">
        <label for="leader_id" class="form-label">Leader</label>
        <select name="leader_id" id="leader_id" class="form-select">
            @foreach ($academicians as $academician)
                <option value="{{ $academician->id }}" @if($grant->leader_id == $academician->id) selected @endif>
                    {{ $academician->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="grant_amount" class="form-label">Grant Amount</label>
        <input type="number" name="grant_amount" id="grant_amount" class="form-control" value="{{ $grant->grant_amount }}" required>
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $grant->start_date }}" required>
    </div>
    <div class="mb-3">
        <label for="duration_months" class="form-label">Duration (Months)</label>
        <input type="number" name="duration_months" id="duration_months" class="form-control" value="{{ $grant->duration_months }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Grant</button>
</form>
@endsection
