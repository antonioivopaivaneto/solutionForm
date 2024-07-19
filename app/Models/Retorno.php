<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retorno extends Model
{
    use HasFactory;
    protected $fillable = ['canal','data','descricao','solicitacao_id','user_id'];

    public function solicitacao(){
        return $this->belongsTo(Solicitacao::class,'');
    }
    public function user(){
        return $this->belongsTo(user::class,'user_id','id');
    }

}
