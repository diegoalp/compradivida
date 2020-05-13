<?php

use Illuminate\Database\Seeder;
use App\Conta;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conta = Conta::create([
            'saldo' => 0,
        ]);
    }
}
