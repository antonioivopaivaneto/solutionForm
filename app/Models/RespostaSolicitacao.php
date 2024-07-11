<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaSolicitacao extends Model
{
    use HasFactory;

    protected $table = 'resposta_solicitacoes';

    protected $fillable = ['user_id','descricao','solicitacao_id']; //

    public function fotos(){
        return $this->hasMany(FotoResposta::class,'resposta_id','id');
    }
    public function solicitacao(){
        return $this->belongsTo(Solicitacao::class,'');
    }
    public function user(){
        return $this->belongsTo(user::class,'user_id','id');
    }

}
