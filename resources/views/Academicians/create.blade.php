@extends('layouts.app')

@section('title', 'Add Academician')

@section('content')
<h1>Add Academician</h1>

<form action="{{ route('academicians.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="staff_number" class="form-label">Staff Number</label>
        <input type="text" name="staff_number" id="staff_number" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="college" class="form-label">College</label>
        <input type="text" name="college" id="college" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <input type="text" name="department" id="department" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="position" class="form-label">Position</label>
        <select name="position" id="position" class="form-select">
            <option value="Professor">Professor</option>
            <option value="Assoc Prof">Assoc Prof</option>
            <option value="Senior Lecturer">Senior Lecturer</option>
            <option value="Lecturer">Lecturer</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Add Academician</button>
</form>
@endsection
