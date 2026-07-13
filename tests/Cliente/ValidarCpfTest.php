<?php

declare(strict_types=1);

namespace Tests\Cliente;

use PHPUnit\Framework\TestCase;

use function App\Cliente\validarCpf;

final class ValidarCpfTest extends TestCase
{
    public function test_deve_aceitar_cpf_valido(): void
    {
        $this->assertTrue(validarCpf('111.444.777-35'));
        $this->assertTrue(validarCpf('11144477735'));
    }

    public function test_deve_rejeitar_cpf_com_digito_verificador_invalido(): void
    {
        $this->assertFalse(validarCpf('11144477736'));
    }

    public function test_deve_rejeitar_cpf_com_todos_digitos_iguais(): void
    {
        $this->assertFalse(validarCpf('11111111111'));
    }

    public function test_deve_rejeitar_cpf_com_tamanho_invalido(): void
    {
        $this->assertFalse(validarCpf('123456789'));
    }
}
