<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        // Kader Kuta Kidul
        User::factory()->create([
            'name' => 'Kader Kuta Kidul',
            'email' => 'dskutakidul02@gmail.com',
            'password' => bcrypt('@kutakidul09!'),
            'role' => 'KADER',
        ]);

        // Kader Kuta Lor
        User::factory()->create([
            'name' => 'Kader Kuta Lor',
            'email' => 'dskutalor01@gmail.com',
            'password' => bcrypt('@kutalor77!'),
            'role' => 'KADER',
        ]);

        // Kader Duku Pete
        User::factory()->create([
            'name' => 'Kader Duku Pete',
            'email' => 'dsdukupete04@gmail.com',
            'password' => bcrypt('@dukupete32!'),
            'role' => 'KADER',
        ]);

        // Kader Salaganggeng
        User::factory()->create([
            'name' => 'Kader Salaganggeng',
            'email' => 'dsSalaganggeng03@gmail.com',
            'password' => bcrypt('@Salaganggeng64!'),
            'role' => 'KADER',
        ]);
    }
}
