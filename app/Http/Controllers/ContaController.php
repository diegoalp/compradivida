<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conta;

class ContaController extends Controller
{
    public function index()
    {
        $saldo = Conta::find(1)->select('contas.saldo')->first();
        return json_encode($saldo);
    }
    public function update(Request $request, $id)
    {
        $saldo = Conta::find($id);
        $saldo->saldo = $request->input('saldo');
        $saldo->save();
        return response(200);
    }
}
