<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creates a default administrator so the app is usable right after
        // migrating. Change the password immediately in a real deployment.
        User::firstOrCreate(
            ['email' => 'admintoko@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('Toko112233'),
                'roles' => 'admin',
                'photo' => '',
                'email_verified_at' => now(),
            ]
        );
    }
}
