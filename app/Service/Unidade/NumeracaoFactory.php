<?php

namespace App\Service\Unidade;


class NumeracaoFactory
{
    public static function make(?string $tipo, string $entrada): NumeracaoStrategy
    {
        return match ($tipo) {
            'padrao' => new PadraoStrategy(),
            'sequencial' => new SequencialStrategy(),
            'condominio' => new CondominioStrategy(),

            default => self::autoDetect($entrada),
        };
    }

    private static function autoDetect(string $entrada): NumeracaoStrategy
    {
        if (str_contains($entrada, '/')) {
            return new CondominioStrategy();
        }

        [$inicio, $fim] = array_map('intval', explode('-', $entrada));

        return $fim <= 9
            ? new PadraoStrategy()
            : new SequencialStrategy();
    }
}
