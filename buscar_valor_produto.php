<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    http_response_code(403);
    exit("Acesso negado");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit("ID inválido");
}

$id = intval($_GET['id']);

// Conexão com o banco
$host = 'sql201.infinityfree.com';
$usuario = 'if0_39007414';
$senha = 'SibfxkGyuneA6';
$banco = 'if0_39007414_bdcontroleestoque';

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    http_response_code(500);
    exit("Erro na conexão");
}

$sql = "SELECT valor_unitario, qtde_estoque FROM produtos WHERE id_produto = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit("Erro na preparação da consulta");
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: application/json');

if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();
    echo json_encode([
        'valor_unitario' => (float) $produto['valor_unitario'],
        'qtde_estoque' => (int) $produto['qtde_estoque']
    ]);
} else {
    http_response_code(404);
    echo json_encode(['valor_unitario' => 0.00, 'qtde_estoque' => 0]);
}

$stmt->close();
$conn->close();
