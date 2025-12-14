<?php

namespace App\Service;

class UnidadeNumeracaoService
{
    public function gerarNumeracao(string $entrada, int $qtdAndares): array
    {
        $unidades = [];

        $prefixo = 0;
        $faixa = $entrada;

        // Ex: 10/01-04
        if (str_contains($entrada, '/')) {
            [$prefixo, $faixa] = explode('/', $entrada);
            $prefixo = (int) trim($prefixo);
        }

        [$inicio, $fim] = array_map('intval', explode('-', $faixa));

        for ($andar = 0; $andar < $qtdAndares; $andar++) {
            for ($num = $inicio; $num <= $fim; $num++) {
                $numero =
                    ($prefixo * 100) +
                    ($andar * 10) +
                    $num;

                $unidades[] = str_pad((string)$numero, 2, '0', STR_PAD_LEFT);
            }
        }

        return $unidades;
    }

    public function extrairAndar(string $numeroCompleto, int $prefixo = 0): int
    {
        if ($prefixo === 0) {
            // Ex: 13 → andar = 1
            return (int) substr($numeroCompleto, 0, 1);
        }

        // Ex: 1014 com prefixo 10 → remove "10" → 14 → andar = 1
        $restante = substr($numeroCompleto, strlen((string)$prefixo));

        return (int) substr($restante, 0, 1);
    }
}
