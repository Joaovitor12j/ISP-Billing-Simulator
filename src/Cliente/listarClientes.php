<?php

declare(strict_types=1);

namespace App\Cliente;

use function App\Database\getConnection;

function listarClientes(): array
{
    $pdo = getConnection();

    $stmt = $pdo->query(
        'SELECT clientes.id, clientes.nome, clientes.cpf, clientes.endereco, clientes.plano_id, planos.nome AS plano_nome
         FROM clientes
         INNER JOIN planos ON planos.id = clientes.plano_id
         ORDER BY clientes.nome'
    );

    return $stmt->fetchAll();
}
