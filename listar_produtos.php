<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

$host = "sql201.infinityfree.com";
$porta = 3306;
$usuarioBD = "if0_39007414";
$senhaBD = "SibfxkGyuneA6";
$banco = "if0_39007414_bdcontroleestoque";

$conn = new mysqli($host, $usuarioBD, $senhaBD, $banco, $porta);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Excluir produto se solicitado via GET
if (isset($_GET['excluir_id'])) {
    $idExcluir = intval($_GET['excluir_id']);
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id_produto = ?");
    $stmt->bind_param("i", $idExcluir);
    $stmt->execute();
    $stmt->close();
    header("Location: listar_produtos.php"); // redireciona para evitar reenvio
    exit;
}

// Buscar produtos
$result = $conn->query("SELECT * FROM produtos ORDER BY nome_produto");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Listar Produtos</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
    <h1>Produtos no Estoque</h1>
    <a href="pagina_inicial.php"><button>🔙 Voltar ao Menu</button></a>
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 20px;">
        <tr>
            <th>ID</th><th>Nome do Produto</th><th>Quantidade em Estoque</th><th>Ação</th>
        </tr>
        <?php while($produto = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $produto['id_produto'] ?></td>
                <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                <td><?= $produto['qtde_estoque'] ?></td>
                <td>
                    <a href="listar_produtos.php?excluir_id=<?= $produto['id_produto'] ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php include 'acessibilidade.php'; ?>

</body>
</html>
