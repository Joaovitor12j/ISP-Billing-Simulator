<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Cliente\atualizarCliente;
use function App\Cliente\buscarClientePorId;
use function App\Cliente\cpfJaCadastrado;
use function App\Cliente\criarCliente;
use function App\Cliente\excluirCliente;
use function App\Cliente\listarClientes;
use function App\Cliente\validarCamposCliente;
use function App\Plano\listarPlanos;
use function App\Plano\planoExiste;

$erros = [];
$clienteEmEdicao = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $id = $id === false ? null : $id;

    $nome = trim((string) ($_POST['nome'] ?? ''));
    $cpf = preg_replace('/\D/', '', (string) ($_POST['cpf'] ?? ''));
    $endereco = trim((string) ($_POST['endereco'] ?? ''));

    $planoId = filter_input(INPUT_POST, 'plano_id', FILTER_VALIDATE_INT);
    $planoId = $planoId === false ? null : $planoId;

    $erros = validarCamposCliente($nome, $cpf, $endereco, $planoId);

    if ($erros === [] && cpfJaCadastrado($cpf, $id)) {
        $erros[] = 'CPF já cadastrado';
    }

    if ($erros === [] && !planoExiste($planoId)) {
        $erros[] = 'Plano selecionado não existe';
    }

    if ($erros === []) {
        if ($id !== null) {
            atualizarCliente($id, $nome, $cpf, $endereco, $planoId);
        } else {
            criarCliente($nome, $cpf, $endereco, $planoId);
        }

        header('Location: /clientes.php');
        exit;
    }

    $clienteEmEdicao = [
        'id' => $id,
        'nome' => $nome,
        'cpf' => $cpf,
        'endereco' => $endereco,
        'plano_id' => $planoId,
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    excluirCliente((int) $_GET['excluir']);
    header('Location: /clientes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['editar'])) {
    $clienteEmEdicao = buscarClientePorId((int) $_GET['editar']);
}

$clientes = listarClientes();
$planos = listarPlanos();

require dirname(__DIR__) . '/templates/clientes/pagina.php';
