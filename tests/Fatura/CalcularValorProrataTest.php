<?php

declare(strict_types=1);

namespace Tests\Fatura;

use PHPUnit\Framework\TestCase;

use function App\Fatura\calcularValorProrata;

final class CalcularValorProrataTest extends TestCase
{
    public function test_deve_cobrar_valor_cheio_quando_cadastro_no_dia_1_mes_30_dias_vencimento_dia_10(): void
    {
        $valor = calcularValorProrata(100.0, 1, 10, 30);

        $this->assertSame(100.0, $valor);
    }

    public function test_deve_cobrar_proporcional_quando_cadastro_dia_15_vencimento_dia_10_mes_30_dias(): void
    {
        $valor = calcularValorProrata(100.0, 15, 10, 30);

        $this->assertSame(53.33, $valor);
    }

    public function test_deve_cobrar_proporcional_a_1_dia_quando_cadastro_dia_31_mes_31_dias_vencimento_dia_5(): void
    {
        $valor = calcularValorProrata(100.0, 31, 5, 31);

        $this->assertSame(3.23, $valor);
    }

    public function test_deve_cobrar_valor_cheio_quando_cadastro_exatamente_no_dia_de_vencimento(): void
    {
        $valor = calcularValorProrata(100.0, 10, 10, 30);

        $this->assertSame(100.0, $valor);
    }

    public function test_deve_cobrar_valor_cheio_em_fevereiro_28_dias_com_vencimento_e_cadastro_no_dia_28(): void
    {
        $valor = calcularValorProrata(100.0, 28, 28, 28);

        $this->assertSame(100.0, $valor);
    }
}
