<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Create the 5 new users
        $users = [
            ['name' => 'Abimanyu', 'email' => 'abimanyu@binus.ac.id'],
            ['name' => 'Christian', 'email' => 'christian@binus.ac.id'],
            ['name' => 'Bagas', 'email' => 'bagas@binus.ac.id'],
            ['name' => 'Bryan', 'email' => 'bryan@binus.ac.id'],
            ['name' => 'Jason', 'email' => 'jason@binus.ac.id'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
