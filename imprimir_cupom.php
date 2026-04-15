<?php
session_start();
include 'conexao.php';

$id_pedido = $_GET['id_pedido'] ?? null;
if (!$id_pedido) {
    echo "ID do pedido não informado.";
    exit;
}

$sqlVenda = "SELECT * FROM vendas WHERE id_venda = ?";
$stmtVenda = $conn->prepare($sqlVenda);
$stmtVenda->bind_param("i", $id_pedido);
$stmtVenda->execute();
$resVenda = $stmtVenda->get_result();
$venda = $resVenda->fetch_assoc();

if (!$venda) {
    echo "Venda não encontrada.";
    exit;
}

$sqlItens = "SELECT * FROM itens_venda WHERE id_venda = ?";
$stmtItens = $conn->prepare($sqlItens);
$stmtItens->bind_param("i", $id_pedido);
$stmtItens->execute();
$resItens = $stmtItens->get_result();
$itens = $resItens->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cupom Venda #<?= $id_pedido ?></title>
    <style>
        body { font-family: monospace; width: 300px; margin: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <!-- Linhas centralizadas -->
    <div style="text-align: center; font-size: 12px;">
        <h2>Loja Eletrônicos</h2>
    </div>
    <div style="text-align: center; font-size: 8px;">
        <h2>CNPJ: 01234567/0001-89</h2>
        <h2>Rua 1, nº 123</h2>
        <h2>Telefone: 16-912345678</h2>
        <h2>Comprovante de venda</h2>
    </div>

    <!-- Linhas não centralizadas -->

    <p>Venda #: <?= $id_pedido ?></p>
    <p>Data: <?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></p>
    <p>Forma de Pagamento: <?= htmlspecialchars($venda['forma_pagamento']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($itens as $item) {
                $subtotal = $item['quantidade'] * $item['valor_unitario'];
                $total += $subtotal;
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['nome_produto']) . "</td>";
                echo "<td>" . $item['quantidade'] . "</td>";
                echo "<td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
                echo "</tr>";
            }
            ?>
            <tr class="total">
                <td colspan="2">Subtotal</td>
                <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
            </tr>
            <?php if (isset($venda['desconto']) && floatval($venda['desconto']) > 0): ?>
            <tr class="total">
                <td colspan="2">Desconto</td>
                <td>- R$ <?= number_format($venda['desconto'], 2, ',', '.') ?></td>
            </tr>
            <tr class="total">
                <td colspan="2"><strong>Total com desconto</strong></td>
                <td><strong>R$ <?= number_format($total - $venda['desconto'], 2, ',', '.') ?></strong></td>
            </tr>
            <?php else: ?>
            <tr class="total">
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="text-align: center; font-size: 8px;">
        <h2>Sem valor Fiscal</h2>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
