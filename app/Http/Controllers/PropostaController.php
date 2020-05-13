<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proposta;
use App\Conta;

class PropostaController extends Controller
{
    public function index()
    {
        $propostas = Proposta::all();
        return json_encode($propostas);
    }
    public function store(Request $request){

        $saldo = Conta::find(1);
        $valor_boleto = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('valor_boleto'))));
        
        if($saldo->saldo >= $valor_boleto){
            
            $saldo->saldo = $saldo->saldo - $valor_boleto;
            $saldo->save();
            
            $numero = Proposta::count()+1;

            $proposta = new Proposta();
            $proposta->protocolo = "CD-".str_pad($numero , 3 , '0' , STR_PAD_LEFT);
            $proposta->nome = mb_strtoupper($request->input('nome'));
            $proposta->nome_vendedor = mb_strtoupper($request->input('nome_vendedor'));
            $proposta->telefone = $request->input('telefone');
            $proposta->cpf = $request->input('cpf');
            $proposta->data_vencimento_boleto = $request->input('data_vencimento_boleto');
            $proposta->banco_boleto = $request->input('banco_boleto');
            $proposta->valor_boleto = $valor_boleto;
            $proposta->rendimento = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('rendimento'))));
            $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
            $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
            $proposta->status = 1;
            $proposta->save();

            return response(200);
        }else{
            return error();
        }
        
        
    }

    public function update(Request $request, $id){
        $proposta = Proposta::find($id);
        $proposta->status = $request->input('status');
        if($request->input('data_quitacao') ==! 0){
            $proposta->data_quitacao_boleto = $request->input('data_quitacao');
        }
        $proposta->save();
        return json_encode($proposta);
    }
}
