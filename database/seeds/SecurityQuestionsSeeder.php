<?php

use Illuminate\Database\Seeder;

class SecurityQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('security_questions')
            ->insert([
                ["question" => "What is your favorite book or movie?"],
                ["question" => "What is the name of the street you grew up on?"],
                ["question" => "What is your favorite food?"],
                ["question" => "What is the name of your childhood best friend?"],
                ["question" => "What is your mother's maiden name?"]
            ]);
    }
}
