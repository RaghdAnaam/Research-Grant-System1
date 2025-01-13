@extends('layouts.app')

@section('title', 'Add Grant')

@section('content')
<h1>Add Grant</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('grants.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="project_title" class="form-label">Project Title</label>
        <input type="text" name="project_title" id="project_title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="grant_provider" class="form-label">Grant Provider</label>
        <input type="text" name="grant_provider" id="grant_provider" class="form-control" required>
    </div>
    <div class="mb-3">
    <label for="leader_id" class="form-label">Leader</label>
    <select name="leader_id" id="leader_id" class="form-select" required>
        @foreach ($academicians as $academician)
            <option value="{{ $academician->id }}">{{ $academician->name }}</option>
        @endforeach
    </select>
</div>
/    <div class="mb-3">
        <label for="grant_amount" class="form-label">Grant Amount</label>
        <input type="number" name="grant_amount" id="grant_amount" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="duration_months" class="form-label">Duration (Months)</label>
        <input type="number" name="duration_months" id="duration_months" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Add Grant</button>
</form>
@endsection
