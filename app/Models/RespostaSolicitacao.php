<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaSolicitacao extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','descricao']; //

    public function fotos(){
        return $this->hasMany(FotoResposta::class,'resposta_id','id');
    }

}
