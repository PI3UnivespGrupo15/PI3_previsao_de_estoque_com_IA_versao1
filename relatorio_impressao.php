<?php
session_start();

// ==========================
// CONEXÃO
// ==========================
$host = 'sql201.infinityfree.com';
$usuario = 'if0_39007414';
$senha = 'SibfxkGyuneA6';
$banco = 'if0_39007414_bdcontroleestoque';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// ==========================
// GERAR RELATÓRIO (MESMA LÓGICA)
// ==========================
$relatorio_repor = [];

$sql_produtos = "SELECT id_produto, nome_produto, qtde_estoque FROM produtos";
$result_produtos = $conn->query($sql_produtos);

while ($produto = $result_produtos->fetch_assoc()) {

    $id_produto = $produto["id_produto"];
    $nome = $produto["nome_produto"];
    $estoque = $produto["qtde_estoque"];

    $sql_vendas = "
        SELECT 
        SUM(iv.quantidade) as total_vendido
        FROM itens_venda iv
        JOIN vendas v ON iv.id_venda = v.id_venda
        WHERE iv.id_produto = $id_produto
        AND v.data_venda >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    ";

    $result_vendas = $conn->query($sql_vendas);
    $row = $result_vendas->fetch_assoc();

    $total_vendido = $row["total_vendido"] ?? 0;

    $previsao = round($total_vendido / 6);

    $repor = $previsao - $estoque;
    if ($repor < 0) {
        $repor = 0;
    }

    if ($repor > 0) {
        $relatorio_repor[] = [
            "produto" => $nome,
            "estoque" => $estoque,
            "previsao" => $previsao,
            "repor" => $repor
        ];
    }
}

// ==========================
// RESUMO
// ==========================
$total_itens = count($relatorio_repor);
$total_repor = 0;

foreach ($relatorio_repor as $item) {
    $total_repor += $item["repor"];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Relatório de Reposição</title>

<style>
body {
    font-family: Arial;
    padding: 30px;
}

h1 {
    text-align: center;
    margin-bottom: 5px;
}

.subtitulo {
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #333;
    color: white;
}

td, th {
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
}

.alerta {
    color: red;
    font-weight: bold;
}

.resumo {
    margin-top: 20px;
    font-weight: bold;
    font-size: 16px;
}

@media print {
    button {
        display: none;
    }
}
</style>
</head>

<body>

<h1>📦 Relatório de Reposição de Estoque</h1>
<p class="subtitulo">Gerado em: <?= date('d/m/Y H:i') ?></p>

<table>
<tr>
    <th>Produto</th>
    <th>Estoque Atual</th>
    <th>Previsão (mensal)</th>
    <th>Quantidade para Repor</th>
</tr>

<?php foreach ($relatorio_repor as $item): ?>
<tr>
    <td><?= $item["produto"] ?></td>
    <td><?= $item["estoque"] ?></td>
    <td><?= $item["previsao"] ?></td>
    <td class="alerta"><?= $item["repor"] ?></td>
</tr>
<?php endforeach; ?>

</table>

<div class="resumo">
    Total de produtos a repor: <?= $total_itens ?><br>
    Total de itens a comprar: <?= $total_repor ?>
</div>

<script>
    window.print();
</script>

</body>
</html>