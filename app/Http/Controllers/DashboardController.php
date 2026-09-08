<?php

namespace App\Http\Controllers;

use App\Models\Academician;
use App\Models\Grant;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRouteName());
    }

    public function admin(): View
    {
        $completedMilestones = Milestone::where('status', 'Completed')->count();
        $activeProjects = Grant::where(function ($query) {
            $query->whereDoesntHave('milestones')
                ->orWhereHas('milestones', fn ($q) => $q->where('status', '!=', 'Completed'));
        })->count();

        return view('admin.dashboard', [
            'grantCount' => Grant::count(),
            'academicianCount' => Academician::count(),
            'activeProjects' => $activeProjects,
            'completedMilestones' => $completedMilestones,
            'userCount' => User::count(),
        ]);
    }

    public function leader(Request $request): View
    {
        $academician = $request->user()->academician;
        $grantQuery = $academician
            ? $academician->grantsAsLeader()->with(['members', 'milestones'])->latest()
            : Grant::query()->whereRaw('1 = 0');

        $grants = (clone $grantQuery)->get();
        $grantIds = $grants->pluck('id');

        return view('leader.dashboard', [
            'grants' => $grants,
            'myGrantsCount' => $grants->count(),
            'pendingMilestones' => Milestone::whereIn('grant_id', $grantIds)->where('status', 'Pending')->count(),
            'completedMilestones' => Milestone::whereIn('grant_id', $grantIds)->where('status', 'Completed')->count(),
        ]);
    }

    public function leaderGrants(Request $request): View
    {
        $academician = $request->user()->academician;
        $grants = $academician
            ? $academician->grantsAsLeader()->with(['members', 'milestones'])->latest()->paginate(10)->withQueryString()
            : Grant::query()->whereRaw('1 = 0')->paginate(10);

        return view('leader.grants', compact('grants'));
    }

    public function academic(Request $request): View
    {
        $academician = $request->user()->academician;
        $grants = $academician
            ? $academician->grantsAsMember()->with(['leader', 'members', 'milestones'])->latest()->get()
            : collect();

        $ongoingProjects = $grants->filter(function ($grant) {
            return $grant->milestones->isEmpty()
                || $grant->milestones->contains(fn ($m) => $m->status !== 'Completed');
        })->count();

        return view('academic.dashboard', [
            'grants' => $grants,
            'assignedGrantsCount' => $grants->count(),
            'ongoingProjects' => $ongoingProjects,
        ]);
    }

    public function academicGrants(Request $request): View
    {
        $academician = $request->user()->academician;
        $grants = $academician
            ? $academician->grantsAsMember()->with(['leader', 'members', 'milestones'])->latest()->paginate(10)->withQueryString()
            : Grant::query()->whereRaw('1 = 0')->paginate(10);

        return view('academic.grants', compact('grants'));
    }
}
