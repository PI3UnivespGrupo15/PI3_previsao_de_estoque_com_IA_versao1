<?php
$codigo = $_GET['codigo'] ?? '';

if (empty($codigo)) {
    exit("Código de barras não informado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Código de Barras</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        img {
            margin-top: 50px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <h2>Código de Barras</h2>
    <img src="barcode.php?text=<?= htmlspecialchars($codigo) ?>&size=80" alt="Código de Barras">
    <br>
    <button onclick="window.print()">🖨️ Imprimir</button>
</body>
</html>
