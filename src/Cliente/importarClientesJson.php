<?php

declare(strict_types=1);

namespace App\Cliente;

use JsonException;
use PDO;

use function App\Plano\planoExiste;

function importarClientesJson(PDO $conexao, string $conteudoJson): array
{
    try {
        $itens = json_decode($conteudoJson, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        return ['sucesso' => 0, 'erros' => ['JSON malformado']];
    }

    if (!is_array($itens)) {
        return ['sucesso' => 0, 'erros' => ['JSON deve conter uma lista de clientes']];
    }

    $sucesso = 0;
    $erros = [];

    foreach ($itens as $indice => $item) {
        $linha = $indice + 1;

        if (!is_array($item)) {
            $erros[] = "Linha {$linha}: estrutura inválida";
            continue;
        }

        $nome = trim((string) ($item['nome'] ?? ''));
        $cpf = preg_replace('/\D/', '', (string) ($item['cpf'] ?? ''));
        $endereco = trim((string) ($item['endereco'] ?? ''));
        $planoId = isset($item['plano_id']) ? (int) $item['plano_id'] : null;

        $errosCampos = validarCamposCliente($nome, $cpf, $endereco, $planoId);

        if ($errosCampos !== []) {
            $erros[] = "Linha {$linha}: " . implode(', ', $errosCampos);
            continue;
        }

        if (cpfJaCadastrado($cpf)) {
            $erros[] = "Linha {$linha}: CPF já cadastrado";
            continue;
        }

        if (!planoExiste($planoId)) {
            $erros[] = "Linha {$linha}: plano_id inexistente";
            continue;
        }

        criarCliente($nome, $cpf, $endereco, $planoId);
        $sucesso++;
    }

    return ['sucesso' => $sucesso, 'erros' => $erros];
}
