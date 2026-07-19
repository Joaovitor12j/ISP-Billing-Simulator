<?php

declare(strict_types=1);

namespace Tests\Cliente;

use PDO;
use PHPUnit\Framework\TestCase;

use function App\Cliente\criarCliente;
use function App\Cliente\importarClientesJson;
use function App\Database\getConnection;
use function App\Plano\criarPlano;

final class ImportarClientesJsonTest extends TestCase
{
    private PDO $conexao;
    private int $planoId;
    private array $cpfsCriados = [];

    protected function setUp(): void
    {
        $this->conexao = getConnection();
        $this->planoId = criarPlano('Plano Teste Import ' . uniqid(), 100, 99.90, 10);
    }

    protected function tearDown(): void
    {
        foreach ($this->cpfsCriados as $cpf) {
            $this->conexao->prepare('DELETE FROM clientes WHERE cpf = :cpf')->execute(['cpf' => $cpf]);
        }

        $this->conexao->prepare('DELETE FROM planos WHERE id = :id')->execute(['id' => $this->planoId]);
    }

    private function gerarCpfValido(int $semente): string
    {
        $cpf = str_pad((string) $semente, 9, '0', STR_PAD_LEFT);

        for ($posicaoVerificador = 9; $posicaoVerificador <= 10; $posicaoVerificador++) {
            $soma = 0;

            for ($posicao = 0; $posicao < $posicaoVerificador; $posicao++) {
                $soma += (int) $cpf[$posicao] * ($posicaoVerificador + 1 - $posicao);
            }

            $digito = ($soma * 10) % 11;

            if ($digito === 10) {
                $digito = 0;
            }

            $cpf .= (string) $digito;
        }

        return $cpf;
    }

    public function test_deve_importar_todos_quando_json_valido_com_tres_clientes(): void
    {
        $cpfs = [
            $this->gerarCpfValido(100200301),
            $this->gerarCpfValido(100200302),
            $this->gerarCpfValido(100200303),
        ];
        $this->cpfsCriados = $cpfs;

        $itens = array_map(fn (string $cpf, int $indice): array => [
            'nome' => 'Cliente Import ' . $indice,
            'cpf' => $cpf,
            'endereco' => 'Rua Import, ' . $indice,
            'plano_id' => $this->planoId,
        ], $cpfs, array_keys($cpfs));

        $resultado = importarClientesJson($this->conexao, json_encode($itens));

        $this->assertSame(3, $resultado['sucesso']);
        $this->assertSame([], $resultado['erros']);
    }

    public function test_deve_rejeitar_cliente_com_cpf_duplicado_mas_importar_demais(): void
    {
        $cpfExistente = $this->gerarCpfValido(200300401);
        $cpfNovo1 = $this->gerarCpfValido(200300402);
        $cpfNovo2 = $this->gerarCpfValido(200300403);
        $this->cpfsCriados = [$cpfExistente, $cpfNovo1, $cpfNovo2];

        criarCliente('Cliente Já Existente', $cpfExistente, 'Rua Existente, 1', $this->planoId);

        $itens = [
            ['nome' => 'Cliente Duplicado', 'cpf' => $cpfExistente, 'endereco' => 'Rua A, 1', 'plano_id' => $this->planoId],
            ['nome' => 'Cliente Novo 1', 'cpf' => $cpfNovo1, 'endereco' => 'Rua B, 2', 'plano_id' => $this->planoId],
            ['nome' => 'Cliente Novo 2', 'cpf' => $cpfNovo2, 'endereco' => 'Rua C, 3', 'plano_id' => $this->planoId],
        ];

        $resultado = importarClientesJson($this->conexao, json_encode($itens));

        $this->assertSame(2, $resultado['sucesso']);
        $this->assertCount(1, $resultado['erros']);
        $this->assertStringContainsString('CPF já cadastrado', $resultado['erros'][0]);
    }

    public function test_deve_rejeitar_cliente_com_plano_id_inexistente_com_mensagem_clara(): void
    {
        $cpf = $this->gerarCpfValido(300400501);
        $this->cpfsCriados = [$cpf];

        $itens = [
            ['nome' => 'Cliente Plano Inexistente', 'cpf' => $cpf, 'endereco' => 'Rua Z, 9', 'plano_id' => 999999],
        ];

        $resultado = importarClientesJson($this->conexao, json_encode($itens));

        $this->assertSame(0, $resultado['sucesso']);
        $this->assertCount(1, $resultado['erros']);
        $this->assertStringContainsString('plano_id inexistente', $resultado['erros'][0]);
    }

    public function test_deve_retornar_erro_sem_quebrar_aplicacao_quando_json_malformado(): void
    {
        $resultado = importarClientesJson($this->conexao, '{invalido,,,');

        $this->assertSame(0, $resultado['sucesso']);
        $this->assertCount(1, $resultado['erros']);
        $this->assertStringContainsString('JSON malformado', $resultado['erros'][0]);
    }
}
