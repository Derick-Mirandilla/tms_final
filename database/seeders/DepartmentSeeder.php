<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure departments are created or found, and store their IDs for categories
        $itSupport = Department::firstOrCreate(['department_name' => 'IT Support'], ['description' => 'Handles all IT-related issues.']);
        $humanResources = Department::firstOrCreate(['department_name' => 'Human Resources'], ['description' => 'Manages HR inquiries and employee relations.']);
        $facilities = Department::firstOrCreate(['department_name' => 'Facilities'], ['description' => 'Oversees building maintenance and services.']);
        $sales = Department::firstOrCreate(['department_name' => 'Sales'], ['description' => 'Manages sales inquiries and client outreach.']);

 
    }
}
