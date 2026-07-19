<?php

declare(strict_types=1);

namespace App\Relatorio;

use PDO;

function receitaPorPlanoEMes(PDO $conexao, string $competencia): array
{
    $stmt = $conexao->prepare(
        'SELECT planos.nome AS plano_nome, COUNT(DISTINCT clientes.id) AS quantidade_clientes, SUM(faturas.valor) AS receita_total
         FROM faturas
         INNER JOIN clientes ON clientes.id = faturas.cliente_id
         INNER JOIN planos ON planos.id = clientes.plano_id
         WHERE faturas.competencia = :competencia AND faturas.status = \'paga\'
         GROUP BY planos.id, planos.nome
         ORDER BY planos.nome'
    );
    $stmt->execute(['competencia' => $competencia]);

    return $stmt->fetchAll();
}
