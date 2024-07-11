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
    public function solicitacoesAbertas()
    {
        return $this->hasMany(Solicitacao::class)->where('status', 0);
    }
    public function solicitacoesAndamento()
    {
        return $this->hasMany(Solicitacao::class)->where('status', 2);
    }
    public function solicitacoesFechada()
    {
        return $this->hasMany(Solicitacao::class)->where('status', 1);
    }
}
