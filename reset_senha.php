<?php
$host = "sql201.infinityfree.com";
$usuarioBD = "if0_39007414";
$senhaBD = "SibfxkGyuneA6";
$banco = "if0_39007414_bdcontroleestoque";

// Conexão
$conn = new mysqli($host, $usuarioBD, $senhaBD, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Usuário e nova senha
$usuario = 'piunivesp';
$novaSenha = 'grupo11';
$hash = password_hash($novaSenha, PASSWORD_DEFAULT);

// Atualizar senha no banco
$stmt = $conn->prepare("UPDATE usuarios SET senha=? WHERE usuario=?");
$stmt->bind_param("ss", $hash, $usuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Senha atualizada com sucesso!";
} else {
    echo "Usuário não encontrado ou senha já estava atualizada.";
}

$stmt->close();
$conn->close();
?>
