<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = "solicitacoes";

    protected $fillable = [
        'assunto',
        'solicitacao',
        'condominio_id',
        'unidade_id',
        'nome',
        'email',
        'foto',
        'status',
        'proprietario',
        'telefone',
        'local',
    ];

    public function fotos(){
        return $this->hasMany(Foto::class,'solicitacao_id','id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    public function condominio()
    {
        return $this->belongsTo(Condominio::class);
    }


}
