<?php

namespace App\service;

use App\Models\Condominio;
use Illuminate\Support\Facades\DB;

class DashboardService {


    public function getDadosFiltros()
    {
        $condominios= Condominio::select('id', 'nome')->get();

        $assuntos = DB::table('solicitacoes')
        ->pluck('assunto')
        ->unique();

        $locais = DB::table('solicitacoes')
        ->pluck('local')
        ->unique();

        $data = [
            'condominios' => $condominios,
            'assuntos' => $assuntos,
            'locais' => $locais,
        ];

        return $data;
    }
}


