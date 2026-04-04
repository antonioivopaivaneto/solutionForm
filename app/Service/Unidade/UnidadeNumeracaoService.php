<?php

namespace App\Service\Unidade;


class UnidadeNumeracaoService
{
    public function gerarNumeracao(string $entrada, int $qtdAndares, ?string $tipo = null): array
    {
        $strategy = NumeracaoFactory::make($tipo, $entrada);

        return $strategy->gerar($entrada, $qtdAndares);
    }

    public function extrairAndar(string $numeroCompleto, string $entrada): int
{
    // detecta prefixo automaticamente
    $prefixo = null;

    if (str_contains($entrada, '/')) {
        [$prefixo] = explode('/', $entrada);
        $prefixo = trim($prefixo);
    }

    if ($prefixo === null) {
        // ex: 101 → andar 1
        return (int) substr($numeroCompleto, 0, 1);
    }

    // ex: 1014 com prefixo 10 → remove prefixo
    $restante = substr($numeroCompleto, strlen($prefixo));

    return (int) substr($restante, 0, 1);
}
}
