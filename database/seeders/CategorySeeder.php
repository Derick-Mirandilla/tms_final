<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Department; // To retrieve department IDs

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve department IDs
        $itSupportDept = Department::where('department_name', 'IT Support')->firstOrFail();
        $humanResourcesDept = Department::where('department_name', 'Human Resources')->firstOrFail();
        $facilitiesDept = Department::where('department_name', 'Facilities')->firstOrFail();
        $salesDept = Department::where('department_name', 'Sales')->firstOrFail();

        // IT Support Categories
        Category::firstOrCreate(
            ['category_name' => 'Software Bug', 'department_id' => $itSupportDept->department_id],
            ['description' => 'Issues with application functionality or errors.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Hardware Issue', 'department_id' => $itSupportDept->department_id],
            ['description' => 'Problems with physical computer components or peripherals.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Network Connectivity', 'department_id' => $itSupportDept->department_id],
            ['description' => 'Problems connecting to the internet or internal network.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Account Access', 'department_id' => $itSupportDept->department_id],
            ['description' => 'Password resets, lockout, or login issues.']
        );

        // Human Resources Categories
        Category::firstOrCreate(
            ['category_name' => 'Payroll Inquiry', 'department_id' => $humanResourcesDept->department_id],
            ['description' => 'Questions or issues related to salary and payments.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Leave Request', 'department_id' => $humanResourcesDept->department_id],
            ['description' => 'Requests for vacation, sick leave, etc.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Benefits Question', 'department_id' => $humanResourcesDept->department_id],
            ['description' => 'Inquiries about employee benefits.']
        );

        // Facilities Categories
        Category::firstOrCreate(
            ['category_name' => 'Maintenance Request', 'department_id' => $facilitiesDept->department_id],
            ['description' => 'Requests for repair or maintenance of facilities.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Supplies Order', 'department_id' => $facilitiesDept->department_id],
            ['description' => 'Requests for office supplies.']
        );

        // Sales Categories
        Category::firstOrCreate(
            ['category_name' => 'New Lead Inquiry', 'department_id' => $salesDept->department_id],
            ['description' => 'Inquiries from potential new customers.']
        );
        Category::firstOrCreate(
            ['category_name' => 'Product Information', 'department_id' => $salesDept->department_id],
            ['description' => 'Requests for detailed product specifications.']
        );
    }
}
