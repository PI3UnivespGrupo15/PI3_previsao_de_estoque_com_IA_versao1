<?php
    
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    
session_start();
include 'conexao.php'; // garante conexão com o banco

if (!isset($_GET['id_venda'])) {
    echo json_encode(['erro' => 'ID da venda não informado.']);
    exit;
}

$id_venda = intval($_GET['id_venda']);

// busca os itens da venda original
$sql = "SELECT id_produto, nome_produto, quantidade, valor_unitario 
        FROM itens_venda 
        WHERE id_venda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venda);
$stmt->execute();
$resultado = $stmt->get_result();

$itens = [];
while ($linha = $resultado->fetch_assoc()) {
    $itens[] = $linha;
}

if (count($itens) === 0) {
    echo json_encode(['erro' => 'Venda não encontrada ou sem itens.']);
    exit;
}

// salva no carrinho da sessão
$_SESSION['carrinho'] = []; // limpa o carrinho atual

foreach ($itens as $item) {
    $_SESSION['carrinho'][] = [
        'id_produto' => $item['id_produto'],
        'nome' => $item['nome_produto'],
        'quantidade' => $item['quantidade'],
        'valor_unitario' => $item['valor_unitario']
    ];
}

// retorna sucesso para o JavaScript
echo json_encode(['sucesso' => true, 'itens' => $itens]);
?>
