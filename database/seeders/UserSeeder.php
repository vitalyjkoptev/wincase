<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Boss — full control, dashboard, finances, workers
        User::updateOrCreate(
            ['email' => 'boss@wincase.eu'],
            [
                'name' => 'Director WinCase',
                'password' => Hash::make('WinCase2026'),
                'role' => 'boss',
                'status' => 'active',
                'phone' => '+48 579 266 493',
                'department' => 'Management',
            ]
        );

        // Staff — clients, cases, tasks, calendar, documents
        User::updateOrCreate(
            ['email' => 'staff@wincase.eu'],
            [
                'name' => 'Anna Kowalska',
                'password' => Hash::make('Worker2026'),
                'role' => 'staff',
                'status' => 'active',
                'phone' => '+48 500 100 200',
                'department' => 'Immigration',
            ]
        );

        // User / Client — my cases, documents, messages
        User::updateOrCreate(
            ['email' => 'client@wincase.eu'],
            [
                'name' => 'Ivan Petrov',
                'password' => Hash::make('Client2026'),
                'role' => 'user',
                'status' => 'active',
                'phone' => '+48 500 200 300',
            ]
        );
    }
}
