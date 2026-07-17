<?php

declare(strict_types=1);

namespace App\Fatura;

use function App\Database\getConnection;

function listarFaturas(): array
{
    $pdo = getConnection();

    $stmt = $pdo->query(
        'SELECT faturas.id, faturas.cliente_id, faturas.competencia, faturas.valor, faturas.data_vencimento, faturas.status, clientes.nome AS cliente_nome
         FROM faturas
         INNER JOIN clientes ON clientes.id = faturas.cliente_id
         ORDER BY faturas.data_vencimento DESC'
    );

    return $stmt->fetchAll();
}
