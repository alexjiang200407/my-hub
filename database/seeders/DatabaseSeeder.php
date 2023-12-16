<?php

namespace Database\Seeders;


use App\Models\Reminder;
use App\Models\Tag;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Reminder::factory()->create([
            "userId" => 1
        ]);

        Reminder::factory()->create([
            "userId" => 1
        ]);

        Tag::factory()->create([
            "reminderId" => 1,
            "tag" => "fart"
        ]);
        Tag::factory()->create([
            "reminderId" => 2,
            "tag" => "bb"
        ]);
        Tag::factory()->create([
            "reminderId" => 2,
            "tag" => "shit"
        ]);
    }
}
