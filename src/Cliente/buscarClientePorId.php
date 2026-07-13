<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function buscarClientePorId(int $id): ?array
{
    $pdo = getConnection();

    $stmt = $pdo->prepare('SELECT id, nome, cpf, endereco, plano_id FROM clientes WHERE id = :id');
    $stmt->execute(['id' => $id]);

    $cliente = $stmt->fetch();

    return $cliente === false ? null : $cliente;
}
