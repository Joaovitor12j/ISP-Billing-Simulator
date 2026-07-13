<?php

declare(strict_types=1);

namespace App\Plano;

use function App\Database\getConnection;

function planoExiste(int $id): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('SELECT 1 FROM planos WHERE id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() !== false;
}
