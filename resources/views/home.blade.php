@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">PROPOSTAS
                    
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <propostas-component></propostas-component>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#novaPropostaModal">CADASTRAR</button>
                </div>
            </div>  
        </div> 
    </div>
</div>
@endsection
