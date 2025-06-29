<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndInitialDataSeeder::class);
        $this->call(DepartmentSeeder::class); 
        $this->call(CategorySeeder::class);   
        $this->call(TicketStatusSeeder::class);
        $this->call(ActionTypeSeeder::class); 
    }
}
