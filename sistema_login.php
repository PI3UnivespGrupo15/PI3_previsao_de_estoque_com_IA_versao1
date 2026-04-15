<?php
session_start();

$host = "sql201.infinityfree.com";
$porta = 3306;
$usuarioBD = "if0_39007414";
$senhaBD = "SibfxkGyuneA6";
$banco = "if0_39007414_bdcontroleestoque";

$conn = new mysqli($host, $usuarioBD, $senhaBD, $banco, $porta);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Criar tabela se não existir
$conn->query("
    CREATE TABLE IF NOT EXISTS projeto_previsao_estoque_usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )
");

// Criar usuário padrão admin/1234 (executa uma vez)
$usuarioPadrao = 'piunivesp';
$senhaPadrao = password_hash('grupo11', PASSWORD_DEFAULT);

// Verifica se já existe admin
$stmt = $conn->prepare("SELECT id FROM projeto_previsao_estoque_usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuarioPadrao);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $stmt_insert = $conn->prepare("INSERT INTO projeto_previsao_estoque_usuarios (usuario, senha) VALUES (?, ?)");
    $stmt_insert->bind_param("ss", $usuarioPadrao, $senhaPadrao);
    $stmt_insert->execute();
    $stmt_insert->close();
}
$stmt->close();

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
            header("Location: index.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
    $stmt->close();
}

// Logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: sistema_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Sistema de Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
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
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION["usuario"]) ?>!</h2>
    <p>Você está logado no sistema.</p>
    <a href="?logout=true"><button>Sair</button></a>
<?php endif; ?>

</body>
</html>
