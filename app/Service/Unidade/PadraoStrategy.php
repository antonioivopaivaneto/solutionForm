<?php

namespace App\Service\Unidade;


class PadraoStrategy implements NumeracaoStrategy
{
    public function gerar(string $entrada, int $qtdAndares): array
    {
        $unidades = [];
        [$inicio, $fim] = array_map('intval', explode('-', $entrada));

        for ($andar = 0; $andar < $qtdAndares; $andar++) {
            for ($num = $inicio; $num <= $fim; $num++) {
                $numero = ($andar * 10) + $num;
                $unidades[] = str_pad($numero, 2, '0', STR_PAD_LEFT);
            }
        }

        return $unidades;
    }
}
