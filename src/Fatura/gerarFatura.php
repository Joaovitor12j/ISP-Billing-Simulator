<?php

declare(strict_types=1);

namespace App\Fatura;

use PDO;

function gerarFatura(PDO $conexao, int $clienteId, string $competencia): int
{
    $stmtExistente = $conexao->prepare(
        'SELECT id FROM faturas WHERE cliente_id = :cliente_id AND competencia = :competencia'
    );
    $stmtExistente->execute(['cliente_id' => $clienteId, 'competencia' => $competencia]);

    $faturaExistente = $stmtExistente->fetch();

    if ($faturaExistente !== false) {
        return (int) $faturaExistente['id'];
    }

    $stmtCliente = $conexao->prepare(
        'SELECT clientes.created_at, planos.valor_mensal, planos.dia_vencimento
         FROM clientes
         INNER JOIN planos ON planos.id = clientes.plano_id
         WHERE clientes.id = :cliente_id'
    );
    $stmtCliente->execute(['cliente_id' => $clienteId]);

    $cliente = $stmtCliente->fetch();

    $diaCadastro = (int) date('j', strtotime($cliente['created_at']));
    $diaVencimento = (int) $cliente['dia_vencimento'];
    $valorMensal = (float) $cliente['valor_mensal'];
    $diasNoMes = (int) date('t', strtotime($competencia . '-01'));

    $stmtTotalFaturas = $conexao->prepare('SELECT COUNT(*) AS total FROM faturas WHERE cliente_id = :cliente_id');
    $stmtTotalFaturas->execute(['cliente_id' => $clienteId]);

    $totalFaturas = (int) $stmtTotalFaturas->fetch()['total'];
    $primeiraFatura = $totalFaturas === 0;

    $valor = $primeiraFatura
        ? calcularValorProrata($valorMensal, $diaCadastro, $diaVencimento, $diasNoMes)
        : round($valorMensal, 2);

    $dataVencimento = sprintf('%s-%02d', $competencia, min($diaVencimento, $diasNoMes));

    $stmtInserir = $conexao->prepare(
        'INSERT INTO faturas (cliente_id, competencia, valor, data_vencimento, status) VALUES (:cliente_id, :competencia, :valor, :data_vencimento, :status)'
    );
    $stmtInserir->execute([
        'cliente_id' => $clienteId,
        'competencia' => $competencia,
        'valor' => $valor,
        'data_vencimento' => $dataVencimento,
        'status' => 'aberta',
    ]);

    return (int) $conexao->lastInsertId();
}
