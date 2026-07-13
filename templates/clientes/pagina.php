<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Clientes</title>
</head>
<body>
    <h1>Clientes</h1>

    <?php if ($erros !== []): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= escapeHtml($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/clientes.php">
        <?php if ($clienteEmEdicao !== null && $clienteEmEdicao['id'] !== null): ?>
            <input type="hidden" name="id" value="<?= escapeHtml((string) $clienteEmEdicao['id']) ?>">
        <?php endif; ?>

        <label>
            Nome
            <input type="text" name="nome" value="<?= escapeHtml((string) ($clienteEmEdicao['nome'] ?? '')) ?>" required>
        </label>

        <label>
            CPF
            <input type="text" name="cpf" value="<?= escapeHtml((string) ($clienteEmEdicao['cpf'] ?? '')) ?>" maxlength="14" required>
        </label>

        <label>
            Endereço
            <input type="text" name="endereco" value="<?= escapeHtml((string) ($clienteEmEdicao['endereco'] ?? '')) ?>" required>
        </label>

        <label>
            Plano
            <select name="plano_id" required>
                <option value="">Selecione</option>
                <?php foreach ($planos as $plano): ?>
                    <option value="<?= escapeHtml((string) $plano['id']) ?>" <?= (int) ($clienteEmEdicao['plano_id'] ?? 0) === (int) $plano['id'] ? 'selected' : '' ?>>
                        <?= escapeHtml($plano['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <button type="submit"><?= $clienteEmEdicao !== null && $clienteEmEdicao['id'] !== null ? 'Atualizar' : 'Criar' ?></button>
    </form>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Plano</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= escapeHtml($cliente['nome']) ?></td>
                    <td><?= escapeHtml($cliente['cpf']) ?></td>
                    <td><?= escapeHtml($cliente['endereco']) ?></td>
                    <td><?= escapeHtml($cliente['plano_nome']) ?></td>
                    <td>
                        <a href="/clientes.php?editar=<?= escapeHtml((string) $cliente['id']) ?>">Editar</a>
                        <a href="/clientes.php?excluir=<?= escapeHtml((string) $cliente['id']) ?>" onclick="return confirm('Excluir cliente?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
