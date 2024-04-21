<?php

use Illuminate\Database\Seeder;

class ProfessorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('professors')->insert([
            [
                'name' => 'J. Prado'
            ],
            [
                'name' => 'N. Dioneda'
            ],
            [
                'name' => 'JM. Dino'
            ],
            [
                'name' => 'M. Bausa'
            ],
            [
                'name' => 'DJ. Cayetano'
            ]
        ]);
    }
}
