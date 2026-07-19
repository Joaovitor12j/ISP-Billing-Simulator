<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Cliente\listarClientes;
use function App\Database\getConnection;
use function App\Fatura\listarFaturasComFiltro;
use function App\Relatorio\listarInadimplentes;
use function App\Relatorio\receitaPorPlanoEMes;

$conexao = getConnection();

$dataInicio = trim((string) ($_GET['data_inicio'] ?? '')) ?: null;
$dataFim = trim((string) ($_GET['data_fim'] ?? '')) ?: null;

$clienteId = filter_input(INPUT_GET, 'cliente_id', FILTER_VALIDATE_INT);
$clienteId = $clienteId === false || $clienteId === null ? null : $clienteId;

$status = trim((string) ($_GET['status'] ?? '')) ?: null;

$competencia = trim((string) ($_GET['competencia'] ?? '')) ?: date('Y-m');

$diasAtraso = filter_input(INPUT_GET, 'dias_atraso', FILTER_VALIDATE_INT);
$diasAtraso = $diasAtraso === false || $diasAtraso === null ? 30 : $diasAtraso;

$clientes = listarClientes();
$faturas = listarFaturasComFiltro($conexao, $dataInicio, $dataFim, $clienteId, $status);
$receitaPorPlano = receitaPorPlanoEMes($conexao, $competencia);
$inadimplentes = listarInadimplentes($conexao, $diasAtraso);

require dirname(__DIR__) . '/templates/relatorios/pagina.php';
