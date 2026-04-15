<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>Sistema de Estoque</h1>
    <p>Bem-vindo, <strong><?= $_SESSION["usuario"] ?></strong>!</p>

    <div class="menu">
        <a href="pagina_inicial.php"><button>🔙 Voltar ao Menu</button></a>
    </div>

    <div class="conteudo">
        <h2>📈 Relatório de Vendas</h2>
        <form method="GET" action="gerar_relatorio.php">
            <label for="dias">Selecione o período:</label>
            <select name="dias" id="dias" required>
                <option value="">-- Selecione --</option>
                <option value="10">Últimos 10 dias</option>
                <option value="30">Últimos 30 dias</option>
                <option value="60">Últimos 60 dias</option>
            </select>
            <button type="submit">📥 Baixar Relatório</button>
        </form>
    </div>
</div>
</body>
</html>
