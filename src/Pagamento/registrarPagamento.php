<?php

declare(strict_types=1);

namespace App\Pagamento;

use InvalidArgumentException;
use PDO;

function registrarPagamento(PDO $conexao, int $faturaId, float $valorPago, string $formaPagamento, string $dataPagamento): int
{
    if ($valorPago <= 0) {
        throw new InvalidArgumentException('Valor pago deve ser maior que zero');
    }

    $stmtFatura = $conexao->prepare('SELECT valor, status FROM faturas WHERE id = :fatura_id');
    $stmtFatura->execute(['fatura_id' => $faturaId]);

    $fatura = $stmtFatura->fetch();

    if ($fatura === false) {
        throw new InvalidArgumentException('Fatura não encontrada');
    }

    if ($fatura['status'] === 'paga') {
        throw new InvalidArgumentException('Fatura já está paga');
    }

    $stmtInserir = $conexao->prepare(
        'INSERT INTO pagamentos (fatura_id, valor_pago, forma_pagamento, data_pagamento) VALUES (:fatura_id, :valor_pago, :forma_pagamento, :data_pagamento)'
    );
    $stmtInserir->execute([
        'fatura_id' => $faturaId,
        'valor_pago' => $valorPago,
        'forma_pagamento' => $formaPagamento,
        'data_pagamento' => $dataPagamento,
    ]);

    $pagamentoId = (int) $conexao->lastInsertId();

    $stmtTotal = $conexao->prepare('SELECT SUM(valor_pago) AS total_pago FROM pagamentos WHERE fatura_id = :fatura_id');
    $stmtTotal->execute(['fatura_id' => $faturaId]);

    $totalPago = round((float) $stmtTotal->fetch()['total_pago'], 2);
    $valorFatura = round((float) $fatura['valor'], 2);

    $novoStatus = match (true) {
        $totalPago >= $valorFatura => 'paga',
        $totalPago > 0 => 'parcial',
        default => 'aberta',
    };

    $stmtStatus = $conexao->prepare('UPDATE faturas SET status = :status WHERE id = :fatura_id');
    $stmtStatus->execute(['status' => $novoStatus, 'fatura_id' => $faturaId]);

    return $pagamentoId;
}
