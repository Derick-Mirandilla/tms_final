<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\TicketStatus;
use App\Models\PriorityLevel; // Corrected model name
use App\Models\ActionType;
use Illuminate\Support\Facades\Hash;

class RolesAndInitialDataSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles and store their IDs
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'description' => 'Full system control.']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'description' => 'Ticket assignment and escalation.']);
        $agentLowRole = Role::firstOrCreate(['name' => 'agent_low', 'description' => 'Handles low priority tickets, can create tickets.']);
        $agentMediumRole = Role::firstOrCreate(['name' => 'agent_medium', 'description' => 'Handles low and medium priority tickets.']);

        // Store role IDs for easy access in constants or logic
        // Assuming default Spatie IDs 1, 2, 3, 4 for roles respectively
        // If you need specific constants:
        define('SUPER_ADMIN_ROLE_ID', $superAdminRole->id);
        define('MANAGER_ROLE_ID', $managerRole->id);
        define('AGENT_LOW_ROLE_ID', $agentLowRole->id);
        define('AGENT_MEDIUM_ROLE_ID', $agentMediumRole->id);


        // Create default users and assign roles
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '111-222-3333',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id, // Assign role_id directly
            ]
        );
        $superAdminUser->assignRole($superAdminRole); // Spatie assignment

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'first_name' => 'Support',
                'last_name' => 'Manager',
                'phone' => '444-555-6666',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
            ]
        );
        $managerUser->assignRole($managerRole);

        $agentLowUser = User::firstOrCreate(
            ['email' => 'agentlow@example.com'],
            [
                'first_name' => 'Agent',
                'last_name' => 'Low',
                'phone' => '777-888-9999',
                'password' => Hash::make('password'),
                'role_id' => $agentLowRole->id,
            ]
        );
        $agentLowUser->assignRole($agentLowRole);

        $agentMediumUser = User::firstOrCreate(
            ['email' => 'agentmedium@example.com'],
            [
                'first_name' => 'Agent',
                'last_name' => 'Medium',
                'phone' => '123-456-7890',
                'password' => Hash::make('password'),
                'role_id' => $agentMediumRole->id,
            ]
        );
        $agentMediumUser->assignRole($agentMediumRole);


        // Create Priority Levels
        PriorityLevel::firstOrCreate(['priority_name' => 'Low', 'description' => 'Minimal impact, non-urgent.']);
        PriorityLevel::firstOrCreate(['priority_name' => 'Medium', 'description' => 'Moderate impact, requires attention.']);
        PriorityLevel::firstOrCreate(['priority_name' => 'High', 'description' => 'Significant impact, urgent.']);

        // Create Action Types
        ActionType::firstOrCreate(['type_name' => 'Created', 'description' => 'Ticket created.']);
        ActionType::firstOrCreate(['type_name' => 'Assigned', 'description' => 'Ticket assigned to an agent/manager.']);
        ActionType::firstOrCreate(['type_name' => 'Status Changed', 'description' => 'Ticket status updated.']);
        ActionType::firstOrCreate(['type_name' => 'Priority Changed', 'description' => 'Ticket priority updated.']);
        ActionType::firstOrCreate(['type_name' => 'Comment Added', 'description' => 'A comment was added to the ticket.']);
        ActionType::firstOrCreate(['type_name' => 'Resolved', 'description' => 'Ticket marked as resolved.']);
        ActionType::firstOrCreate(['type_name' => 'Closed', 'description' => 'Ticket marked as closed.']);
        ActionType::firstOrCreate(['type_name' => 'Deleted', 'description' => 'Ticket deleted.']); // For audit trail
    }
}
