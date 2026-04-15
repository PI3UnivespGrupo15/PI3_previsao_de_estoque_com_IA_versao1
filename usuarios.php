<?php
// sistema_login.php

session_start();

// 1. Conexão com o banco
$host = "sql201.infinityfree.com";
$usuarioBD = "if0_39007414";
$senhaBD = "SibfxkGyuneA6";
$banco = "if0_39007414_bdcontroleestoque";

$conn = new mysqli($host, $usuarioBD, $senhaBD, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// 2. Criação da tabela de usuários (executa uma vez)
$conn->query("
    CREATE TABLE IF NOT EXISTS projeto_previsao_estoque_usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )
");

// 3. Cadastro de um usuário padrão (executa uma vez)
$usuarioPadrao = 'piunivesp';
$senhaPadrao = password_hash('grupo11', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO projeto_previsao_estoque_usuarios (usuario, senha) VALUES ('$usuarioPadrao', '$senhaPadrao')");

// 4. Lógica de login
$erro = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["entrar"])) {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM projeto_previsao_estoque_usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user["senha"])) {
            $_SESSION["usuario"] = $user["usuario"];
            header("Location: sistema_login.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}

// 5. Logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: sistema_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Login</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            text-align: center;
            margin-top: 100px;
        }
        form {
            background-color: white;
            display: inline-block;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }
        input, button {
            display: block;
            margin: 10px auto;
            padding: 8px;
            width: 200px;
        }
        .erro {
            color: red;
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION["usuario"])): ?>
    <h2>Login do Sistema</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="entrar">Entrar</button>
        <?php if ($erro): ?>
            <p class="erro"><?= $erro ?></p>
        <?php endif; ?>
    </form>
<?php else: ?>
    <h2>Bem-vindo, <?= $_SESSION["usuario"] ?>!</h2>
    <p>Você está logado no sistema.</p>
    <a href="?logout=true">Sair</a>
<?php endif; ?>

</body>
</html>
