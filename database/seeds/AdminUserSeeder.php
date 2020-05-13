<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Diêgo Almeida Pessoa', 
            'email' => 'diegoalp@gmail.com',
            'password' => bcrypt('09021988'),
        ]);

        $user = User::create([
        	'name' => 'FERNANDO JUNIOR', 
            'email' => 'juninho_hau@hotmail.com',
            'password' => bcrypt('julia290808'),
        ]);
    }
}
