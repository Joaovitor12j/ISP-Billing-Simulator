<?php

declare(strict_types=1);

namespace App\Http;

function escapeHtml(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
