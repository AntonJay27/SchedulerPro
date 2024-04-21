<?php

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            [
                'name' => 'CS Thesis 1',
                'subject_code' => 'CS 411',
                'lab' => 0
            ],
            [
                'name' => 'Software Engineering 2',
                'subject_code' => 'CS 412',
                'lab' => 0
            ],
            [
                'name' => 'Social Issues and Professional Practice 1',
                'subject_code' => 'CS 414',
                'lab' => 0
            ],
            [
                'name' => 'Text Mining and Information Retrieval',
                'subject_code' => 'CS PT4',
                'lab' => 0
            ],
            [
                'name' => 'Computer Architecture and Organization',
                'subject_code' => 'CS 413',
                'lab' => 0
            ]
        ]);
    }
}
