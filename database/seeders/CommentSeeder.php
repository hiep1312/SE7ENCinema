<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');
        $movies = Movie::pluck('id');

        $topLevelComments = [];

        // Tạo 20 bình luận gốc (top-level)
        foreach (range(1, 25) as $i) {
            $comment = Comment::create([
                'user_id' => $users->random(),
                'movie_id' => $movies->random(),
                'parent_comment_id' => null,
                'reply_comment_id' => null,
                'content' => fake()->paragraph(),
                'status' => fake()->randomElement(['active', 'hidden', 'reported', 'deleted']),
            ]);
            $topLevelComments[] = $comment;
        }

        foreach (Arr::shuffle($topLevelComments) as $top) {
            $previous = null;

            foreach (range(1, 5) as $depth) {
                $reply = Comment::create([
                    'user_id' => $users->random(),
                    'movie_id' => $top->movie_id,
                    'parent_comment_id' => $top->id,
                    'reply_comment_id' => $previous?->id ?? $top->id,
                    'content' => fake()->sentence(15),
                    'status' => fake()->randomElement(['active', 'hidden', 'reported', 'deleted']),
                ]);
                $previous = $reply;
            }
        }
    }
}
