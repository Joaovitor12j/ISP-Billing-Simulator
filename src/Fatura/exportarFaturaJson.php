<?php

declare(strict_types=1);

namespace App\Fatura;

use InvalidArgumentException;
use PDO;

function exportarFaturaJson(PDO $conexao, int $faturaId): string
{
    $stmtFatura = $conexao->prepare(
        'SELECT id, cliente_id, competencia, valor, data_vencimento, status FROM faturas WHERE id = :id'
    );
    $stmtFatura->execute(['id' => $faturaId]);

    $fatura = $stmtFatura->fetch(PDO::FETCH_ASSOC);

    if ($fatura === false) {
        throw new InvalidArgumentException('Fatura não encontrada');
    }

    $stmtCliente = $conexao->prepare(
        'SELECT id, nome, cpf, endereco, plano_id FROM clientes WHERE id = :id'
    );
    $stmtCliente->execute(['id' => $fatura['cliente_id']]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    $stmtPlano = $conexao->prepare(
        'SELECT id, nome, velocidade_mbps, valor_mensal, dia_vencimento FROM planos WHERE id = :id'
    );
    $stmtPlano->execute(['id' => $cliente['plano_id']]);
    $plano = $stmtPlano->fetch(PDO::FETCH_ASSOC);

    $stmtPagamentos = $conexao->prepare(
        'SELECT id, valor_pago, forma_pagamento, data_pagamento FROM pagamentos WHERE fatura_id = :fatura_id ORDER BY data_pagamento'
    );
    $stmtPagamentos->execute(['fatura_id' => $faturaId]);
    $pagamentos = $stmtPagamentos->fetchAll(PDO::FETCH_ASSOC);

    $dados = [
        'fatura' => $fatura,
        'cliente' => $cliente,
        'plano' => $plano,
        'pagamentos' => $pagamentos,
    ];

    return json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
}
