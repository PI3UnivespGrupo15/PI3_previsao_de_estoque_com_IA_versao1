<?php
session_start();

// Verifica se o usuário está logado
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
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se veio produto e quantidade
if (!isset($_POST['id_produto'], $_POST['qtde_vendida'])) {
    header("Location: registrar_venda.php?erro=dados_invalidos");
    exit;
}

$id_produto   = intval($_POST['id_produto']);
$quantidade   = intval($_POST['qtde_vendida']);
$valor_form   = isset($_POST['valor_unitario']) ? floatval($_POST['valor_unitario']) : 0;

// Quantidade inválida
if ($quantidade <= 0) {
    header("Location: registrar_venda.php?erro=quantidade_invalida");
    exit;
}

// Busca o produto no banco (garante valor correto)
$sql = "SELECT nome_produto, valor_unitario FROM produtos WHERE id_produto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_produto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: registrar_venda.php?erro=produto_nao_encontrado");
    exit;
}

$produto = $result->fetch_assoc();
$nome_produto   = $produto['nome_produto'];
$valor_banco    = (float) $produto['valor_unitario'];

// Se o hidden vier vazio/zerado, usamos o valor do banco
$valor_unitario = ($valor_form > 0) ? $valor_form : $valor_banco;

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Verifica se produto já existe no carrinho → soma quantidades
$existe = false;
foreach ($_SESSION['carrinho'] as &$item) {
    if ($item['id_produto'] == $id_produto) {
        $item['quantidade'] += $quantidade;
        $existe = true;
        break;
    }
}

// Se não existe, adiciona novo item
if (!$existe) {
    $_SESSION['carrinho'][] = [
        'id_produto'    => $id_produto,
        'nome'          => $nome_produto,
        'quantidade'    => $quantidade,
        'valor_unitario'=> $valor_unitario
    ];
}

$stmt->close();
$conn->close();

header("Location: registrar_venda.php");
exit;
