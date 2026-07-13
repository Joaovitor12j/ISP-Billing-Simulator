<?php

declare(strict_types=1);

namespace App\Plano;

function validarCamposPlano(string $nome, ?int $velocidadeMbps, ?float $valorMensal, ?int $diaVencimento): array
{
    $erros = [];

    if (trim($nome) === '') {
        $erros[] = 'Nome é obrigatório';
    }

    if ($velocidadeMbps === null || $velocidadeMbps <= 0) {
        $erros[] = 'Velocidade (Mbps) deve ser um número inteiro positivo';
    }

    if ($valorMensal === null || $valorMensal <= 0) {
        $erros[] = 'Valor mensal deve ser um número positivo';
    }

    if ($diaVencimento === null || $diaVencimento < 1 || $diaVencimento > 31) {
        $erros[] = 'Dia de vencimento deve estar entre 1 e 31';
    }

    return $erros;
}
