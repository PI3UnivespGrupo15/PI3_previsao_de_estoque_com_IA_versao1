<?php
include 'conexao.php';

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "SELECT id_produto, nome_produto FROM produtos WHERE codigo_barras = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }
}
?>
