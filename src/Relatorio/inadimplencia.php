<?php

declare(strict_types=1);

namespace App\Relatorio;

use PDO;

function listarInadimplentes(PDO $conexao, int $diasAtraso): array
{
    $stmt = $conexao->prepare(
        'SELECT faturas.id, faturas.cliente_id, faturas.competencia, faturas.valor, faturas.data_vencimento, faturas.status,
                clientes.nome AS cliente_nome, clientes.endereco AS cliente_contato,
                DATEDIFF(CURDATE(), faturas.data_vencimento) AS dias_atraso
         FROM faturas
         INNER JOIN clientes ON clientes.id = faturas.cliente_id
         WHERE faturas.status IN (\'aberta\', \'parcial\')
           AND DATEDIFF(CURDATE(), faturas.data_vencimento) >= :dias_atraso
         ORDER BY dias_atraso DESC'
    );
    $stmt->execute(['dias_atraso' => $diasAtraso]);

    return $stmt->fetchAll();
}
