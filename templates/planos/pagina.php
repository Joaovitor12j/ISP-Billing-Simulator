<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Planos</title>
</head>
<body>
    <h1>Planos</h1>

    <?php if ($erros !== []): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= escapeHtml($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/planos.php">
        <?php if ($planoEmEdicao !== null && $planoEmEdicao['id'] !== null): ?>
            <input type="hidden" name="id" value="<?= escapeHtml((string) $planoEmEdicao['id']) ?>">
        <?php endif; ?>

        <label>
            Nome
            <input type="text" name="nome" value="<?= escapeHtml((string) ($planoEmEdicao['nome'] ?? '')) ?>" required>
        </label>

        <label>
            Velocidade (Mbps)
            <input type="number" name="velocidade_mbps" value="<?= escapeHtml((string) ($planoEmEdicao['velocidade_mbps'] ?? '')) ?>" required>
        </label>

        <label>
            Valor mensal
            <input type="number" step="0.01" name="valor_mensal" value="<?= escapeHtml((string) ($planoEmEdicao['valor_mensal'] ?? '')) ?>" required>
        </label>

        <label>
            Dia de vencimento
            <input type="number" min="1" max="31" name="dia_vencimento" value="<?= escapeHtml((string) ($planoEmEdicao['dia_vencimento'] ?? '')) ?>" required>
        </label>

        <button type="submit"><?= $planoEmEdicao !== null && $planoEmEdicao['id'] !== null ? 'Atualizar' : 'Criar' ?></button>
    </form>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Velocidade (Mbps)</th>
                <th>Valor mensal</th>
                <th>Dia de vencimento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planos as $plano): ?>
                <tr>
                    <td><?= escapeHtml($plano['nome']) ?></td>
                    <td><?= escapeHtml((string) $plano['velocidade_mbps']) ?></td>
                    <td><?= escapeHtml($plano['valor_mensal']) ?></td>
                    <td><?= escapeHtml((string) $plano['dia_vencimento']) ?></td>
                    <td>
                        <a href="/planos.php?editar=<?= escapeHtml((string) $plano['id']) ?>">Editar</a>
                        <a href="/planos.php?excluir=<?= escapeHtml((string) $plano['id']) ?>" onclick="return confirm('Excluir plano?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
