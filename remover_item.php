<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

// Verifica se foi passado o ID do produto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: registrar_venda.php?erro=id_invalido");
    exit;
}

$id_produto = intval($_GET['id']);

// Verifica se existe carrinho ativo
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $index => $item) {
        if ($item['id_produto'] == $id_produto) {
            unset($_SESSION['carrinho'][$index]); // remove o item
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // reorganiza os índices
            break;
        }
    }
}

// Redireciona de volta para o carrinho
header("Location: registrar_venda.php");
exit;
