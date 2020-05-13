@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3>Nº {{ $id }}</h3>
                    {{-- <div class="float-right">Saldo: <saldo-component></saldo-component></div> --}}
                </div>

                <div class="card-body">
                    <proposta-component proposta='{{ $id }}'></proposta-component>
                </div>
                {{-- <div class="card-footer">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#novaPropostaModal">CADASTRAR</button>
                </div> --}}
            </div>  
        </div> 
    </div>
</div>
@endsection
