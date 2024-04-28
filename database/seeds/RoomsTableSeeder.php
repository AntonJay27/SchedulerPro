<?php

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            [
                'name' => 'CCB LAB 1', 'lab' => 0
            ],
            [
                'name' => 'CCB LAB 2', 'lab' => 0
            ],
            [
                'name' => 'CCB LAB 3', 'lab' => 0
            ],
            [
                'name' => 'CCB LAB 4', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 1', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 2', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 4', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 5', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 6', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 7', 'lab' => 0
            ],
            [
                'name' => 'CCB RM 8', 'lab' => 0
            ]
        ]);
    }
}
