<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function atualizarPlano(int $id, string $nome, int $velocidadeMbps, float $valorMensal, int $diaVencimento): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'UPDATE planos SET nome = :nome, velocidade_mbps = :velocidade_mbps, valor_mensal = :valor_mensal, dia_vencimento = :dia_vencimento WHERE id = :id'
    );

    return $stmt->execute([
        'id' => $id,
        'nome' => $nome,
        'velocidade_mbps' => $velocidadeMbps,
        'valor_mensal' => $valorMensal,
        'dia_vencimento' => $diaVencimento,
    ]);
}
