<?php

declare(strict_types=1);

namespace App\Pagamento;

use function App\Database\getConnection;

function listarPagamentosPorFatura(int $faturaId): array
{
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'SELECT id, fatura_id, valor_pago, forma_pagamento, data_pagamento FROM pagamentos WHERE fatura_id = :fatura_id ORDER BY data_pagamento'
    );
    $stmt->execute(['fatura_id' => $faturaId]);

    return $stmt->fetchAll();
}
