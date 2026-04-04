<?php

namespace App\Service;

class UnidadeNumeracaoService
{
public function gerarNumeracao(string $entrada, int $qtdAndares, ?string $tipo = null): array
{
    if ($tipo === 'condominio') {
        return $this->gerarCondominio($entrada, $qtdAndares);
    }

    if ($tipo === 'padrao') {
        return $this->gerarPadrao($entrada, $qtdAndares);
    }

    if ($tipo === 'sequencial') {
        return $this->gerarSequencial($entrada, $qtdAndares);
    }

    // fallback automático
    if (str_contains($entrada, '/')) {
        return $this->gerarCondominio($entrada, $qtdAndares);
    }

    [$inicio, $fim] = array_map('intval', explode('-', $entrada));

    return $fim <= 9
        ? $this->gerarPadrao($entrada, $qtdAndares)
        : $this->gerarSequencial($entrada, $qtdAndares);
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
    private function gerarPadrao(string $entrada, int $qtdAndares): array
{
    $unidades = [];

    [$inicio, $fim] = array_map('intval', explode('-', $entrada));

    for ($andar = 0; $andar < $qtdAndares; $andar++) {
        for ($num = $inicio; $num <= $fim; $num++) {
            $numero = ($andar * 10) + $num;
            $unidades[] = str_pad((string)$numero, 2, '0', STR_PAD_LEFT);
        }
    }

    return $unidades;
}
private function gerarSequencial(string $entrada, int $qtdAndares): array
{
    $unidades = [];

    [$inicio, $fim] = array_map('intval', explode('-', $entrada));
    $totalPorAndar = ($fim - $inicio) + 1;

    for ($andar = 0; $andar < $qtdAndares; $andar++) {
        for ($num = $inicio; $num <= $fim; $num++) {
            $numero = ($andar * $totalPorAndar) + $num;
            $unidades[] = str_pad((string)$numero, 2, '0', STR_PAD_LEFT);
        }
    }

    return $unidades;
}
private function gerarCondominio(string $entrada, int $qtdAndares): array
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
        for ($num = $inicio; $num <= $fim; $num++) {

            if ($prefixo !== null) {
                // 🔥 com prefixo (SEU TESTE)
                $numero = ($prefixo * 100) + (($andar - 1) * 10) + $num;
            } else {
                // 🔥 padrão condomínio real (imagem)
                $numero = ($andar * 100) + $num;
            }

            $unidades[] = (string) $numero;
        }
    }

    return $unidades;
}
public function gerarNumeracaoCondominio(string $entrada, int $qtdAndares): array
{
    return $this->gerarCondominio($entrada, $qtdAndares);
}
}
