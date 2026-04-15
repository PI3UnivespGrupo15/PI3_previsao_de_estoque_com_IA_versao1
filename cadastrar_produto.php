<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco
    $host = 'sql201.infinityfree.com';
    $usuario = 'if0_39007414';
    $senha = 'SibfxkGyuneA6';
    $banco = 'if0_39007414_bdcontroleestoque';

    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        $mensagem = "❌ Erro na conexão: " . $conn->connect_error;
    } else {
        $nome = trim($_POST["nome_produto"]);
        $qtde = intval($_POST["qtde_estoque"]);
        $valor = floatval(str_replace(',', '.', $_POST["valor_unitario"])); // aceita vírgula ou ponto

        // Verifica se o produto já existe
        $verifica = $conn->prepare("SELECT COUNT(*) FROM produtos WHERE nome_produto = ?");
        $verifica->bind_param("s", $nome);
        $verifica->execute();
        $verifica->bind_result($existe);
        $verifica->fetch();
        $verifica->close();

        if ($existe > 0) {
            $mensagem = "⚠️ Produto com esse nome já está cadastrado.";
        } else {
            // Tenta inserir o produto com valor_unitario e código de barras
            
            // Gera código EAN-13 aleatório e único
do {
    $codigo_barras = str_pad(mt_rand(1, 9999999999999), 13, '0', STR_PAD_LEFT);
    $stmt_check = $conn->prepare("SELECT id_produto FROM produtos WHERE codigo_barras = ?");
    $stmt_check->bind_param("s", $codigo_barras);
    $stmt_check->execute();
    $stmt_check->store_result();
} while ($stmt_check->num_rows > 0);

            $stmt = $conn->prepare("INSERT INTO produtos (nome_produto, qtde_estoque, valor_unitario, codigo_barras) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sids", $nome, $qtde, $valor, $codigo_barras);

            if ($stmt->execute()) {
   				 $mensagem = "✅ Produto cadastrado com sucesso! Código de barras: $codigo_barras
   				 <br><a href='imprimir_codigo.php?codigo=$codigo_barras' target='_blank'>
   				 <button type='button'>🖨️ Imprimir Código de Barras</button></a>";
			}
            else {
    			if ($conn->errno == 1062) {
     		   $mensagem = "⚠️ Produto com esse nome já está cadastrado (restrição UNIQUE).";
    			} 
                else {
        $mensagem = "❌ Erro ao cadastrar: " . $stmt->error;
  			  }
}

            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        form label,
        form input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #388e3c;
        }

        .mensagem {
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Cadastrar Produto</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= $mensagem ?></div>

        <?php endif; ?>

        <form action="" method="post">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" id="nome_produto" name="nome_produto" required>

            <label for="qtde_estoque">Quantidade em Estoque:</label>
            <input type="number" id="qtde_estoque" name="qtde_estoque" required>

            <label for="valor_unitario">Valor Unitário (R$):</label>
            <input type="text" id="valor_unitario" name="valor_unitario" placeholder="Ex: 10.00" required pattern="^\d+(\.\d{1,2})?$" title="Formato válido: 10.00">

            <input type="submit" value="Cadastrar">
        </form>

        <a href="pagina_inicial.php"><button>🔙 Voltar ao Menu</button></a>
    </div>
    <?php include 'acessibilidade.php'; ?>

</body>
</html>
