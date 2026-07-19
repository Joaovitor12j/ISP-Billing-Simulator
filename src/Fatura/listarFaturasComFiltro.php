<?php

declare(strict_types=1);

namespace App\Fatura;

use PDO;

function listarFaturasComFiltro(
    PDO $conexao,
    ?string $dataInicio,
    ?string $dataFim,
    ?int $clienteId,
    ?string $status
): array {
    $condicoes = [];
    $parametros = [];

    if ($dataInicio !== null) {
        $condicoes[] = 'faturas.data_vencimento >= :data_inicio';
        $parametros['data_inicio'] = $dataInicio;
    }

    if ($dataFim !== null) {
        $condicoes[] = 'faturas.data_vencimento <= :data_fim';
        $parametros['data_fim'] = $dataFim;
    }

    if ($clienteId !== null) {
        $condicoes[] = 'faturas.cliente_id = :cliente_id';
        $parametros['cliente_id'] = $clienteId;
    }

    if ($status !== null) {
        $condicoes[] = 'faturas.status = :status';
        $parametros['status'] = $status;
    }

    $sql = 'SELECT faturas.id, faturas.cliente_id, faturas.competencia, faturas.valor, faturas.data_vencimento, faturas.status, clientes.nome AS cliente_nome
            FROM faturas
            INNER JOIN clientes ON clientes.id = faturas.cliente_id';

    if ($condicoes !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $condicoes);
    }

    $sql .= ' ORDER BY faturas.data_vencimento DESC';

    $stmt = $conexao->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll();
}
