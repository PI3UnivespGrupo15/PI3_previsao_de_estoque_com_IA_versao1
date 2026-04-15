<?php
$host = "sql201.infinityfree.com"; //host do banco de dados no infintyfree
$user = "if0_39007414";  // nome de usuário do MySQL
$pass = "SibfxkGyuneA6"; // senha do banco de dados
$db   = "if0_39007414_bdcontroleestoque"; //nome do banco de dados no infinityfree

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

return $conn;
?>
