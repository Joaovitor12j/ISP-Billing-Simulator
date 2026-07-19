<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Cliente\importarClientesJson;
use function App\Database\getConnection;

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $conteudo = file_get_contents($_FILES['arquivo']['tmp_name']);
    $resultado = importarClientesJson(getConnection(), $conteudo);
}

require dirname(__DIR__) . '/templates/clientes/importar.php';
