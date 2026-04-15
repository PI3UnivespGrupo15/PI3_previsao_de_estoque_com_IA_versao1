<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexao.php");

    $nome_produto = $_POST["nome_produto"];
    $quantidade = intval($_POST["quantidade"]);

    $sql_verifica = "SELECT * FROM produtos WHERE nome_produto = ?";
    $stmt = $conn->prepare($sql_verifica);
    $stmt->bind_param("s", $nome_produto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $sql_update = "UPDATE produtos SET qtde_estoque = qtde_estoque + ? WHERE nome_produto = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("is", $quantidade, $nome_produto);

        if ($stmt->execute()) {
            $mensagem = "✅ Estoque atualizado para o produto: <strong>$nome_produto</strong>.";
        } else {
            $mensagem = "❌ Erro ao atualizar estoque: " . $stmt->error;
        }
    } else {
        $mensagem = "⚠️ Produto <strong>$nome_produto</strong> não encontrado.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Estoque</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .mensagem {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        .mensagem.sucesso { background-color: #d4edda; color: #155724; }
        .mensagem.erro { background-color: #f8d7da; color: #721c24; }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 0 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"], .btn-voltar, .btn-ler-codigo {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            text-decoration: none;
            text-align: center;
            line-height: normal;
        }

        input[type="submit"]:hover, .btn-voltar:hover, .btn-ler-codigo:hover { background-color: #2980b9; }

        #sugestoes {
            border: 1px solid #ccc;
            display: none;
            max-height: 150px;
            overflow-y: auto;
            background-color: white;
            position: absolute;
            width: 100%;
            z-index: 1000;
            font-size: 14px;
        }

        #sugestoes div {
            padding: 8px 10px;
            cursor: pointer;
        }

        #sugestoes div:hover {
            background-color: #f0f0f0;
        }

        .input-container { position: relative; width: 100%; margin-bottom: 10px; }
        .input-container button { margin-top: 5px; width: 100%; }
    </style>
</head>
<body>
<div class="container">
    <h2>Adicionar Estoque</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?= strpos($mensagem, '✅') !== false ? 'sucesso' : 'erro' ?>">
            <?= $mensagem ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-container">
            <input type="text" id="campoProduto" name="nome_produto" placeholder="Digite o nome ou escaneie o código" autocomplete="off" required>
            <div id="sugestoes"></div>
        </div>

        <button type="button" class="btn-ler-codigo" onclick="ativarLeitorUSB()">📷 Ler Código de Barras (USB)</button>

        <input type="number" name="quantidade" placeholder="Quantidade a Adicionar" min="1" required>
        <input type="submit" value="Adicionar">
    </form>

    <a href="pagina_inicial.php" class="btn-voltar">Voltar ao Menu Inicial</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#campoProduto").on("keyup", function() {
        let valor = $(this).val();
        if (valor.length >= 3) {
            $.get("buscar_produto.php", { termo: valor }, function(data) {
                $("#sugestoes").html(data).show();
            });
        } else {
            $("#sugestoes").hide();
        }
    });
});

// Seleciona produto com 1 clique
function selecionarProduto(id, nome) {
    $("#campoProduto").val(nome);
    $("#sugestoes").hide();
    // Se quiser, aqui você pode buscar valor_unitario via AJAX
}

// Fecha sugestões ao clicar fora
$(document).click(function(event) {
    if(!$(event.target).closest("#campoProduto, #sugestoes, .btn-ler-codigo").length) {
        $("#sugestoes").hide();
    }
});

// Função fictícia para ativar leitor USB
function ativarLeitorUSB() {
    alert("Aguardando entrada do leitor USB. Digite ou escaneie o código de barras.");
    // Aqui o leitor USB vai enviar o código diretamente para o campo "campoProduto"
}
</script>

<?php include 'acessibilidade.php'; ?>
</body>
</html>
