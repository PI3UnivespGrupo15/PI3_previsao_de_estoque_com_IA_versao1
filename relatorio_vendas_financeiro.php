<?php
    ini_set('display_errors', 1);
	error_reporting(E_ALL);
session_start();
include 'conexao.php';

// Verifica período selecionado
$periodo = $_GET['periodo'] ?? 'hoje';
$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';

if (!empty($data_inicio) && !empty($data_fim)) {

    $condicao_data = "DATE(data_venda) BETWEEN '$data_inicio' AND '$data_fim'";

} else {

    if ($periodo == 'hoje') {
        $condicao_data = "DATE(data_venda) = CURDATE()";
    } elseif ($periodo == '7dias') {
        $condicao_data = "data_venda >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } else {
        $condicao_data = "data_venda >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    }
}


switch ($periodo) {
    case '7dias':
        $dataFiltro = "DATE(v.data_venda) >= CURDATE() - INTERVAL 7 DAY";
        break;
    case '30dias':
        $dataFiltro = "DATE(v.data_venda) >= CURDATE() - INTERVAL 30 DAY";
        break;
    case 'hoje':
    default:
        $dataFiltro = "DATE(v.data_venda) = CURDATE()";
        break;
}

// Consulta todas as vendas com os itens
$sql = "
SELECT 
    v.id_venda,
    v.data_venda,
    v.forma_pagamento,
    v.desconto,
    i.id_venda AS id_venda_item,
    i.nome_produto,
    i.quantidade,
    i.valor_unitario
FROM vendas v
JOIN itens_venda i ON v.id_venda = i.id_venda
WHERE $condicao_data

ORDER BY v.data_venda DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();

$vendas = [];
while ($row = $res->fetch_assoc()) {
    $vendas[$row['id_venda']]['data_venda'] = $row['data_venda'];
    $vendas[$row['id_venda']]['forma_pagamento'] = $row['forma_pagamento'];
    $vendas[$row['id_venda']]['desconto_total'] = $row['desconto'] ?? 0.0;
    $vendas[$row['id_venda']]['itens'][] = $row;
}

// Define taxas por forma de pagamento
$taxas = [
    'Cartão de Crédito' => 0.047,
    'Cartão de Débito' => 0.02,
    'Pix' => 0.01,
    'Dinheiro' => 0.00,
];

// Totais finais
$total_bruto = 0;
$total_taxas = 0;
$total_descontos = 0;
$total_liquido = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Vendas</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 6px; border: 1px solid #ccc; text-align: left; font-size: 13px; }
        th { background-color: #f2f2f2; }
        .topo { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .topo a { text-decoration: none; background: #007bff; color: white; padding: 6px 12px; border-radius: 5px; }
        .filtros { margin-bottom: 15px; }
        .excluir-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #dc3545;
            transition: color 0.3s ease;
        }
        .excluir-btn:hover {
            color: #a71d2a;
        }
    </style>
</head>
<body>

<div class="topo">
    <a href="pagina_inicial.php">← Voltar ao Menu</a>

    <form method="get" class="filtros">

        <label>Período:
            <select name="periodo" onchange="this.form.submit()">
                <option value="hoje" <?= $periodo == 'hoje' ? 'selected' : '' ?>>Hoje</option>
                <option value="7dias" <?= $periodo == '7dias' ? 'selected' : '' ?>>Últimos 7 dias</option>
                <option value="30dias" <?= $periodo == '30dias' ? 'selected' : '' ?>>Últimos 30 dias</option>
            </select>
        </label>

        <label>De:
            <input type="date" name="data_inicio" value="<?= $_GET['data_inicio'] ?? '' ?>">
        </label>

        <label>Até:
            <input type="date" name="data_fim" value="<?= $_GET['data_fim'] ?? '' ?>">
        </label>

        <button type="submit">Filtrar</button>
        <a href="relatorio_vendas_financeiro.php" class="btn-limpar">Limpar</a>


    </form>
</div>


<h2>Relatório Financeiro de Vendas</h2>

<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Forma de Pagamento</th>
            <th>Produto</th>
            <th>Qtd</th>
            <th>Valor Unitário</th>
            <th>Desconto</th>
            <th>Taxas</th>
            <th>Valor Líquido</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vendas as $id_venda => $venda): 
            $forma = $venda['forma_pagamento'];
            $desconto_total = floatval($venda['desconto_total']);
            $taxa_percentual = $taxas[$forma] ?? 0.00;

            $total_sem_desconto = 0;
            foreach ($venda['itens'] as $item) {
                $total_sem_desconto += $item['quantidade'] * $item['valor_unitario'];
            }

            $primeiroItem = true;
            foreach ($venda['itens'] as $item):
                $quantidade = $item['quantidade'];
                $unitario = $item['valor_unitario'];
                $subtotal = $quantidade * $unitario;

                $desconto_item = $total_sem_desconto > 0 ? ($subtotal / $total_sem_desconto) * $desconto_total : 0;
                $base_taxa = $subtotal - $desconto_item;
                $valor_taxa = $base_taxa * $taxa_percentual;
                $valor_liquido = $base_taxa - $valor_taxa;

                $total_bruto += $subtotal;
                $total_descontos += $desconto_item;
                $total_taxas += $valor_taxa;
                $total_liquido += $valor_liquido;
        ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
            <td><?= htmlspecialchars($forma) ?></td>
            <td><?= htmlspecialchars($item['nome_produto']) ?></td>
            <td><?= $quantidade ?></td>
            <td>R$ <?= number_format($unitario, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($desconto_item, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($valor_taxa, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($valor_liquido, 2, ',', '.') ?></td>
            <td>
                <?php if ($primeiroItem): ?>
                <form method="POST" action="excluir_venda.php" onsubmit="return confirm('Tem certeza que deseja excluir a venda ID <?= $id_venda ?>?');" style="margin:0;">
                    <input type="hidden" name="id_venda" value="<?= $id_venda ?>">
                    <button type="submit" class="excluir-btn" title="Excluir Venda">
                        🗑️
                    </button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php 
            $primeiroItem = false;
            endforeach; 
        endforeach; 
        ?>
    </tbody>
</table>

<h3>Totais</h3>
<ul>
    <li><strong>Bruto:</strong> R$ <?= number_format($total_bruto, 2, ',', '.') ?></li>
    <li><strong>Descontos:</strong> R$ <?= number_format($total_descontos, 2, ',', '.') ?></li>
    <li><strong>Taxas:</strong> R$ <?= number_format($total_taxas, 2, ',', '.') ?></li>
    <li><strong>Valor Líquido:</strong> R$ <?= number_format($total_liquido, 2, ',', '.') ?></li>
</ul>

</body>
</html>
