<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Database\getConnection;
use function App\Fatura\buscarFaturaPorId;
use function App\Pagamento\listarPagamentosPorFatura;
use function App\Pagamento\registrarPagamento;

$erros = [];

$faturaId = filter_input(INPUT_GET, 'fatura_id', FILTER_VALIDATE_INT);
$faturaId = $faturaId === false ? null : $faturaId;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faturaIdPost = filter_input(INPUT_POST, 'fatura_id', FILTER_VALIDATE_INT);
    $faturaId = $faturaIdPost === false ? null : $faturaIdPost;

    $valorPago = filter_input(INPUT_POST, 'valor_pago', FILTER_VALIDATE_FLOAT);
    $valorPago = $valorPago === false ? null : $valorPago;

    $formaPagamento = trim((string) ($_POST['forma_pagamento'] ?? ''));
    $dataPagamento = trim((string) ($_POST['data_pagamento'] ?? ''));

    if ($faturaId === null) {
        $erros[] = 'Fatura é obrigatória';
    }

    if ($valorPago === null) {
        $erros[] = 'Valor pago é obrigatório';
    }

    if ($formaPagamento === '') {
        $erros[] = 'Forma de pagamento é obrigatória';
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataPagamento) !== 1) {
        $erros[] = 'Data de pagamento deve estar no formato AAAA-MM-DD';
    }

    if ($erros === []) {
        try {
            registrarPagamento(getConnection(), $faturaId, $valorPago, $formaPagamento, $dataPagamento);

            header('Location: /pagamento.php?fatura_id=' . $faturaId);
            exit;
        } catch (InvalidArgumentException $e) {
            $erros[] = $e->getMessage();
        }
    }
}

if ($faturaId === null) {
    http_response_code(400);
    exit('Fatura não informada');
}

$fatura = buscarFaturaPorId($faturaId);

if ($fatura === null) {
    http_response_code(404);
    exit('Fatura não encontrada');
}

$pagamentos = listarPagamentosPorFatura($faturaId);
$totalPago = array_sum(array_column($pagamentos, 'valor_pago'));
$saldoRestante = round((float) $fatura['valor'] - (float) $totalPago, 2);

require dirname(__DIR__) . '/templates/pagamentos/pagina.php';
