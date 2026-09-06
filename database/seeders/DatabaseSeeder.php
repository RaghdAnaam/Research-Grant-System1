<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Academician;
use App\Models\Grant;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'academician_id' => null,
            ],
        );

        $drAhmad = Academician::updateOrCreate(
            ['staff_number' => 'STF001'],
            [
                'name' => 'Dr Ahmad',
                'email' => 'leader@example.com',
                'college' => 'College of Computing',
                'department' => 'Artificial Intelligence',
                'position' => 'Senior Lecturer',
            ],
        );

        User::updateOrCreate(
            ['email' => 'leader@example.com'],
            [
                'name' => $drAhmad->name,
                'password' => Hash::make('password'),
                'role' => UserRole::Leader,
                'academician_id' => $drAhmad->id,
            ],
        );

        $drAli = Academician::updateOrCreate(
            ['staff_number' => 'STF002'],
            [
                'name' => 'Dr Ali',
                'email' => 'academic@example.com',
                'college' => 'College of Computing',
                'department' => 'Computer Science',
                'position' => 'Lecturer',
            ],
        );

        User::updateOrCreate(
            ['email' => 'academic@example.com'],
            [
                'name' => $drAli->name,
                'password' => Hash::make('password'),
                'role' => UserRole::Academic,
                'academician_id' => $drAli->id,
            ],
        );

        $drSara = Academician::updateOrCreate(
            ['staff_number' => 'STF003'],
            [
                'name' => 'Dr Sara',
                'email' => 'sara@example.com',
                'college' => 'College of Engineering',
                'department' => 'Smart Systems',
                'position' => 'Assoc Prof',
            ],
        );

        User::updateOrCreate(
            ['email' => 'sara@example.com'],
            [
                'name' => $drSara->name,
                'password' => Hash::make('password'),
                'role' => UserRole::Leader,
                'academician_id' => $drSara->id,
            ],
        );

        $aiResearch = Grant::updateOrCreate(
            ['project_title' => 'AI Research'],
            [
                'leader_id' => $drAhmad->id,
                'grant_provider' => 'University Internal Grant',
                'grant_amount' => 50000,
                'start_date' => now()->toDateString(),
                'duration_months' => 12,
            ],
        );
        $aiResearch->members()->sync([$drAli->id, $drSara->id]);

        $smartParking = Grant::updateOrCreate(
            ['project_title' => 'Smart Parking'],
            [
                'leader_id' => $drSara->id,
                'grant_provider' => 'City Innovation Fund',
                'grant_amount' => 30000,
                'start_date' => now()->addWeek()->toDateString(),
                'duration_months' => 8,
            ],
        );
        $smartParking->members()->sync([$drAli->id]);

        foreach ([
            ['grant_id' => $aiResearch->id, 'milestone_name' => 'Literature Review', 'target_completion_date' => now()->subWeek()->toDateString(), 'deliverable' => 'Completed literature review matrix', 'status' => 'Completed', 'remarks' => 'Completed'],
            ['grant_id' => $aiResearch->id, 'milestone_name' => 'Data Collection', 'target_completion_date' => now()->addMonth()->toDateString(), 'deliverable' => 'Collected research dataset', 'status' => 'Pending', 'remarks' => null],
            ['grant_id' => $aiResearch->id, 'milestone_name' => 'Final Report', 'target_completion_date' => now()->addMonths(6)->toDateString(), 'deliverable' => 'Final grant report', 'status' => 'Pending', 'remarks' => null],
            ['grant_id' => $smartParking->id, 'milestone_name' => 'Prototype Design', 'target_completion_date' => now()->addMonth()->toDateString(), 'deliverable' => 'Smart parking prototype design', 'status' => 'In Progress', 'remarks' => 'Hardware planning started'],
        ] as $milestone) {
            Milestone::updateOrCreate(
                [
                    'grant_id' => $milestone['grant_id'],
                    'milestone_name' => $milestone['milestone_name'],
                ],
                $milestone,
            );
        }
    }
}
