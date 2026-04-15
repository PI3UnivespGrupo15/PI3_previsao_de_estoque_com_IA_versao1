<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    exit("Usuário não logado.");
}

include "conexao.php";

if (!isset($_POST['id_venda']) || empty($_POST['id_venda'])) {
    exit("Venda inválida.");
}

$id_venda = intval($_POST['id_venda']);

// Consulta os itens da venda
$stmt = $conn->prepare("
    SELECT id_produto, nome_produto, quantidade, valor_unitario
    FROM itens_venda
    WHERE id_venda = ?
");
$stmt->bind_param("i", $id_venda);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit(""); // Nenhum item encontrado
}

$html = "";

while ($row = $result->fetch_assoc()) {
    $total = $row['quantidade'] * $row['valor_unitario'];
    $id_produto = htmlspecialchars($row['id_produto']);
    $nome_produto = htmlspecialchars($row['nome_produto']);
    $valor_unitario = number_format($row['valor_unitario'], 2, ',', '.');
    $total_formatado = number_format($total, 2, ',', '.');

    $html .= "
    <tr>
        <td>
            <input type='checkbox' class='chkDevolver' 
                   data-id='{$id_produto}' 
                   data-valor='{$row['valor_unitario']}' 
                   checked>
        </td>
        <td>{$nome_produto}</td>
        <td>
            <input type='number' class='qtdeDevolver' 
                   value='{$row['quantidade']}' 
                   min='1' max='{$row['quantidade']}' 
                   style='width:70px; text-align:center'>
        </td>
        <td>R$ {$valor_unitario}</td>
        <td class='total-item'>R$ {$total_formatado}</td>
    </tr>
    ";
}



echo $html;
