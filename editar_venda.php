<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: sistema_login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: relatorio_vendas_financeiro.php");
    exit;
}

$id_venda = intval($_GET['id']);

// Busca os itens da venda
$sql = "SELECT id, id_produto, nome_produto, quantidade, valor_unitario FROM itens_venda WHERE id_venda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venda);
$stmt->execute();
$res = $stmt->get_result();

$itens = [];
while ($row = $res->fetch_assoc()) {
    $itens[] = $row;
}
$stmt->close();

if (count($itens) == 0) {
    header("Location: relatorio_vendas_financeiro.php");
    exit;
}

// Busca todos os produtos disponíveis
$produtos_result = $conn->query("SELECT id_produto, nome_produto, qtde_estoque, valor_unitario FROM produtos ORDER BY nome_produto ASC");
$produtos = [];
while ($p = $produtos_result->fetch_assoc()) {
    $produtos[] = $p;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Venda #<?= $id_venda ?></title>
<style>
body { font-family: Arial; padding: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
th { background-color: #f2f2f2; }
input, select { width: 100%; padding: 6px; box-sizing: border-box; }
button { padding: 8px 16px; margin-top: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background-color: #0056b3; }
</style>
</head>
<body>

<h2>Editar Venda #<?= $id_venda ?></h2>

<form method="POST" action="editar_venda_processar.php">
    <input type="hidden" name="id_venda" value="<?= $id_venda ?>">

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
            <tr>
                <td>
                    <select name="id_produto[<?= $item['id'] ?>]" required>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id_produto'] ?>" <?= $p['id_produto'] == $item['id_produto'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nome_produto']) ?> (Estoque: <?= $p['qtde_estoque'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="quantidade[<?= $item['id'] ?>]" value="<?= $item['quantidade'] ?>" min="1" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="valor_unitario[<?= $item['id'] ?>]" value="<?= $item['valor_unitario'] ?>" required>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>
