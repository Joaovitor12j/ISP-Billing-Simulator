<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Cliente\listarClientes;
use function App\Database\getConnection;
use function App\Fatura\gerarFatura;
use function App\Fatura\listarFaturas;

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $clienteId = $clienteId === false ? null : $clienteId;

    $competencia = trim((string) ($_POST['competencia'] ?? ''));

    if ($clienteId === null) {
        $erros[] = 'Cliente é obrigatório';
    }

    if (preg_match('/^\d{4}-\d{2}$/', $competencia) !== 1) {
        $erros[] = 'Competência deve estar no formato AAAA-MM';
    }

    if ($erros === []) {
        gerarFatura(getConnection(), $clienteId, $competencia);

        header('Location: /faturas.php');
        exit;
    }
}

$clientes = listarClientes();
$faturas = listarFaturas();

require dirname(__DIR__) . '/templates/faturas/pagina.php';
