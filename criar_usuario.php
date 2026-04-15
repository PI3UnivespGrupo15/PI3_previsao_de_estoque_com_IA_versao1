<?php
    ini_set('display_errors', 1);
error_reporting(E_ALL);
$host = "sql201.infinityfree.com";
$usuarioBD = "if0_39007414";
$senhaBD = "SibfxkGyuneA6";
$banco = "if0_39007414_bdcontroleestoque";

// Conexão
$conn = new mysqli($host, $usuarioBD, $senhaBD, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Novo usuário
$usuarioNovo = 'piunivesp3';
$senhaNova = 'grupo15';
$hash = password_hash($senhaNova, PASSWORD_DEFAULT);

// Inserir no banco
$stmt = $conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
$stmt->bind_param("ss", $usuarioNovo, $hash);
if ($stmt->execute()) {
    echo "Usuário '$usuarioNovo' criado com sucesso!";
} else {
    echo "Erro ao criar usuário: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
