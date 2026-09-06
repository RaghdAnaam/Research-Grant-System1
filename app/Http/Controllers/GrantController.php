<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use App\Models\Grant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GrantController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $grants = Grant::with(['leader', 'members', 'milestones'])
            ->when($search, function ($query, $search) {
                $query->where('project_title', 'like', "%{$search}%")
                    ->orWhere('grant_provider', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('grants.index', compact('grants', 'search'));
    }

    public function create(): View
    {
        $academicians = Academician::orderBy('name')->get();

        return view('grants.create', compact('academicians'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateGrant($request);
        $memberIds = $this->memberIds($request, (int) $validated['leader_id']);
        unset($validated['member_ids']);

        $grant = Grant::create($validated);
        $grant->members()->sync($memberIds);

        return redirect()->route('grants.show', $grant)->with('success', 'Grant added successfully.');
    }

    public function show(Grant $grant): View
    {
        $this->authorizeGrantView($grant, request()->user());

        $grant->load(['leader', 'members', 'milestones']);
        $canManageMilestones = $this->userLeadsGrant($grant, request()->user());

        return view('grants.show', compact('grant', 'canManageMilestones'));
    }

    public function edit(Grant $grant): View
    {
        $grant->load('members');
        $academicians = Academician::orderBy('name')->get();

        return view('grants.edit', compact('grant', 'academicians'));
    }

    public function update(Request $request, Grant $grant): RedirectResponse
    {
        $validated = $this->validateGrant($request);
        $memberIds = $this->memberIds($request, (int) $validated['leader_id']);
        unset($validated['member_ids']);

        $grant->update($validated);
        $grant->members()->sync($memberIds);

        return redirect()->route('grants.show', $grant)->with('success', 'Grant updated successfully.');
    }

    public function destroy(Grant $grant): RedirectResponse
    {
        $grant->delete();

        return redirect()->route('grants.index')->with('success', 'Grant deleted successfully.');
    }

    private function validateGrant(Request $request): array
    {
        return $request->validate([
            'project_title' => 'required|string|max:255',
            'grant_provider' => 'required|string|max:255',
            'leader_id' => 'required|exists:academicians,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'integer|distinct|exists:academicians,id',
            'grant_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_months' => 'required|integer|min:1',
        ]);
    }

    private function memberIds(Request $request, int $leaderId): array
    {
        return collect($request->input('member_ids', []))
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === $leaderId)
            ->unique()
            ->values()
            ->all();
    }

    private function authorizeGrantView(Grant $grant, User $user): void
    {
        if ($user->hasRole('Admin') || $this->userLeadsGrant($grant, $user) || $this->userIsGrantMember($grant, $user)) {
            return;
        }

        abort(403, 'Unauthorized action.');
    }

    private function userLeadsGrant(Grant $grant, User $user): bool
    {
        return $user->academician_id !== null && (int) $grant->leader_id === (int) $user->academician_id;
    }

    private function userIsGrantMember(Grant $grant, User $user): bool
    {
        if ($user->academician_id === null) {
            return false;
        }

        return $grant->members()->where('academicians.id', $user->academician_id)->exists();
    }
}
