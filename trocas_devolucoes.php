<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
include "conexao.php";
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

// 🔹 Filtro por data
$data_filtro = isset($_GET["data_venda"]) ? $_GET["data_venda"] : '';

// 🔹 Buscar vendas e status de troca (funciona mesmo se não houver trocas)
$sql = "
SELECT 
    v.id_venda, 
    v.data_venda, 
    v.total_venda,
    t.id_venda_original AS troca_realizada
FROM vendas v
LEFT JOIN trocas t ON t.id_venda_original = v.id_venda
";

if ($data_filtro != "") {
    $sql .= " WHERE DATE(v.data_venda) = ?";
}

$sql .= " ORDER BY v.data_venda DESC";

$stmt = $conn->prepare($sql);
if ($data_filtro != "") {
    $stmt->bind_param("s", $data_filtro);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Trocas e Devoluções</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin:0; padding:0; }
.container { width:95%; max-width:1200px; margin:20px auto; }
.card { background:#fff; border-radius:10px; padding:20px; margin-bottom:20px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; text-align:center; border:1px solid #ccc; }
th { background-color:#007bff; color:white; }
tr.produtos td { background:#f9f9f9; text-align:left; }
button { padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin:2px; }
.verProdutos { background-color:#17a2b8; color:white; }
.fazerTroca { background-color:#28a745; color:white; }
#filtros { margin-bottom:15px; text-align:center; }
#filtros input[type="date"] { padding:6px; border-radius:4px; border:1px solid #ccc; }
#topButtons { display:flex; justify-content:center; gap:10px; margin-bottom:20px; }
#topButtons button { padding:10px 20px; font-weight:bold; }
.voltar { background-color:#6c757d; color:white; }
.menuInicial { background-color:#007bff; color:white; }
</style>
</head>
<body>
    
    <!-- Inclui o botão e script de acessibilidade -->
    <?php include 'acessibilidade.php'; ?>

<div class="container">

    <!-- 🔹 Botões topo -->
    <div id="topButtons">
        <button class="voltar" onclick="history.back()">Voltar</button>
        <button class="menuInicial" onclick="window.location.href='index.php'">Página Inicial</button>
    </div>

    <!-- 🔹 Filtro por data -->
    <div id="filtros" class="card">
        <form method="GET">
            <label>Data da venda:</label>
            <input type="date" name="data_venda" value="<?= htmlspecialchars($data_filtro) ?>">
            <button type="submit" style="background-color:#28a745;color:white;">Filtrar</button>
        </form>
    </div>

    <!-- 🔹 Tabela de vendas -->
    <div class="card">
        <h2 style="text-align:center;">Vendas Realizadas</h2>
        <table>
        <thead>
        <tr>
            <th>ID Venda</th>
            <th>Data</th>
            <th>Total Venda (R$)</th>
            <th>Status Troca</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()):
            $status_troca = $row['troca_realizada'] ? 'Realizada' : 'Pendente';
        ?>
        <tr>
            <td><?= $row["id_venda"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($row["data_venda"])) ?></td>
            <td>R$ <?= number_format($row["total_venda"], 2, ',', '.') ?></td>
            <td><?= $status_troca ?></td>
            <td>
                <button class="verProdutos" data-id="<?= $row["id_venda"] ?>">Ver Produtos</button>
                <?php if($status_troca == 'Pendente'): ?>
                    <button class="fazerTroca" onclick="window.location.href='registrar_troca.php?id_venda=<?= $row["id_venda"] ?>'">Fazer Troca</button>
                <?php else: ?>
                    <!-- Botão vermelho inativo -->
                    <button style="background-color:#dc3545; color:white; cursor:not-allowed; border:none; border-radius:5px; padding:6px 12px;" disabled>Troca Realizada</button>
                <?php endif; ?>
            </td>
        </tr>
        <tr class="produtos" id="produtos-<?= $row["id_venda"] ?>" style="display:none;">
            <td colspan="5">Carregando produtos...</td>
        </tr>
        <?php endwhile; ?>
        </tbody>
        </table>
    </div>

</div>

<script>
// 🔸 Exibe produtos da venda
$(".verProdutos").on("click", function(){
    const idVenda = $(this).data("id");
    const linha = $("#produtos-" + idVenda);
    if(linha.is(":visible")){
        linha.hide();
        return;
    }
    $.post("buscar_venda_troca.php", { id_venda: idVenda }, function(retorno){
        if(retorno.trim() !== ""){
            linha.html("<td colspan='5'><table style='width:100%; border-collapse:collapse;'><tr><th>Produto</th><th>Qtd</th><th>Valor Unitário (R$)</th><th>Total (R$)</th></tr>" +
                       retorno +
                       "</table></td>");
        } else {
            linha.html("<td colspan='5'>Nenhum produto encontrado.</td>");
        }
        linha.show();
    });
});
</script>

</body>
</html>
