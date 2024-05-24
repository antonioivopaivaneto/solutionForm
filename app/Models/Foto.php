<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;


    protected $fillable = ['foto','solicitacao_id'];

    public function solicitacao(){
        return $this->belongsTo(Solicitacao::class);
    }
}
