<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function listarPlanos(): array
{
    $pdo = getConnection();

    $stmt = $pdo->query('SELECT id, nome, velocidade_mbps, valor_mensal, dia_vencimento FROM planos ORDER BY nome');

    return $stmt->fetchAll();
}
