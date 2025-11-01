<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Status;

class UserAndStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles if they don't exist
        $roles = [
            'super_admin',
            'admin',
            'agent',
            'user'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create default statuses if they don't exist
        $statuses = [
            'Open',
            'In Progress',
            'Pending',
            'Closed'
        ];

        foreach ($statuses as $statusName) {
            Status::firstOrCreate(['name' => $statusName]);
        }

        $this->command->info('Default roles and statuses created successfully!');
    }
}