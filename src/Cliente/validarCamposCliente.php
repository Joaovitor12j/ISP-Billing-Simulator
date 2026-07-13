<?php

declare(strict_types=1);

namespace App\Cliente;

function validarCamposCliente(string $nome, string $cpf, string $endereco, ?int $planoId): array
{
    $erros = [];

    if (trim($nome) === '') {
        $erros[] = 'Nome é obrigatório';
    }

    if (!validarCpf($cpf)) {
        $erros[] = 'CPF inválido';
    }

    if (trim($endereco) === '') {
        $erros[] = 'Endereço é obrigatório';
    }

    if ($planoId === null) {
        $erros[] = 'Plano é obrigatório';
    }

    return $erros;
}
