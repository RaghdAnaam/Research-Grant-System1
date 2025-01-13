<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Academician;
use Illuminate\Http\Request;

class GrantController extends Controller
{
    public function index()
    {
        // Fetch all grants with associated leader and members
        $grants = Grant::with('leader', 'members')->get();
        return view('grants.index', compact('grants'));
    }

    public function create()
    {
        $academicians = Academician::all(); // Fetch all academicians
        return view('grants.create', compact('academicians'));
    }
    


public function store(Request $request)
{
    
    // Validate form inputs
    $request->validate([
        'project_title' => 'required|string|max:255',
        'grant_provider' => 'required|string|max:255',
        'leader_id' => 'required|exists:academicians,id', // Validate leader exists
        'grant_amount' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'duration_months' => 'required|integer|min:1',
    ]);

    // Create the grant
    Grant::create($request->all());

    // Redirect to the grants index page with success message
    return redirect()->route('grants.index')->with('success', 'Grant added successfully.');
}

    

    public function edit(Grant $grant)
    {
        // Fetch all academicians for editing
        $academicians = Academician::all();
        return view('grants.edit', compact('grant', 'academicians'));
    }

    public function update(Request $request, Grant $grant)
    {
        $request->validate([
            'leader_id' => 'required|exists:users,id', // Ensure leader exists in users table
            'grant_provider' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'grant_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'duration_months' => 'required|integer|min:1',
        ]);

        $grant->update($request->all());

        return redirect()->route('grants.index')->with('success', 'Grant updated successfully.');
    }

    public function destroy(Grant $grant)
    {
        $grant->delete();

        return redirect()->route('grants.index')->with('success', 'Grant deleted successfully.');
    }

    public function academicDashboard()
    {
        // Fetch grants where the user is a member
        $grants = auth()->user()->grantsAsMember()->with('leader')->get();

        return view('academic.dashboard', compact('grants'));
    }

    public function leaderDashboard()
{
    // Ensure the authenticated user has the 'ProjectLeader' role
    if (auth()->user()->role !== 'ProjectLeader') {
        abort(403, 'Unauthorized action.');
    }

    // Fetch grants where the user is the leader
    $grants = Grant::where('leader_id', auth()->id())->get();

    return view('leader.dashboard', compact('grants'));
}

}
