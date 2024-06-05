<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condominio extends Model
{
    use HasFactory;

    protected $fillable = ['nome','cnpj','endereco'];


    public function unidades(){
        return $this->hasMany(Unidade::class,'condominio_id','id');
    }

    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class);
    }
}
