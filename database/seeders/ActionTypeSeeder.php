<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActionType;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actionTypes = [
            ['type_name' => 'Ticket Created', 'description' => 'A new ticket was created.'],
            ['type_name' => 'Ticket Updated', 'description' => 'General ticket details were updated (subject, description, customer, category).'],
            ['type_name' => 'Status Change', 'description' => 'The status of the ticket was changed.'],
            ['type_name' => 'Priority Change', 'description' => 'The priority level of the ticket was changed.'],
            ['type_name' => 'Assignment Change', 'description' => 'The assigned agent for the ticket was changed.'],
            ['type_name' => 'Comment Added', 'description' => 'A new comment was added to the ticket.'],
            ['type_name' => 'Ticket Deleted', 'description' => 'The ticket was deleted.'],
        ];

        foreach ($actionTypes as $typeData) {
            ActionType::firstOrCreate(
                ['type_name' => $typeData['type_name']],
                ['description' => $typeData['description']]
            );
        }
    }
}
