<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function criarCliente(string $nome, string $cpf, string $endereco, int $planoId): int
{
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'INSERT INTO clientes (nome, cpf, endereco, plano_id) VALUES (:nome, :cpf, :endereco, :plano_id)'
    );

    $stmt->execute([
        'nome' => $nome,
        'cpf' => $cpf,
        'endereco' => $endereco,
        'plano_id' => $planoId,
    ]);

    return (int) $pdo->lastInsertId();
}
