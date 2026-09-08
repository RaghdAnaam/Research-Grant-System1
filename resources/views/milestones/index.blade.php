@extends('layouts.app')

@section('title', 'Milestones for Grant')

@section('content')
<div class="dashboard-header">
    <div>
        <p class="eyebrow">Grant milestones</p>
        <h1>{{ $grant->project_title }}</h1>
        <p class="dashboard-subtitle">Add deliverables and track progress for this research grant.</p>
    </div>
    <a href="{{ route('leader.dashboard') }}" class="btn btn-outline-accent">Back to My Grants</a>
</div>

<div class="card-panel milestone-form-card">
    <div class="section-heading">
        <div>
            <h2>Add milestone</h2>
            <p>Define the next deliverable for this grant.</p>
        </div>
    </div>
    <form action="{{ route('milestones.store') }}" method="POST">
        @csrf
        <input type="hidden" name="grant_id" value="{{ $grant->id }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Milestone Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter milestone name" required>
            </div>
            <div class="col-md-6">
                <label for="target_completion_date" class="form-label">Target Completion Date</label>
                <input type="date" id="target_completion_date" name="target_completion_date" class="form-control" required>
            </div>
            <div class="col-12">
                <label for="deliverable" class="form-label">Deliverable</label>
                <textarea id="deliverable" name="deliverable" class="form-control" rows="2" placeholder="Enter deliverable details" required></textarea>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-accent">Add Milestone</button>
        </div>
    </form>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card-panel">
    <div class="section-heading">
        <div>
            <h2>Milestones</h2>
            <p>{{ $grant->milestones->count() }} {{ Str::plural('milestone', $grant->milestones->count()) }}</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table milestone-table">
            <thead>
                <tr>
                    <th>Milestone</th>
                    <th>Target Date</th>
                    <th>Deliverable</th>
                    <th>Status &amp; Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grant->milestones as $milestone)
                    <tr>
                        <td><strong>{{ $milestone->milestone_name }}</strong></td>
                        <td>{{ $milestone->target_completion_date }}</td>
                        <td>{{ $milestone->deliverable }}</td>
                        <td>
                    <form action="{{ route('milestones.updateStatus', $milestone->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm">
                                    @foreach (\App\Models\Milestone::statuses() as $status)
                                        <option value="{{ $status }}" @selected($milestone->status === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <textarea name="remarks" class="form-control form-control-sm mt-2" rows="2" placeholder="Optional remarks">{{ $milestone->remarks }}</textarea>
                                <button type="submit" class="btn btn-sm btn-accent mt-2">Save status</button>
                            </form>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('milestones.edit', $milestone->id) }}" class="btn btn-sm btn-outline-accent">Edit</a>
                                <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No milestones have been added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
