<?php

if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

// Conexão com o banco de dados (ajuste conforme seu ambiente)
$host = 'sql201.infinityfree.com';
$usuario = 'if0_39007414';
$senha = 'SibfxkGyuneA6';
$banco = 'if0_39007414_bdcontroleestoque';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome = trim($_POST["nome_produto"]);
$qtde = intval($_POST["qtde_estoque"]);

// Insere no banco de dados
$stmt = $conn->prepare("INSERT INTO produtos (nome_produto, qtde_estoque) VALUES (?, ?)");
$stmt->bind_param("si", $nome, $qtde);

if ($stmt->execute()) {
    echo "<p>✅ Produto cadastrado com sucesso!</p>";
    echo "<a href='index.php'>Voltar ao Menu</a>";
} else {
    echo "<p>❌ Erro ao cadastrar: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>