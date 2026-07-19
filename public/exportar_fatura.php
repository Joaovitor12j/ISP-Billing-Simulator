<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use function App\Database\getConnection;
use function App\Fatura\exportarFaturaJson;

$faturaId = filter_input(INPUT_GET, 'fatura_id', FILTER_VALIDATE_INT);

if ($faturaId === false || $faturaId === null) {
    http_response_code(400);
    echo 'fatura_id inválido';
    exit;
}

try {
    $json = exportarFaturaJson(getConnection(), $faturaId);
} catch (InvalidArgumentException $e) {
    http_response_code(404);
    echo $e->getMessage();
    exit;
}

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="fatura_' . $faturaId . '.json"');
echo $json;
