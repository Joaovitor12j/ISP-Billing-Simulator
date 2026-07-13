<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function excluirPlano(int $id): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('DELETE FROM planos WHERE id = :id');

    return $stmt->execute(['id' => $id]);
}
