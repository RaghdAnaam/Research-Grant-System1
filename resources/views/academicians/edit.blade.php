@extends('layouts.app')

@section('title', 'Edit Academician')

@section('content')
<h1>Edit Academician</h1>

<form action="{{ route('academicians.update', $academician->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $academician->name }}" required>
    </div>
    <div class="mb-3">
        <label for="staff_number" class="form-label">Staff Number</label>
        <input type="text" name="staff_number" id="staff_number" class="form-control" value="{{ $academician->staff_number }}" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ $academician->email }}" required>
    </div>
    <div class="mb-3">
        <label for="college" class="form-label">College</label>
        <input type="text" name="college" id="college" class="form-control" value="{{ $academician->college }}" required>
    </div>
    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <input type="text" name="department" id="department" class="form-control" value="{{ $academician->department }}" required>
    </div>
    <div class="mb-3">
        <label for="position" class="form-label">Position</label>
        <select name="position" id="position" class="form-select">
            <option value="Professor" @if($academician->position == 'Professor') selected @endif>Professor</option>
            <option value="Assoc Prof" @if($academician->position == 'Assoc Prof') selected @endif>Assoc Prof</option>
            <option value="Senior Lecturer" @if($academician->position == 'Senior Lecturer') selected @endif>Senior Lecturer</option>
            <option value="Lecturer" @if($academician->position == 'Lecturer') selected @endif>Lecturer</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Academician</button>
</form>
@endsection
