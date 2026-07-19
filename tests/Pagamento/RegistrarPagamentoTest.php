<?php

declare(strict_types=1);

namespace Tests\Pagamento;

use InvalidArgumentException;
use PDO;
use PHPUnit\Framework\TestCase;

use function App\Cliente\criarCliente;
use function App\Database\getConnection;
use function App\Pagamento\listarPagamentosPorFatura;
use function App\Pagamento\registrarPagamento;
use function App\Plano\criarPlano;

final class RegistrarPagamentoTest extends TestCase
{
    private PDO $conexao;
    private int $planoId;
    private int $clienteId;
    private int $faturaId;

    protected function setUp(): void
    {
        $this->conexao = getConnection();

        $this->planoId = criarPlano('Plano Teste Pagamento', 100, 100.00, 10);
        $this->clienteId = criarCliente(
            'Cliente Teste Pagamento',
            (string) random_int(10000000000, 99999999999),
            'Endereço Teste',
            $this->planoId
        );

        $stmtFatura = $this->conexao->prepare(
            'INSERT INTO faturas (cliente_id, competencia, valor, data_vencimento, status) VALUES (:cliente_id, :competencia, :valor, :data_vencimento, :status)'
        );
        $stmtFatura->execute([
            'cliente_id' => $this->clienteId,
            'competencia' => '2026-01',
            'valor' => 100.00,
            'data_vencimento' => '2026-01-10',
            'status' => 'aberta',
        ]);

        $this->faturaId = (int) $this->conexao->lastInsertId();
    }

    protected function tearDown(): void
    {
        $this->conexao->prepare('DELETE FROM clientes WHERE id = :id')->execute(['id' => $this->clienteId]);
        $this->conexao->prepare('DELETE FROM planos WHERE id = :id')->execute(['id' => $this->planoId]);
    }

    private function buscarStatusFatura(): string
    {
        $stmt = $this->conexao->prepare('SELECT status FROM faturas WHERE id = :id');
        $stmt->execute(['id' => $this->faturaId]);

        return $stmt->fetch()['status'];
    }

    public function test_deve_manter_status_parcial_e_calcular_saldo_restante_quando_pagamento_parcial(): void
    {
        registrarPagamento($this->conexao, $this->faturaId, 40.00, 'pix', '2026-01-05');

        $pagamentos = listarPagamentosPorFatura($this->faturaId);
        $totalPago = array_sum(array_column($pagamentos, 'valor_pago'));
        $saldoRestante = 100.00 - $totalPago;

        $this->assertSame('parcial', $this->buscarStatusFatura());
        $this->assertSame(60.00, round($saldoRestante, 2));
    }

    public function test_deve_mudar_status_para_paga_quando_soma_de_pagamentos_parciais_totaliza_valor_fatura(): void
    {
        registrarPagamento($this->conexao, $this->faturaId, 40.00, 'pix', '2026-01-05');
        $this->assertSame('parcial', $this->buscarStatusFatura());

        registrarPagamento($this->conexao, $this->faturaId, 60.00, 'boleto', '2026-01-08');

        $this->assertSame('paga', $this->buscarStatusFatura());
    }

    public function test_deve_mudar_status_para_paga_quando_pagamento_unico_de_valor_igual_ao_total(): void
    {
        registrarPagamento($this->conexao, $this->faturaId, 100.00, 'cartao', '2026-01-05');

        $this->assertSame('paga', $this->buscarStatusFatura());
    }

    public function test_deve_rejeitar_pagamento_em_fatura_ja_paga(): void
    {
        registrarPagamento($this->conexao, $this->faturaId, 100.00, 'cartao', '2026-01-05');

        $this->expectException(InvalidArgumentException::class);

        registrarPagamento($this->conexao, $this->faturaId, 10.00, 'pix', '2026-01-06');
    }

    public function test_deve_rejeitar_pagamento_com_valor_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        registrarPagamento($this->conexao, $this->faturaId, 0.0, 'pix', '2026-01-05');
    }

    public function test_deve_rejeitar_pagamento_com_valor_negativo(): void
    {
        $this->expectException(InvalidArgumentException::class);

        registrarPagamento($this->conexao, $this->faturaId, -10.00, 'pix', '2026-01-05');
    }
}
