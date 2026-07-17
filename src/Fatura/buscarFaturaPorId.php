<?php

declare(strict_types=1);

namespace App\Fatura;

use function App\Database\getConnection;

function buscarFaturaPorId(int $id): ?array
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('SELECT id, cliente_id, competencia, valor, data_vencimento, status FROM faturas WHERE id = :id');
    $stmt->execute(['id' => $id]);

    $fatura = $stmt->fetch();

    return $fatura === false ? null : $fatura;
}
