<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Category;
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
        User::factory(19)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::factory(4)->create();

        $questions = Question::factory(30)->create([
            'category_id' => fn() => $categories->random()->id, //the function will be executed for each created question
            'user_id' => fn() => User::inRandomOrder()->first()->id, //the function will be executed for each created question
        ]); //Different configuration cause of foreign keys

        $answers = Answer::factory(50)->create([
            'question_id' => fn() => $questions->random()->id, //is using $questions from above cause of foreign key and relations
            'user_id' => fn() => User::inRandomOrder()->first()->id, //the function will be executed for each created answer
        ]); //Different configuration cause of foreign keys

        Comment::factory(100)->create([
            'user_id' => fn() => User::inRandomOrder()->first()->id, //the function will be executed for each created comment
            'commentable_id' => fn() => $answers->random()->id, //this is because of polymorphic relation, so i need the commentable id being taken from answers
            'commentable_type' => Answer::class
        ]);

        Comment::factory(100)->create([
            'user_id' => fn() => User::inRandomOrder()->first()->id, //the function will be executed for each created comment
            'commentable_id' => fn() => $questions->random()->id, //this is because of polymorphic relation, so i need the commentable id being taken from questions
            'commentable_type'=> Question::class
        ]);
    }
}
