<?php

declare(strict_types=1);

namespace Tests\Plano;

use PHPUnit\Framework\TestCase;

use function App\Plano\validarCamposPlano;

final class ValidarCamposPlanoTest extends TestCase
{
    public function test_deve_aceitar_dados_validos(): void
    {
        $erros = validarCamposPlano('Fibra 200MB', 200, 99.90, 10);

        $this->assertSame([], $erros);
    }

    public function test_deve_rejeitar_nome_vazio(): void
    {
        $erros = validarCamposPlano('', 200, 99.90, 10);

        $this->assertContains('Nome é obrigatório', $erros);
    }

    public function test_deve_rejeitar_velocidade_nula_ou_nao_positiva(): void
    {
        $this->assertContains('Velocidade (Mbps) deve ser um número inteiro positivo', validarCamposPlano('Fibra', null, 99.90, 10));
        $this->assertContains('Velocidade (Mbps) deve ser um número inteiro positivo', validarCamposPlano('Fibra', 0, 99.90, 10));
    }

    public function test_deve_rejeitar_valor_mensal_nulo_ou_nao_positivo(): void
    {
        $this->assertContains('Valor mensal deve ser um número positivo', validarCamposPlano('Fibra', 200, null, 10));
        $this->assertContains('Valor mensal deve ser um número positivo', validarCamposPlano('Fibra', 200, 0.0, 10));
    }

    public function test_deve_rejeitar_dia_vencimento_fora_do_intervalo(): void
    {
        $this->assertContains('Dia de vencimento deve estar entre 1 e 31', validarCamposPlano('Fibra', 200, 99.90, 0));
        $this->assertContains('Dia de vencimento deve estar entre 1 e 31', validarCamposPlano('Fibra', 200, 99.90, 32));
        $this->assertContains('Dia de vencimento deve estar entre 1 e 31', validarCamposPlano('Fibra', 200, 99.90, null));
    }
}
