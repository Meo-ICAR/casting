<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing data in the correct order
        \App\Models\Application::truncate();
        \App\Models\Profile::truncate();
        \App\Models\Role::truncate();
        \App\Models\Project::truncate();
        \App\Models\User::where('role', '!=', 'admin')->delete();

        // Re-enable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create or update director user
        $director = User::updateOrCreate(
            ['email' => 'director@example.com'],
            [
                'name' => 'Director',
                'last_name' => 'User',
                'password' => bcrypt('password'),
                'role' => 'director',
                'email_verified_at' => now(),
            ]
        );

        // Create actor users
        User::factory(10)->create([
            'role' => 'actor',
            'password' => bcrypt('password'),
        ]);

        // Call the CinemaCastingSeeder
        $this->call([
            CinemaCastingSeeder::class,
        ]);
    }
}
