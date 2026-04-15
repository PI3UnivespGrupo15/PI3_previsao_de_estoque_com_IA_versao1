<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include 'conexao.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
    header("Location: registrar_venda.php?erro=carrinho_vazio");
    exit;
}

// Dados do formulário
$forma_pagamento = $_POST['forma_pagamento'] ?? '';
$data_venda = $_POST['data_venda'] ?? '';
$data_venda = str_replace('T', ' ', $data_venda);
$desconto = isset($_POST['desconto']) ? floatval($_POST['desconto']) : 0.00;

if (!$forma_pagamento) {
    header("Location: registrar_venda.php?erro=semformapagamento");
    exit;
}

if (!$data_venda) {
    header("Location: registrar_venda.php?erro=semdata");
    exit;
}

$conn->begin_transaction();

try {
    $total_venda = 0;

    // 1️⃣ Verifica estoque antes de registrar a venda
    foreach ($_SESSION['carrinho'] as $item) {
        $id_produto = $item['id_produto'];
        $quantidade = $item['quantidade'];

        $res = $conn->query("SELECT qtde_estoque FROM produtos WHERE id_produto = $id_produto");
        $produto = $res->fetch_assoc();

        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }

        if ($quantidade > $produto['qtde_estoque']) {
            throw new Exception("Quantidade solicitada maior que o estoque disponível para o produto {$item['nome']}.");
        }

        $total_venda += $quantidade * $item['valor_unitario'];
    }

    // Aplica desconto
    $total_venda -= $desconto;

    // 2️⃣ Inserir a venda
    $stmt_venda = $conn->prepare("INSERT INTO vendas (forma_pagamento, data_venda, total_venda, desconto) VALUES (?, ?, ?, ?)");
    $stmt_venda->bind_param("ssdd", $forma_pagamento, $data_venda, $total_venda, $desconto);
    $stmt_venda->execute();
    $id_venda = $conn->insert_id;

    // 3️⃣ Inserir itens da venda e atualizar estoque
    $stmt_item = $conn->prepare("INSERT INTO itens_venda (id_venda, id_produto, nome_produto, quantidade, valor_unitario) VALUES (?, ?, ?, ?, ?)");
    $stmt_update = $conn->prepare("UPDATE produtos SET qtde_estoque = qtde_estoque - ? WHERE id_produto = ?");

    foreach ($_SESSION['carrinho'] as $item) {
        $stmt_item->bind_param("iisid", $id_venda, $item['id_produto'], $item['nome'], $item['quantidade'], $item['valor_unitario']);
        $stmt_item->execute();

        $stmt_update->bind_param("ii", $item['quantidade'], $item['id_produto']);
        $stmt_update->execute();
    }

    $conn->commit();
    $_SESSION['carrinho'] = [];

    header("Location: registrar_venda.php?sucesso=1&id_venda=$id_venda");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    $erro = $e->getMessage();
    header("Location: registrar_venda.php?erro=" . urlencode($erro));
    exit;
}
?>
