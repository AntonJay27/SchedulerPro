<?php

use Illuminate\Database\Seeder;

class TimeslotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timeslots')->insert([
            [
                'time' => '07:30 - 08:30',
                'rank' => 1
            ],
            [
                'time' => '08:30 - 09:30',
                'rank' => 2
            ],
            [
                'time' => '09:30 - 10:30',
                'rank' => 3
            ],
            [
                'time' => '10:30 - 11:30',
                'rank' => 4
            ],
            [
                'time' => '01:00 - 02:00',
                'rank' => 6
            ],
            [
                'time' => '02:00 - 03:00',
                'rank' => 7
            ],
            [
                'time' => '03:00 - 04:00',
                'rank' => 8
            ],
            [
                'time' => '04:00 - 05:00',
                'rank' => 9
            ],
            [
                'time' => '05:00 - 06:00',
                'rank' => 10
            ]
        ]);
    }
}