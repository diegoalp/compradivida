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

    public function comissoes(){
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;
        $propostasMes = Proposta::where('status',4)
        ->whereMonth('data_finalizacao_boleto', '=',$mesAtual)
        ->whereYear('data_finalizacao_boleto', '=',$anoAtual)
        ->get();
        return json_encode($propostasMes);

    }
    public function comissoesVendedor($vendedor,$mes){
        $data = explode('-', $mes);
        $mesAtual = $data[1];
        $anoAtual = $data[0];
        if($vendedor !== "GERAL"){
            $propostasVendedor = Proposta::where('status','=',4)
            ->where('nome_vendedor', '=', mb_strtoupper($vendedor))
            ->whereMonth('data_finalizacao_boleto', '=',$mesAtual)
            ->whereYear('data_finalizacao_boleto', '=',$anoAtual)
            ->get();
        }else{
            $propostasVendedor = Proposta::where('status','=',4)
            ->whereMonth('data_finalizacao_boleto', '=',$mesAtual)
            ->whereYear('data_finalizacao_boleto', '=',$anoAtual)
            ->get();
        }
        
        return json_encode($propostasVendedor);

    }
    public function comissoesEscritorio(){
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;
        $propostas = Proposta::where('status',4)
        ->whereMonth('data_finalizacao_boleto', '=',$mesAtual)
        ->whereYear('data_finalizacao_boleto', '=',$anoAtual)
        ->get();
        $rendimento = 0;
        $comissaoGeral = 0;
        $comissaoEscritorio = 0;
        $comissoesVendedores = 0;
        foreach ($propostas as $key => $value){
            $rendimento += $value['rendimento'];
            $comissaoEscritorio += $value['comissao_escritorio'];
            $comissaoGeral += $value['comissao_total'];
            $comissoesVendedores += $value['comissao_vendedor'];
        }
        return response()->json([
            'rendimento' => $rendimento,
            'comissao_total' => $comissaoGeral,
            'comissao_escritorio' => $comissaoEscritorio,
            'comissoes_vendedor' => $comissoesVendedores,
        ]);

    }
    public function store(Request $request){
            
            $numero = Proposta::orderBy('id', 'desc')->first();
            $numero = $numero->id + 1;
            $proposta = new Proposta();
            $proposta->protocolo = "CD-".str_pad($numero , 3 , '0' , STR_PAD_LEFT);
            $proposta->nome = mb_strtoupper($request->input('nome'));
            $proposta->nome_vendedor = mb_strtoupper($request->input('nome_vendedor'));
            $proposta->agencia_quitacao = mb_strtoupper($request->input('agencia_quitacao'));
            $proposta->telefone = $request->input('telefone');
            $proposta->cpf = $request->input('cpf');
            $proposta->banco_boleto = $request->input('banco_boleto');
            $proposta->banco_quitacao = $request->input('banco_quitacao');
            
            // $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
            // $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
            // $proposta->comissao_vendedor = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_vendedor'))));
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
        
        if($request->input('status') == 2){
            $proposta->data_vencimento_boleto = $request->input('data_vencimento_boleto');
            if($request->input('valor_boleto') !== "R$ 0,00"){
                $proposta->valor_boleto = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('valor_boleto'))));
                $proposta->rendimento = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('rendimento'))));
            }
            if($request->input('comissao_total') !== "R$ 0,00"){
                $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
                $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
                $proposta->comissao_vendedor = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_vendedor'))));
            }
            $proposta->status = $request->input('status');
            $proposta->save();

        }
        elseif($request->input('status') == 3){
            if($saldo->saldo >= $proposta->valor_boleto){
                $saldo->saldo = $saldo->saldo - $proposta->valor_boleto;
                $saldo->save();

                if($request->input('comissao_total') !== "R$ 0,00"){
                    $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
                    $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
                    $proposta->comissao_vendedor = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_vendedor'))));
                }
                $proposta->status = $request->input('status');
                $proposta->data_quitacao_boleto = $request->input('data_quitacao');
                $proposta->save();
                return response(200);
            }else{
                return error();
            }
        }elseif($request->input('status') == 4){
            if($request->input('comissao_total') !== "R$ 0,00"){
                $proposta->comissao_total = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_total'))));
                $proposta->comissao_escritorio = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_escritorio'))));
                $proposta->comissao_vendedor = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('comissao_vendedor'))));
            }
            $proposta->status = $request->input('status');
            $proposta->data_finalizacao_boleto = $request->input('data_finalizacao');
            if($request->input('valor_boleto') !== null && $request->input('valor_boleto') !== "R$ 0,00"){
                $proposta->valor_boleto = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('valor_boleto'))));
                $proposta->rendimento = str_replace('R$', '',str_replace(',', '.', str_replace('.', '', $request->input('rendimento'))));
                $proposta->data_vencimento_boleto = $request->input('data_vencimento_boleto');
            }
            $proposta->save();
            $saldo->saldo = $saldo->saldo + $proposta->valor_boleto + $proposta->rendimento;
            $saldo->save();
            return response(200);
        }else{
            $proposta->status = $request->input('status');
            $proposta->save();
            return response(200);
        }
    }
    public function destroy($id)
    {
        $proposta = Proposta::find($id);
        if (isset($proposta)) {
            $proposta->delete();
            return response('OK', 200);
        }
        return response('Proposta não encontrada', 404);
    }
}
