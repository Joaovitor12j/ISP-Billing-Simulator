<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function buscarPlanoPorId(int $id): ?array
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('SELECT id, nome, velocidade_mbps, valor_mensal, dia_vencimento FROM planos WHERE id = :id');
    $stmt->execute(['id' => $id]);

    $plano = $stmt->fetch();

    return $plano === false ? null : $plano;
}
