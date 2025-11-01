<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Technical Support',
                'description' => 'Handles technical issues, software problems, and hardware troubleshooting',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Service',
                'description' => 'Manages customer inquiries, billing questions, and general support',
                'is_active' => true,
            ],
            [
                'name' => 'Sales',
                'description' => 'Assists with product inquiries, pricing, and new customer onboarding',
                'is_active' => true,
            ],
            [
                'name' => 'IT Infrastructure',
                'description' => 'Manages server issues, network problems, and system maintenance',
                'is_active' => true,
            ],
            [
                'name' => 'Product Development',
                'description' => 'Handles bug reports, feature requests, and product feedback',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Default departments created successfully!');
    }
}
