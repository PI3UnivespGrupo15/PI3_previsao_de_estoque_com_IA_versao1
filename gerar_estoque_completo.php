<?php

include 'conexao.php';

// Headers para forçar download como arquivo .html
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="estoque_completo.html"');
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr><th>Produto</th><th>Quantidade em Estoque</th></tr>";

$sql = "SELECT nome_produto, qtde_estoque FROM produtos ORDER BY nome_produto";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['nome_produto']) . "</td>";
    echo "<td>" . htmlspecialchars($row['qtde_estoque']) . "</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>
