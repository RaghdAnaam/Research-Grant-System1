<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Academician;
use App\Models\Grant;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_grant_members(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $leader = Academician::create($this->academicianData('Dr Ahmad', 'STF001', 'leader@example.com'));
        $member = Academician::create($this->academicianData('Dr Ali', 'STF002', 'academic@example.com'));

        $this->actingAs($admin)->post(route('grants.store'), [
            'project_title' => 'AI Research',
            'grant_provider' => 'University Internal Grant',
            'leader_id' => $leader->id,
            'member_ids' => [$member->id],
            'grant_amount' => 50000,
            'start_date' => now()->toDateString(),
            'duration_months' => 12,
        ])->assertRedirect();

        $grant = Grant::where('project_title', 'AI Research')->firstOrFail();

        $this->assertTrue($grant->members()->whereKey($member->id)->exists());
    }

    public function test_admin_can_create_a_user_linked_to_an_existing_academician(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $academician = Academician::create($this->academicianData('Dr New Leader', 'STF100', 'new-leader@example.com'));

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Dr New Leader',
            'email' => 'new-leader@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => UserRole::Leader->value,
            'academician_id' => $academician->id,
        ])->assertRedirect(route('users.index'));

        $user = User::where('email', 'new-leader@example.com')->firstOrFail();

        $this->assertSame(UserRole::Leader, $user->role);
        $this->assertSame($academician->id, $user->academician_id);
    }

    public function test_project_leader_only_sees_grants_they_lead(): void
    {
        [$leaderUser, $leaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Ahmad', 'STF001', 'leader@example.com');
        [, $otherLeaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Sara', 'STF002', 'sara@example.com');

        Grant::create($this->grantData('AI Research', $leaderProfile->id));
        Grant::create($this->grantData('Smart Parking', $otherLeaderProfile->id));

        $this->actingAs($leaderUser)
            ->get(route('leader.grants'))
            ->assertOk()
            ->assertSee('AI Research')
            ->assertDontSee('Smart Parking');
    }

    public function test_academic_only_sees_assigned_grants(): void
    {
        [$academicUser, $academicProfile] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');
        $leader = Academician::create($this->academicianData('Dr Ahmad', 'STF002', 'leader@example.com'));

        $assignedGrant = Grant::create($this->grantData('AI Research', $leader->id));
        $assignedGrant->members()->sync([$academicProfile->id]);
        Grant::create($this->grantData('Smart Parking', $leader->id));

        $this->actingAs($academicUser)
            ->get(route('academic.grants'))
            ->assertOk()
            ->assertSee('AI Research')
            ->assertDontSee('Smart Parking');
    }

    public function test_leader_dashboard_renders_their_grants(): void
    {
        [$leaderUser, $leaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Ahmad', 'STF001', 'leader@example.com');
        [, $otherLeaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Sara', 'STF002', 'sara@example.com');

        Grant::create($this->grantData('AI Research', $leaderProfile->id));
        Grant::create($this->grantData('Smart Parking', $otherLeaderProfile->id));

        $this->actingAs($leaderUser)
            ->get(route('leader.dashboard'))
            ->assertOk()
            ->assertSee('AI Research')
            ->assertDontSee('Smart Parking');
    }

    public function test_academic_dashboard_renders_assigned_grants(): void
    {
        [$academicUser, $academicProfile] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');
        $leader = Academician::create($this->academicianData('Dr Ahmad', 'STF002', 'leader@example.com'));

        $assignedGrant = Grant::create($this->grantData('AI Research', $leader->id));
        $assignedGrant->members()->sync([$academicProfile->id]);
        Grant::create($this->grantData('Smart Parking', $leader->id));

        $this->actingAs($academicUser)
            ->get(route('academic.dashboard'))
            ->assertOk()
            ->assertSee('AI Research')
            ->assertDontSee('Smart Parking');
    }

    public function test_leader_cannot_manage_another_leaders_milestones(): void
    {
        [$leaderUser] = $this->userWithProfile(UserRole::Leader, 'Dr Ahmad', 'STF001', 'leader@example.com');
        [, $otherLeaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Sara', 'STF002', 'sara@example.com');

        $grant = Grant::create($this->grantData('Smart Parking', $otherLeaderProfile->id));
        Milestone::create([
            'grant_id' => $grant->id,
            'milestone_name' => 'Data Collection',
            'target_completion_date' => now()->addMonth()->toDateString(),
            'deliverable' => 'Dataset',
            'status' => 'Pending',
        ]);

        $this->actingAs($leaderUser)
            ->get(route('milestones.index', $grant))
            ->assertForbidden();
    }

    public function test_academic_cannot_manage_grants_or_users(): void
    {
        [$academicUser] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');

        $this->actingAs($academicUser)->get(route('grants.index'))->assertForbidden();
        $this->actingAs($academicUser)->get(route('grants.create'))->assertForbidden();
        $this->actingAs($academicUser)->get(route('users.index'))->assertForbidden();
        $this->actingAs($academicUser)->get(route('academicians.index'))->assertForbidden();
    }

    public function test_academic_can_view_assigned_grant_details(): void
    {
        [$academicUser, $academicProfile] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');
        $leader = Academician::create($this->academicianData('Dr Ahmad', 'STF002', 'leader@example.com'));

        $grant = Grant::create($this->grantData('AI Research', $leader->id));
        $grant->members()->sync([$academicProfile->id]);

        Milestone::create([
            'grant_id' => $grant->id,
            'milestone_name' => 'Literature Review',
            'target_completion_date' => now()->addMonth()->toDateString(),
            'deliverable' => 'Review document',
            'status' => 'Completed',
        ]);

        $this->actingAs($academicUser)
            ->get(route('grants.show', $grant))
            ->assertOk()
            ->assertSee('AI Research')
            ->assertSee('Literature Review')
            ->assertSee('Completed')
            ->assertDontSee('Manage Milestones');
    }

    public function test_leader_can_manage_milestones_for_their_grant(): void
    {
        [$leaderUser, $leaderProfile] = $this->userWithProfile(UserRole::Leader, 'Dr Ahmad', 'STF001', 'leader@example.com');

        $grant = Grant::create($this->grantData('AI Research', $leaderProfile->id));

        $this->actingAs($leaderUser)
            ->get(route('milestones.index', $grant))
            ->assertOk()
            ->assertSee('Add Milestone');

        $this->actingAs($leaderUser)
            ->post(route('milestones.store'), [
                'grant_id' => $grant->id,
                'name' => 'Data Collection',
                'target_completion_date' => now()->addMonth()->toDateString(),
                'deliverable' => 'Dataset',
            ])
            ->assertRedirect(route('milestones.index', $grant));

        $this->assertDatabaseHas('milestones', [
            'grant_id' => $grant->id,
            'milestone_name' => 'Data Collection',
            'status' => 'Pending',
        ]);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_leader_login_redirects_to_leader_dashboard(): void
    {
        [$leaderUser] = $this->userWithProfile(UserRole::Leader, 'Dr Ahmad', 'STF001', 'leader@example.com');

        $this->post('/login', ['email' => $leaderUser->email, 'password' => 'password'])
            ->assertRedirect(route('leader.dashboard'));
    }

    public function test_academic_login_redirects_to_academic_dashboard(): void
    {
        [$academicUser] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');

        $this->post('/login', ['email' => $academicUser->email, 'password' => 'password'])
            ->assertRedirect(route('academic.dashboard'));
    }

    public function test_academic_cannot_view_unassigned_grants(): void
    {
        [$academicUser] = $this->userWithProfile(UserRole::Academic, 'Dr Ali', 'STF001', 'academic@example.com');
        $leader = Academician::create($this->academicianData('Dr Ahmad', 'STF002', 'leader@example.com'));
        $grant = Grant::create($this->grantData('Smart Parking', $leader->id));

        $this->actingAs($academicUser)
            ->get(route('grants.show', $grant))
            ->assertForbidden();
    }

    private function userWithProfile(UserRole $role, string $name, string $staffNumber, string $email): array
    {
        $profile = Academician::create($this->academicianData($name, $staffNumber, $email));
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'academician_id' => $profile->id,
        ]);

        return [$user, $profile];
    }

    private function academicianData(string $name, string $staffNumber, string $email): array
    {
        return [
            'name' => $name,
            'staff_number' => $staffNumber,
            'email' => $email,
            'college' => 'College of Computing',
            'department' => 'Computer Science',
            'position' => 'Lecturer',
        ];
    }

    private function grantData(string $title, int $leaderId): array
    {
        return [
            'project_title' => $title,
            'grant_provider' => 'University Internal Grant',
            'leader_id' => $leaderId,
            'grant_amount' => 50000,
            'start_date' => now()->toDateString(),
            'duration_months' => 12,
        ];
    }
}
