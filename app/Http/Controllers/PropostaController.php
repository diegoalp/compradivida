<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proposta;
use App\Conta;
use Carbon\Carbon;

class PropostaController extends Controller
{
    public function index()
    {
        $propostas = Proposta::all();
        return json_encode($propostas);
    }

    public function comissoes($vendedor){
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;
        $propostas = Proposta::where('nome_vendedor', "=", $vendedor)
        ->whereMonth('data_quitacao_boleto', '=',$mesAtual)
        ->whereYear('data_quitacao_boleto', '=',$anoAtual)
        ->get();
        $comissaoVendedor = 0;
        foreach ($propostas as $key => $value){
            $comissaoVendedor += $value['comissao_vendedor'];
        }
        dd($comissaoVendedor);

    }
    public function store(Request $request){
            
            $numero = Proposta::count()+1;

            $proposta = new Proposta();
            $proposta->protocolo = "CD-".str_pad($numero , 3 , '0' , STR_PAD_LEFT);
            $proposta->nome = mb_strtoupper($request->input('nome'));
            $proposta->nome_vendedor = mb_strtoupper($request->input('nome_vendedor'));
            $proposta->agencia_quitacao = mb_strtoupper($request->input('agencia_quitacao'));
            $proposta->telefone = $request->input('telefone');
            $proposta->cpf = $request->input('cpf');
            $proposta->data_vencimento_boleto = $request->input('data_vencimento_boleto');
            $proposta->banco_boleto = $request->input('banco_boleto');
            $proposta->banco_quitacao = $request->input('banco_quitacao');
            $proposta->valor_boleto = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('valor_boleto'))));
            $proposta->rendimento = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('rendimento'))));
            $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
            $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
            $proposta->comissao_vendedor = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_vendedor'))));
            $proposta->status = 1;
            $proposta->save();

            return response(200);
        
    }
    public function show($id){
        $proposta = Proposta::find($id)->first();
        return view('proposta', compact('proposta'));
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
            $dataAtual = Carbon::now();
            $saldo->saldo = $saldo->saldo + $proposta->valor_boleto + $proposta->rendimento;
            $saldo->save();
            $proposta->status = $request->input('status');
            $proposta->data_finalizacao_boleto = $dataAtual;
            $proposta->save();
            return response(200);
        }else{
            $proposta->status = $request->input('status');
            $proposta->save();
            return response(200);
        }
        
        
        
    }
}
