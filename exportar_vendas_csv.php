<?php
// Conexão com o banco de dados (ajuste conforme seu ambiente)
$host = 'sql201.infinityfree.com';
$usuario = 'if0_39007414';
$senha = 'SibfxkGyuneA6';
$banco = 'if0_39007414_bdcontroleestoque';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=vendas.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['id_produto', 'nome_produto', 'data', 'total_vendido']);

$sql = "
    SELECT 
        iv.id_produto,
        iv.nome_produto,
        DATE(v.data_venda) as data,
        SUM(iv.quantidade) as total_vendido
    FROM itens_venda iv
    JOIN vendas v ON iv.id_venda = v.id_venda
    WHERE v.data_venda >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY iv.id_produto, DATE(v.data_venda)
    ORDER BY data ASC
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>