<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function cpfJaCadastrado(string $cpf, ?int $ignorarClienteId = null): bool
{
    $pdo = getConnection();

    $sql = 'SELECT 1 FROM clientes WHERE cpf = :cpf';
    $parametros = ['cpf' => $cpf];

    if ($ignorarClienteId !== null) {
        $sql .= ' AND id != :id';
        $parametros['id'] = $ignorarClienteId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetch() !== false;
}
