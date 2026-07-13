<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function excluirCliente(int $id): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = :id');

    return $stmt->execute(['id' => $id]);
}
