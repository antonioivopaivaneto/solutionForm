<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = ['nome','bloco','torre','andar'];

    public function condominio(){
        return $this->belongsTo(Condominio::class);
    }

     public function solicitacoes()
     {
         return $this->hasMany(Solicitacao::class);
     }

}
