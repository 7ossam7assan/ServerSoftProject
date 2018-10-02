<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name' => 'hossame',
            'email' => 'h3@h.com',

            'password' => bcrypt('12345'),

        ]);
    }
}
