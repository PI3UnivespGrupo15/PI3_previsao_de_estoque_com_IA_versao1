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

$id_venda = $_POST['id_venda'] ?? null;

if (!$id_venda) {
    echo "ID da venda não informado";
    exit;
}


$conn->begin_transaction();

try {
    // Buscar os itens da venda
    $sql_itens = "SELECT id_produto, quantidade FROM itens_venda WHERE id_venda = ?";
    $stmt_itens = $conn->prepare($sql_itens);
    $stmt_itens->bind_param("i", $id_venda);
    $stmt_itens->execute();
    $result_itens = $stmt_itens->get_result();

    while ($item = $result_itens->fetch_assoc()) {
        $id_produto = $item['id_produto'];
        $quantidade = $item['quantidade'];

        // Repor estoque
        $sql_update_estoque = "UPDATE produtos SET qtde_estoque = qtde_estoque + ? WHERE id_produto = ?";
        $stmt_update = $conn->prepare($sql_update_estoque);
        $stmt_update->bind_param("ii", $quantidade, $id_produto);
        $stmt_update->execute();
    }

    // Excluir itens da venda
    $stmt_delete_itens = $conn->prepare("DELETE FROM itens_venda WHERE id_venda = ?");
    $stmt_delete_itens->bind_param("i", $id_venda);
    $stmt_delete_itens->execute();

    // Excluir a venda
    $stmt_delete_venda = $conn->prepare("DELETE FROM vendas WHERE id_venda = ?");
    $stmt_delete_venda->bind_param("i", $id_venda);
    $stmt_delete_venda->execute();

    $conn->commit();

    header("Location: relatorio_vendas_financeiro.php?mensagem=Venda excluída com sucesso");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "Erro ao excluir venda: " . $e->getMessage();
    exit;
}
?>
