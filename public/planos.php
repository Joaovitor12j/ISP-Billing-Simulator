<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Plano\atualizarPlano;
use function App\Plano\buscarPlanoPorId;
use function App\Plano\criarPlano;
use function App\Plano\excluirPlano;
use function App\Plano\listarPlanos;
use function App\Plano\validarCamposPlano;

$erros = [];
$planoEmEdicao = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $id = $id === false ? null : $id;

    $nome = trim((string) ($_POST['nome'] ?? ''));

    $velocidadeMbps = filter_input(INPUT_POST, 'velocidade_mbps', FILTER_VALIDATE_INT);
    $velocidadeMbps = $velocidadeMbps === false ? null : $velocidadeMbps;

    $valorMensal = filter_input(INPUT_POST, 'valor_mensal', FILTER_VALIDATE_FLOAT);
    $valorMensal = $valorMensal === false ? null : $valorMensal;

    $diaVencimento = filter_input(INPUT_POST, 'dia_vencimento', FILTER_VALIDATE_INT);
    $diaVencimento = $diaVencimento === false ? null : $diaVencimento;

    $erros = validarCamposPlano($nome, $velocidadeMbps, $valorMensal, $diaVencimento);

    if ($erros === []) {
        if ($id !== null) {
            atualizarPlano($id, $nome, $velocidadeMbps, $valorMensal, $diaVencimento);
        } else {
            criarPlano($nome, $velocidadeMbps, $valorMensal, $diaVencimento);
        }

        header('Location: /planos.php');
        exit;
    }

    $planoEmEdicao = [
        'id' => $id,
        'nome' => $nome,
        'velocidade_mbps' => $velocidadeMbps,
        'valor_mensal' => $valorMensal,
        'dia_vencimento' => $diaVencimento,
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    excluirPlano((int) $_GET['excluir']);
    header('Location: /planos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['editar'])) {
    $planoEmEdicao = buscarPlanoPorId((int) $_GET['editar']);
}

$planos = listarPlanos();

require dirname(__DIR__) . '/templates/planos/pagina.php';
