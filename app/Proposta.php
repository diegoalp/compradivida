<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at','data_vencimento_boleto','data_quitacao_boleto'];
}
