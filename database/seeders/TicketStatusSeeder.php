<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define all the required statuses
        $statuses = [
            ['status_name' => 'Open', 'description' => 'Ticket has been created and is awaiting assignment/attention.'],
            ['status_name' => 'In Progress', 'description' => 'Ticket is actively being worked on by an assigned agent.'],
            ['status_name' => 'Escalated', 'description' => 'Ticket has been escalated to a higher level of support.'],
            // Removed 'Pending' as per your request
            ['status_name' => 'Resolved', 'description' => 'Issue has been resolved, awaiting customer confirmation/closure.'],
            ['status_name' => 'Closed', 'description' => 'Ticket has been closed after resolution confirmation or due to inactivity.'],
            ['status_name' => 'Reopened', 'description' => 'A previously resolved/closed ticket has been reopened by the customer or agent.'],
        ];

        foreach ($statuses as $statusData) {
            TicketStatus::firstOrCreate(
                ['status_name' => $statusData['status_name']],
                ['description' => $statusData['description']]
            );
        }
    }
}