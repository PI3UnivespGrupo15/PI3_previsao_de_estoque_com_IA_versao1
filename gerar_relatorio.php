<?php
$conn = include 'conexao.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$dias = isset($_GET['dias']) ? (int)$_GET['dias'] : 30;

header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="relatorio_vendas_' . $dias . '_dias.html"');
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr><th>ID Venda</th><th>Produto</th><th>Quantidade</th><th>Data da Venda</th></tr>";

$sql = "SELECT v.id_venda, iv.nome_produto, iv.quantidade, v.data_venda 
        FROM vendas v
        JOIN itens_venda iv ON v.id_venda = iv.id_venda
        WHERE DATE(v.data_venda) >= CURDATE() - INTERVAL ? DAY 
        ORDER BY v.data_venda DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dias);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "<tr><td colspan='4'>Erro ao gerar relatório: " . htmlspecialchars($conn->error) . "</td></tr>";
} else {
    if ($result->num_rows == 0) {
        echo "<tr><td colspan='4'>Nenhuma venda encontrada nesse período.</td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_venda']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome_produto']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantidade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['data_venda']) . "</td>";
            echo "</tr>";
        }
    }
}

echo "</table>";

$stmt->close();
$conn->close();
exit;
?>
