<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: sistema_login.php");
    exit;
}

if (!isset($_POST['id_venda']) || !is_numeric($_POST['id_venda'])) {
    header("Location: relatorio_vendas_financeiro.php");
    exit;
}

$id_venda = intval($_POST['id_venda']);
$id_produto_array = $_POST['id_produto'];
$quantidade_array = $_POST['quantidade'];
$valor_unitario_array = $_POST['valor_unitario'];

// Ajusta estoque e atualiza itens
foreach ($id_produto_array as $id_item => $id_produto_novo) {

    $quantidade_nova = intval($quantidade_array[$id_item]);
    $valor_unitario_novo = floatval($valor_unitario_array[$id_item]);

    // Busca item antigo
    $sql_item = "SELECT id_produto, quantidade FROM itens_venda WHERE id = ?";
    $stmt_item = $conn->prepare($sql_item);
    $stmt_item->bind_param("i", $id_item);
    $stmt_item->execute();
    $res_item = $stmt_item->get_result();
    $item_antigo = $res_item->fetch_assoc();
    $stmt_item->close();

    // Se o produto mudou, ajusta estoque antigo
    if ($item_antigo['id_produto'] != $id_produto_novo) {
        // Retorna estoque do produto antigo
        $conn->query("UPDATE produtos SET qtde_estoque = qtde_estoque + {$item_antigo['quantidade']} WHERE id_produto = {$item_antigo['id_produto']}");
        // Baixa estoque do novo produto
        $conn->query("UPDATE produtos SET qtde_estoque = qtde_estoque - {$quantidade_nova} WHERE id_produto = {$id_produto_novo}");
    } else {
        // Produto igual, ajusta diferença de quantidade
        $diferenca = $quantidade_nova - $item_antigo['quantidade'];
        $conn->query("UPDATE produtos SET qtde_estoque = qtde_estoque - {$diferenca} WHERE id_produto = {$id_produto_novo}");
    }

    // Atualiza item
    $stmt_up = $conn->prepare("UPDATE itens_venda SET id_produto = ?, nome_produto = (SELECT nome_produto FROM produtos WHERE id_produto = ?), quantidade = ?, valor_unitario = ? WHERE id = ?");
    $stmt_up->bind_param("iiidi", $id_produto_novo, $id_produto_novo, $quantidade_nova, $valor_unitario_novo, $id_item);
    $stmt_up->execute();
    $stmt_up->close();
}

// Redireciona para relatório com alerta
header("Location: relatorio_vendas_financeiro.php?sucesso=editada");
exit;
