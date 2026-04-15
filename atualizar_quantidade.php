<?php
session_start();
if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];

if (!empty($_POST['quantidade'])) {
    foreach ($_POST['quantidade'] as $index => $qtd) {
        $qtd = (int)$qtd;
        if ($qtd > 0 && isset($_SESSION['carrinho'][$index])) {
            $_SESSION['carrinho'][$index]['quantidade'] = $qtd;
        }
    }
}

header("Location: registrar_venda.php");
exit;
