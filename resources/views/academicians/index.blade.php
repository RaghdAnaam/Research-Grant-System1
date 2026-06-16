@extends('layouts.app')

@section('title', 'Manage Academicians')

@section('content')
<h1>Manage Academicians</h1>
<a href="{{ route('academicians.create') }}" class="btn btn-primary mb-3">Add Academician</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Staff Number</th>
            <th>Email</th>
            <th>College</th>
            <th>Department</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($academicians as $academician)
            <tr>
                <td>{{ $academician->name }}</td>
                <td>{{ $academician->staff_number }}</td>
                <td>{{ $academician->email }}</td>
                <td>{{ $academician->college }}</td>
                <td>{{ $academician->department }}</td>
                <td>{{ $academician->position }}</td>
                <td>
                    <a href="{{ route('academicians.edit', $academician->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('academicians.destroy', $academician->id) }}" method="POST" style="display:inline;">
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
