<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'password' => bcrypt('SorSU-BC1970'),
            'name' => 'Administrator',
        ]);
    }
}