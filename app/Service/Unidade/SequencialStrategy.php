<?php
namespace App\Service\Unidade;


class SequencialStrategy implements NumeracaoStrategy
{
    public function gerar(string $entrada, int $qtdAndares): array
    {
        $unidades = [];

        [$inicio, $fim] = array_map('intval', explode('-', $entrada));
        $totalPorAndar = ($fim - $inicio) + 1;

        for ($andar = 0; $andar < $qtdAndares; $andar++) {
            for ($num = $inicio; $num <= $fim; $num++) {
                $numero = ($andar * $totalPorAndar) + $num;
                $unidades[] = str_pad($numero, 2, '0', STR_PAD_LEFT);
            }
        }

        return $unidades;
    }
}
