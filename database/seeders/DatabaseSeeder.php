<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            TournementSeeder::class,
            RoundSeeder::class,
            GameSeeder::class,
            PouleSeeder::class,
            TeamSeeder::class,
            PitchSeeder::class,
            UserSeeder::class,
            TournementuserSeeder::class
        ]);
    }
}
