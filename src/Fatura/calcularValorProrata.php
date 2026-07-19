<?php

declare(strict_types=1);

namespace App\Fatura;

function calcularValorProrata(float $valorMensal, int $diaCadastro, int $diaVencimento, int $diasNoMes): float
{
    if ($diaCadastro <= $diaVencimento) {
        return round($valorMensal, 2);
    }

    $diasProporcionais = $diasNoMes - $diaCadastro + 1;

    return round(($diasProporcionais / $diasNoMes) * $valorMensal, 2);
}
