<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function atualizarCliente(int $id, string $nome, string $cpf, string $endereco, int $planoId): bool
{
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'UPDATE clientes SET nome = :nome, cpf = :cpf, endereco = :endereco, plano_id = :plano_id WHERE id = :id'
    );

    return $stmt->execute([
        'id' => $id,
        'nome' => $nome,
        'cpf' => $cpf,
        'endereco' => $endereco,
        'plano_id' => $planoId,
    ]);
}
