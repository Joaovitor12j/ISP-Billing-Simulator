<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Pagamento da Fatura #<?= escapeHtml((string) $fatura['id']) ?></title>
</head>
<body>
    <h1>Pagamento da Fatura #<?= escapeHtml((string) $fatura['id']) ?></h1>

    <?php if ($erros !== []): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= escapeHtml($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p>Competência: <?= escapeHtml($fatura['competencia']) ?></p>
    <p>Valor da fatura: <?= escapeHtml((string) $fatura['valor']) ?></p>
    <p>Status: <?= escapeHtml($fatura['status']) ?></p>
    <p>Saldo restante: <?= escapeHtml((string) $saldoRestante) ?></p>

    <?php if ($fatura['status'] !== 'paga'): ?>
        <form method="post" action="/pagamento.php">
            <input type="hidden" name="fatura_id" value="<?= escapeHtml((string) $fatura['id']) ?>">

            <label>
                Valor pago
                <input type="number" step="0.01" name="valor_pago" required>
            </label>

            <label>
                Forma de pagamento
                <input type="text" name="forma_pagamento" required>
            </label>

            <label>
                Data do pagamento
                <input type="date" name="data_pagamento" required>
            </label>

            <button type="submit">Registrar pagamento</button>
        </form>
    <?php endif; ?>

    <h2>Histórico de pagamentos</h2>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Data</th>
                <th>Valor pago</th>
                <th>Forma de pagamento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagamentos as $pagamento): ?>
                <tr>
                    <td><?= escapeHtml($pagamento['data_pagamento']) ?></td>
                    <td><?= escapeHtml((string) $pagamento['valor_pago']) ?></td>
                    <td><?= escapeHtml($pagamento['forma_pagamento']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="/faturas.php">Voltar para faturas</a></p>
</body>
</html>
