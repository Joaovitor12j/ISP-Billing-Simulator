<?php

declare(strict_types=1);

namespace App\Cliente;

function validarCpf(string $cpf): bool
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf) === 1) {
        return false;
    }

    for ($posicaoVerificador = 9; $posicaoVerificador <= 10; $posicaoVerificador++) {
        $soma = 0;

        for ($posicao = 0; $posicao < $posicaoVerificador; $posicao++) {
            $soma += (int) $cpf[$posicao] * ($posicaoVerificador + 1 - $posicao);
        }

        $digitoVerificador = ($soma * 10) % 11;

        if ($digitoVerificador === 10) {
            $digitoVerificador = 0;
        }

        if ($digitoVerificador !== (int) $cpf[$posicaoVerificador]) {
            return false;
        }
    }

    return true;
}
