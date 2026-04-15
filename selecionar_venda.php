<?php
session_start();
include 'conexao.php';

// Buscar última venda
$sqlUltima = "SELECT id, data_venda FROM vendas ORDER BY data_venda DESC LIMIT 1";
$resUltima = $conn->query($sqlUltima);
$ultimaVenda = $resUltima->fetch_assoc();

// Buscar últimas 20 vendas para listar
$sqlVendas = "SELECT id, data_venda FROM vendas ORDER BY data_venda DESC LIMIT 20";
$resVendas = $conn->query($sqlVendas);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Selecionar Venda para Imprimir</title>
</head>
<body>
    <h2>Última Venda</h2>
    <?php if ($ultimaVenda): ?>
        <p>ID: <?= $ultimaVenda['id'] ?> - Data: <?= $ultimaVenda['data_venda'] ?></p>
        <button onclick="imprimirVenda(<?= $ultimaVenda['id'] ?>)">Imprimir Última Venda</button>
    <?php else: ?>
        <p>Nenhuma venda encontrada.</p>
    <?php endif; ?>

    <h3>Outras Vendas</h3>
    <select id="vendaSelect">
        <option value="">-- Selecione uma venda --</option>
        <?php while ($v = $resVendas->fetch_assoc()): ?>
            <option value="<?= $v['id'] ?>">ID: <?= $v['id'] ?> - <?= $v['data_venda'] ?></option>
        <?php endwhile; ?>
    </select>
    <button onclick="imprimirVendaSelecionada()">Imprimir Selecionada</button>

    <script>
    function imprimirVenda(id) {
        if (!id) {
            alert('ID da venda inválido');
            return;
        }
        window.open('imprimir_cupom.php?id_pedido=' + id, '_blank');
    }

    function imprimirVendaSelecionada() {
        const select = document.getElementById('vendaSelect');
        const id = select.value;
        if (!id) {
            alert('Selecione uma venda para imprimir');
            return;
        }
        imprimirVenda(id);
    }
    </script>
</body>
</html>
