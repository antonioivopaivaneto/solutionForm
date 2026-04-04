<?php

namespace App\Service\Unidade;

interface NumeracaoStrategy
{
    public function gerar(string $entrada, int $qtdAndares): array;
}
