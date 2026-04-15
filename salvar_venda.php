<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

// Conexão com o banco
$host = 'sql201.infinityfree.com';
$usuario = 'if0_39007414';
$senha = 'SibfxkGyuneA6';
$banco = 'if0_39007414_bdcontroleestoque';

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    exit("Erro de conexão: " . $conn->connect_error);
}

// Receber dados da venda
$data_venda = $_POST['data_venda'];
$forma_pagamento = $_POST['forma_pagamento'];
$total_venda = floatval(str_replace(',', '.', $_POST['total_venda']));

// Receber carrinho — exemplo com sessão
if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
    header("Location: registrar_venda.php?erro=carrinho_vazio");
    exit;
}

$carrinho = $_SESSION['carrinho'];

$conn->begin_transaction();

try {
    // Inserir dados da venda
    $sql_venda = "INSERT INTO vendas (data_venda, forma_pagamento, total_venda) VALUES (?, ?, ?)";
    $stmt_venda = $conn->prepare($sql_venda);
    $stmt_venda->bind_param("ssd", $data_venda, $forma_pagamento, $total_venda);
    $stmt_venda->execute();

    $id_venda = $conn->insert_id;

    // Para cada item no carrinho
    foreach ($carrinho as $item) {
        $id_produto = intval($item['id_produto']);
        $quantidade = intval($item['quantidade']);

        // Verificar estoque atual
        $sql_estoque = "SELECT qtde_estoque FROM produtos WHERE id_produto = ?";
        $stmt_estoque = $conn->prepare($sql_estoque);
        $stmt_estoque->bind_param("i", $id_produto);
        $stmt_estoque->execute();
        $result_estoque = $stmt_estoque->get_result();
        $produto = $result_estoque->fetch_assoc();

        if (!$produto || $produto['qtde_estoque'] < $quantidade) {
            throw new Exception("Estoque insuficiente para o produto ID: $id_produto");
        }

        // Inserir item da venda
        $sql_item = "INSERT INTO itens_venda (id_venda, id_produto, quantidade) VALUES (?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        $stmt_item->bind_param("iii", $id_venda, $id_produto, $quantidade);
        $stmt_item->execute();

        // Atualizar estoque
        $sql_update = "UPDATE produtos SET qtde_estoque = qtde_estoque - ? WHERE id_produto = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $quantidade, $id_produto);
        $stmt_update->execute();
    }

    $conn->commit();

    // Limpar carrinho
    unset($_SESSION['carrinho']);

    header("Location: registrar_venda.php?sucesso=1");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    $erro = $e->getMessage();
    header("Location: registrar_venda.php?erro=" . urlencode($erro));
    exit;
}
?>
