@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h1>Welcome, Admin Executive!</h1>

<p>Manage all the academic staff, and grants here:</p>

<div class="mt-4">
    <a href="{{ route('academicians.index') }}" class="btn btn-primary">Manage Academicians</a>
    <a href="{{ route('grants.index') }}" class="btn btn-success">Manage Grants</a>
</div>
@endsection
