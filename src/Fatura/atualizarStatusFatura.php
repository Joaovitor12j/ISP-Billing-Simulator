<?php

declare(strict_types=1);

namespace App\Fatura;

use function App\Database\getConnection;

function atualizarStatusFatura(int $id, string $status): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('UPDATE faturas SET status = :status WHERE id = :id');

    return $stmt->execute(['id' => $id, 'status' => $status]);
}
