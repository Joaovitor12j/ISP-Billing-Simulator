<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function criarPlano(string $nome, int $velocidadeMbps, float $valorMensal, int $diaVencimento): int
{
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'INSERT INTO planos (nome, velocidade_mbps, valor_mensal, dia_vencimento) VALUES (:nome, :velocidade_mbps, :valor_mensal, :dia_vencimento)'
    );

    $stmt->execute([
        'nome' => $nome,
        'velocidade_mbps' => $velocidadeMbps,
        'valor_mensal' => $valorMensal,
        'dia_vencimento' => $diaVencimento,
    ]);

    return (int) $pdo->lastInsertId();
}
