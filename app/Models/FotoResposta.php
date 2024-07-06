<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoResposta extends Model
{
    use HasFactory;

    protected $fillable = ['foto','resposta_id'];

    public function resposta(){
        return $this->belongsTo(RespostaSolicitacao::class);
    }

}
