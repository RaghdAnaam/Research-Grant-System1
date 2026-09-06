<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function index(Grant $grant): View
    {
        $this->authorizeLeaderForGrant($grant);
        $grant->load(['leader', 'members', 'milestones']);

        return view('milestones.index', compact('grant'));
    }

    public function create(Grant $grant): View
    {
        $this->authorizeLeaderForGrant($grant);

        return view('milestones.create', compact('grant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grant_id' => 'required|exists:grants,id',
            'name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string|max:255',
        ]);

        $grant = Grant::findOrFail($validated['grant_id']);
        $this->authorizeLeaderForGrant($grant);

        Milestone::create([
            'grant_id' => $grant->id,
            'milestone_name' => $validated['name'],
            'target_completion_date' => $validated['target_completion_date'],
            'deliverable' => $validated['deliverable'],
            'status' => 'Pending',
        ]);

        return redirect()->route('milestones.index', $grant)
            ->with('success', 'Milestone added successfully.');
    }

    public function edit(Milestone $milestone): View
    {
        $this->authorizeLeaderForGrant($milestone->grant);

        return view('milestones.edit', compact('milestone'));
    }

    public function update(Request $request, Milestone $milestone): RedirectResponse
    {
        $this->authorizeLeaderForGrant($milestone->grant);

        $validated = $request->validate([
            'milestone_name' => 'required|string|max:255',
            'target_completion_date' => 'required|date',
            'deliverable' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed',
            'remarks' => 'nullable|string',
        ]);

        $milestone->update($validated);

        return redirect()->route('milestones.index', $milestone->grant_id)
            ->with('success', 'Milestone updated successfully.');
    }

    public function updateStatus(Request $request, Milestone $milestone): RedirectResponse
    {
        $this->authorizeLeaderForGrant($milestone->grant);

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'remarks' => 'nullable|string',
        ]);

        $milestone->update($validated);

        return redirect()->route('milestones.index', $milestone->grant_id)
            ->with('success', 'Milestone status updated successfully.');
    }

    public function destroy(Milestone $milestone): RedirectResponse
    {
        $grantId = $milestone->grant_id;
        $this->authorizeLeaderForGrant($milestone->grant);
        $milestone->delete();

        return redirect()->route('milestones.index', $grantId)
            ->with('success', 'Milestone deleted successfully.');
    }

    private function authorizeLeaderForGrant(Grant $grant): void
    {
        if (auth()->user()?->academician_id !== null && (int) auth()->user()->academician_id === (int) $grant->leader_id) {
            return;
        }

        abort(403, 'Unauthorized action.');
    }
}
