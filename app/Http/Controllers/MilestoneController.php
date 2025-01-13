<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Grant;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index(Grant $grant)
{
    return view('milestones.index', compact('grant'));
}


    public function create(Grant $grant)
    {
        // Pass the grant to the milestone creation view
        return view('milestones.create', compact('grant'));
    }

    public function store(Request $request)
{
    $request->validate([
        'grant_id' => 'required|exists:grants,id',
        'name' => 'required|string|max:255',
        'target_completion_date' => 'required|date',
        'deliverable' => 'required|string|max:255',
    ]);

    Milestone::create([
        'grant_id' => $request->input('grant_id'),
        'milestone_name' => $request->input('name'),
        'target_completion_date' => $request->input('target_completion_date'),
        'deliverable' => $request->input('deliverable'),
        'status' => 'Pending',
    ]);

    return redirect()->route('milestones.index', ['grant' => $request->input('grant_id')])
                     ->with('success', 'Milestone added successfully.');
}

public function edit(Milestone $milestone)
{
    return view('milestones.edit', compact('milestone'));
}


public function update(Request $request, Milestone $milestone)
{
    $request->validate([
        'milestone_name' => 'required|string|max:255', // Use milestone_name, as per the form
        'target_completion_date' => 'required|date',
        'deliverable' => 'required|string|max:255',
        'status' => 'required|in:Pending,In Progress,Completed',
        'remarks' => 'nullable|string',
    ]);

    // Update the milestone
    $milestone->update([
        'milestone_name' => $request->input('milestone_name'),
        'target_completion_date' => $request->input('target_completion_date'),
        'deliverable' => $request->input('deliverable'),
        'status' => $request->input('status'),
        'remarks' => $request->input('remarks'),
    ]);

    // Redirect to the milestones page
    return redirect()->route('milestones.index', ['grant' => $milestone->grant_id])
                     ->with('success', 'Milestone updated successfully.');
}
public function updateStatus(Request $request, Milestone $milestone)
{
    $request->validate([
        'status' => 'required|in:Pending,In Progress,Completed',
        'remarks' => 'nullable|string',
    ]);

    $milestone->update([
        'status' => $request->input('status'),
        'remarks' => $request->input('remarks'),
    ]);

    return redirect()->route('milestones.index', ['grant' => $milestone->grant_id])
                     ->with('success', 'Milestone status updated successfully.');
}

    

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();
        return redirect()->back()->with('success', 'Milestone deleted successfully.');
        
    }
}
