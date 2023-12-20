<?php

namespace Database\Seeders;


use App\Models\Post;
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
        \App\Models\User::factory(1)->create([
            "name"=> "fart",
            "email"=> "fart@gmail.com",
            "password"=> bcrypt("shitytitty"),
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Post::factory()->create([
            "userId" => 1
        ]);

        Post::factory()->create([
            "userId" => 1
        ]);

        Tag::factory()->create([
            "postId" => 1,
            "tag" => "fart"
        ]);
        Tag::factory()->create([
            "postId" => 2,
            "tag" => "bb"
        ]);
        Tag::factory()->create([
            "postId" => 2,
            "tag" => "shit"
        ]);
    }
}
