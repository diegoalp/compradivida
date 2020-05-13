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
        
        
    }

    public function update(Request $request, $id){
        
        $proposta = Proposta::find($id);
        $saldo = Conta::find(1);
        
        if($request->input('status') == 3){
            if($saldo->saldo >= $proposta->valor_boleto){
                $saldo->saldo = $saldo->saldo - $proposta->valor_boleto;
                $saldo->save();

                $proposta->status = $request->input('status');
                $proposta->data_quitacao_boleto = $request->input('data_quitacao');
                $proposta->save();
                return response(200);
            }else{
                return error();
            }
        }elseif($request->input('status') == 4){
            $saldo->saldo = $saldo->saldo + $proposta->valor_boleto + $proposta->rendimento;
            $saldo->save();
            $proposta->status = $request->input('status');
            $proposta->save();
            return response(200);
        }else{
            $proposta->status = $request->input('status');
            $proposta->save();
            return response(200);
        }
        
        
        
    }
}
