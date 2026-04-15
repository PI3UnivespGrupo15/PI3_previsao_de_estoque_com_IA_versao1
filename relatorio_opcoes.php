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
    <title>Opções Relatório de Vendas</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: auto;
            text-align: center;
        }
        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
            cursor: pointer;
        }
        button.generate {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button.generate:hover {
            background-color: #388e3c;
        }
        button.back {
            background-color: #888;
            color: white;
            border: none;
            margin-top: 10px;
        }
        button.back:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Opções do Relatório de Vendas</h2>

        <form action="gerar_relatorio.php" method="get" target="_blank">
            <label for="dias">Selecione a quantidade de dias:</label>
            <select name="dias" id="dias" required>
                <option value="10">Últimos 10 dias</option>
                <option value="30" selected>Últimos 30 dias</option>
                <option value="60">Últimos 60 dias</option>
            </select>
            <button class="generate" type="submit">Gerar Relatório</button>
        </form>

        <button class="back" onclick="window.location.href='pagina_inicial.php'">Voltar ao Menu Inicial</button>

    </div>

    <?php include 'acessibilidade.php'; ?>

</body>
</html>
