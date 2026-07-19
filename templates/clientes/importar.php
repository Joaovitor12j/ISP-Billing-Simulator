<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Importar clientes</title>
</head>
<body>
    <h1>Importar clientes via JSON</h1>

    <form method="post" action="/importar_clientes.php" enctype="multipart/form-data">
        <label>
            Arquivo JSON
            <input type="file" name="arquivo" accept=".json,application/json" required>
        </label>

        <button type="submit">Importar</button>
    </form>

    <?php if ($resultado !== null): ?>
        <h2>Resumo da importação</h2>
        <p>Clientes importados com sucesso: <?= escapeHtml((string) $resultado['sucesso']) ?></p>

        <?php if ($resultado['erros'] !== []): ?>
            <ul style="color: red;">
                <?php foreach ($resultado['erros'] as $erro): ?>
                    <li><?= escapeHtml($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="/clientes.php">Voltar para clientes</a></p>
</body>
</html>
