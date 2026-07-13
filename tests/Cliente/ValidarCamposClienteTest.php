<?php

declare(strict_types=1);

namespace Tests\Cliente;

use PHPUnit\Framework\TestCase;

use function App\Cliente\validarCamposCliente;

final class ValidarCamposClienteTest extends TestCase
{
    public function test_deve_aceitar_dados_validos(): void
    {
        $erros = validarCamposCliente('Joao Silva', '11144477735', 'Rua A, 123', 1);

        $this->assertSame([], $erros);
    }

    public function test_deve_rejeitar_nome_vazio(): void
    {
        $erros = validarCamposCliente('', '11144477735', 'Rua A, 123', 1);

        $this->assertContains('Nome é obrigatório', $erros);
    }

    public function test_deve_rejeitar_cpf_invalido(): void
    {
        $erros = validarCamposCliente('Joao', '12345678900', 'Rua A, 123', 1);

        $this->assertContains('CPF inválido', $erros);
    }

    public function test_deve_rejeitar_endereco_vazio(): void
    {
        $erros = validarCamposCliente('Joao', '11144477735', '', 1);

        $this->assertContains('Endereço é obrigatório', $erros);
    }

    public function test_deve_rejeitar_plano_nulo(): void
    {
        $erros = validarCamposCliente('Joao', '11144477735', 'Rua A, 123', null);

        $this->assertContains('Plano é obrigatório', $erros);
    }
}
