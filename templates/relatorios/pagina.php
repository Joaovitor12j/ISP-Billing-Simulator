<?php

declare(strict_types=1);

use function App\Http\escapeHtml;

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatórios</title>
</head>
<body>
    <h1>Relatórios</h1>

    <h2>Faturas</h2>

    <form method="get" action="/relatorios.php">
        <label>
            Data início
            <input type="date" name="data_inicio" value="<?= escapeHtml((string) ($dataInicio ?? '')) ?>">
        </label>

        <label>
            Data fim
            <input type="date" name="data_fim" value="<?= escapeHtml((string) ($dataFim ?? '')) ?>">
        </label>

        <label>
            Cliente
            <select name="cliente_id">
                <option value="">Todos</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= escapeHtml((string) $cliente['id']) ?>" <?= $clienteId === (int) $cliente['id'] ? 'selected' : '' ?>>
                        <?= escapeHtml($cliente['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Status
            <select name="status">
                <option value="">Todos</option>
                <?php foreach (['aberta', 'parcial', 'paga', 'vencida'] as $opcaoStatus): ?>
                    <option value="<?= escapeHtml($opcaoStatus) ?>" <?= $status === $opcaoStatus ? 'selected' : '' ?>>
                        <?= escapeHtml($opcaoStatus) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <button type="submit">Filtrar</button>
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

    <h2>Receita por plano</h2>

    <form method="get" action="/relatorios.php">
        <input type="hidden" name="data_inicio" value="<?= escapeHtml((string) ($dataInicio ?? '')) ?>">
        <input type="hidden" name="data_fim" value="<?= escapeHtml((string) ($dataFim ?? '')) ?>">
        <input type="hidden" name="cliente_id" value="<?= escapeHtml((string) ($clienteId ?? '')) ?>">
        <input type="hidden" name="status" value="<?= escapeHtml((string) ($status ?? '')) ?>">
        <input type="hidden" name="dias_atraso" value="<?= escapeHtml((string) $diasAtraso) ?>">

        <label>
            Competência
            <input type="month" name="competencia" value="<?= escapeHtml($competencia) ?>">
        </label>

        <button type="submit">Consultar</button>
    </form>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Plano</th>
                <th>Clientes faturados</th>
                <th>Receita total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receitaPorPlano as $linha): ?>
                <tr>
                    <td><?= escapeHtml($linha['plano_nome']) ?></td>
                    <td><?= escapeHtml((string) $linha['quantidade_clientes']) ?></td>
                    <td><?= escapeHtml((string) $linha['receita_total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Inadimplência</h2>

    <form method="get" action="/relatorios.php">
        <input type="hidden" name="data_inicio" value="<?= escapeHtml((string) ($dataInicio ?? '')) ?>">
        <input type="hidden" name="data_fim" value="<?= escapeHtml((string) ($dataFim ?? '')) ?>">
        <input type="hidden" name="cliente_id" value="<?= escapeHtml((string) ($clienteId ?? '')) ?>">
        <input type="hidden" name="status" value="<?= escapeHtml((string) ($status ?? '')) ?>">
        <input type="hidden" name="competencia" value="<?= escapeHtml($competencia) ?>">

        <label>
            Dias de atraso
            <input type="number" name="dias_atraso" min="1" value="<?= escapeHtml((string) $diasAtraso) ?>">
        </label>

        <button type="submit">Consultar</button>
    </form>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Contato</th>
                <th>Competência</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
                <th>Dias em atraso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inadimplentes as $inadimplente): ?>
                <tr>
                    <td><?= escapeHtml($inadimplente['cliente_nome']) ?></td>
                    <td><?= escapeHtml($inadimplente['cliente_contato']) ?></td>
                    <td><?= escapeHtml($inadimplente['competencia']) ?></td>
                    <td><?= escapeHtml($inadimplente['valor']) ?></td>
                    <td><?= escapeHtml($inadimplente['data_vencimento']) ?></td>
                    <td><?= escapeHtml($inadimplente['status']) ?></td>
                    <td><?= escapeHtml((string) $inadimplente['dias_atraso']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
