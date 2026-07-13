<?php

declare(strict_types=1);

namespace App\Database;

use Dotenv\Dotenv;
use PDO;

function getConnection(): PDO
{
    Dotenv::createImmutable(dirname(__DIR__, 2))->safeLoad();

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $_ENV['DB_HOST'],
        $_ENV['DB_PORT'],
        $_ENV['DB_NAME']
    );

    return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}
