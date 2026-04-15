<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'conexao.php';

$data_selecionada = $_GET['data'] ?? '';
$vendas = [];

if ($data_selecionada !== '') {
    // Buscar vendas no dia selecionado (considerando o campo data_venda com data e hora)
    $inicio_dia = $data_selecionada . ' 00:00:00';
    $fim_dia = $data_selecionada . ' 23:59:59';

    $sql = "SELECT id_venda, data_venda, forma_pagamento, total_venda
            FROM vendas
            WHERE data_venda BETWEEN ? AND ?
            ORDER BY data_venda DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param("ss", $inicio_dia, $fim_dia);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendas = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Venda por Data para Imprimir Cupom</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        a.btn { background-color: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h2>Buscar Venda por Data para Imprimir Cupom</h2>
    <form method="GET" action="">
        <label for="data">Selecione a data:</label><br>
        <input type="date" id="data" name="data" value="<?= htmlspecialchars($data_selecionada) ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($data_selecionada !== ''): ?>
        <?php if (count($vendas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Venda</th>
                        <th>Data</th>
                        <th>Forma Pagamento</th>
                        <th>Total</th>
                        <th>Imprimir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendas as $v): ?>
                        <tr>
                            <td><?= $v['id_venda'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?></td>
                            <td><?= htmlspecialchars($v['forma_pagamento']) ?></td>
                            <td>R$ <?= number_format($v['total_venda'], 2, ',', '.') ?></td>
                            <td><a href="imprimir_cupom.php?id_pedido=<?= $v['id_venda'] ?>" target="_blank" class="btn">Imprimir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma venda encontrada na data selecionada.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php include 'acessibilidade.php'; ?>

</body>
</html>
