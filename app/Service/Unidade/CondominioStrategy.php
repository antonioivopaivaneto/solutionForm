<?php
namespace App\Service\Unidade;


class CondominioStrategy implements NumeracaoStrategy
{
    public function gerar(string $entrada, int $qtdAndares): array
    {
        $unidades = [];

        $prefixo = null;
        $faixa = $entrada;

        if (str_contains($entrada, '/')) {
            [$prefixo, $faixa] = explode('/', $entrada);
            $prefixo = (int) trim($prefixo);
        }

        [$inicio, $fim] = array_map('intval', explode('-', $faixa));

        for ($andar = 1; $andar <= $qtdAndares; $andar++) {

            $fimAtual = ($andar === 1 && $prefixo === null)
                ? $fim
                : $fim;

            for ($num = $inicio; $num <= $fimAtual; $num++) {

                if ($prefixo !== null) {
                    $numero = ($prefixo * 100) + (($andar - 1) * 10) + $num;
                } else {
                    $numero = ($andar * 100) + $num;
                }

                $unidades[] = (string) $numero;
            }
        }

        return $unidades;
    }
}
