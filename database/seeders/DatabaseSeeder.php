<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
         'name' => 'Test User',
         'email' => 'admin@example.com',
         'password' =>  bcrypt('123456789'),
         'role' => 'admin',

        ]);

        User::create([
            'name' => 'Manutence User',
            'email' => 'manutencao@example.com',
            'password' => bcrypt('123456789'),
            'role' => 'manutence',
        ]);
    }
}
