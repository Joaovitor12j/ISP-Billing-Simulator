<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Faturas</title>
</head>
<body>
    <h1>Faturas</h1>

    <?php if ($erros !== []): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= escapeHtml($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/faturas.php">
        <label>
            Cliente
            <select name="cliente_id" required>
                <option value="">Selecione</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= escapeHtml((string) $cliente['id']) ?>">
                        <?= escapeHtml($cliente['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Competência
            <input type="month" name="competencia" required>
        </label>

        <button type="submit">Gerar fatura</button>
    </form>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Competência</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faturas as $fatura): ?>
                <tr>
                    <td><?= escapeHtml($fatura['cliente_nome']) ?></td>
                    <td><?= escapeHtml($fatura['competencia']) ?></td>
                    <td><?= escapeHtml($fatura['valor']) ?></td>
                    <td><?= escapeHtml($fatura['data_vencimento']) ?></td>
                    <td><?= escapeHtml($fatura['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
