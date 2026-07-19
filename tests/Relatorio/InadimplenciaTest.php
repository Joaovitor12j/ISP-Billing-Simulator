<?php

declare(strict_types=1);

namespace Tests\Relatorio;

use PDO;
use PHPUnit\Framework\TestCase;

use function App\Database\getConnection;
use function App\Relatorio\listarInadimplentes;

final class InadimplenciaTest extends TestCase
{
    private PDO $conexao;
    private int $planoId;
    private int $clienteId;

    protected function setUp(): void
    {
        $this->conexao = getConnection();

        $stmtPlano = $this->conexao->prepare(
            'INSERT INTO planos (nome, velocidade_mbps, valor_mensal, dia_vencimento) VALUES (:nome, 100, 99.90, 10)'
        );
        $stmtPlano->execute(['nome' => 'Plano Teste Inadimplencia ' . uniqid()]);
        $this->planoId = (int) $this->conexao->lastInsertId();

        $stmtCliente = $this->conexao->prepare(
            'INSERT INTO clientes (nome, cpf, endereco, plano_id) VALUES (:nome, :cpf, :endereco, :plano_id)'
        );
        $stmtCliente->execute([
            'nome' => 'Cliente Teste Inadimplencia',
            'cpf' => (string) random_int(10000000000, 99999999999),
            'endereco' => 'Rua Teste, 123',
            'plano_id' => $this->planoId,
        ]);
        $this->clienteId = (int) $this->conexao->lastInsertId();
    }

    protected function tearDown(): void
    {
        $this->conexao->prepare('DELETE FROM faturas WHERE cliente_id = :cliente_id')
            ->execute(['cliente_id' => $this->clienteId]);
        $this->conexao->prepare('DELETE FROM clientes WHERE id = :id')
            ->execute(['id' => $this->clienteId]);
        $this->conexao->prepare('DELETE FROM planos WHERE id = :id')
            ->execute(['id' => $this->planoId]);
    }

    private function criarFatura(string $competencia, string $dataVencimento, string $status): void
    {
        $stmt = $this->conexao->prepare(
            'INSERT INTO faturas (cliente_id, competencia, valor, data_vencimento, status)
             VALUES (:cliente_id, :competencia, 99.90, :data_vencimento, :status)'
        );
        $stmt->execute([
            'cliente_id' => $this->clienteId,
            'competencia' => $competencia,
            'data_vencimento' => $dataVencimento,
            'status' => $status,
        ]);
    }

    public function test_fatura_vencida_ontem_nao_aparece_como_inadimplente_com_limite_de_30_dias(): void
    {
        $this->criarFatura('2026-06', date('Y-m-d', strtotime('-1 day')), 'aberta');

        $inadimplentes = listarInadimplentes($this->conexao, 30);

        $this->assertSame([], $this->filtrarPorCliente($inadimplentes));
    }

    public function test_fatura_vencida_ha_30_dias_aparece_como_inadimplente_com_limite_de_30_dias(): void
    {
        $this->criarFatura('2026-06', date('Y-m-d', strtotime('-30 days')), 'aberta');

        $inadimplentes = $this->filtrarPorCliente(listarInadimplentes($this->conexao, 30));

        $this->assertCount(1, $inadimplentes);
        $this->assertSame(30, (int) $inadimplentes[0]['dias_atraso']);
    }

    public function test_fatura_paga_nunca_aparece_independente_da_data_de_vencimento(): void
    {
        $this->criarFatura('2026-01', date('Y-m-d', strtotime('-365 days')), 'paga');

        $inadimplentes = listarInadimplentes($this->conexao, 1);

        $this->assertSame([], $this->filtrarPorCliente($inadimplentes));
    }

    public function test_fatura_vencida_ha_5_dias_aparece_como_inadimplente_com_limite_de_1_dia(): void
    {
        $this->criarFatura('2026-07', date('Y-m-d', strtotime('-5 days')), 'parcial');

        $inadimplentes = $this->filtrarPorCliente(listarInadimplentes($this->conexao, 1));

        $this->assertCount(1, $inadimplentes);
        $this->assertSame(5, (int) $inadimplentes[0]['dias_atraso']);
    }

    private function filtrarPorCliente(array $inadimplentes): array
    {
        return array_values(array_filter(
            $inadimplentes,
            fn (array $fatura): bool => (int) $fatura['cliente_id'] === $this->clienteId
        ));
    }
}
