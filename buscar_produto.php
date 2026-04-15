<?php
include 'conexao.php';

if (isset($_GET['termo'])) {
    $termo = $_GET['termo'];

    // Verifica se o termo é numérico (para busca por código de barras)
    if (is_numeric($termo)) {
        $sql = "SELECT id_produto, nome_produto FROM produtos 
                WHERE codigo_barras = ? 
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $termo);
    } else {
        // Busca padrão por nome
        $sql = "SELECT id_produto, nome_produto FROM produtos 
                WHERE nome_produto LIKE ? 
                LIMIT 5";

        $stmt = $conn->prepare($sql);
        $like = "%$termo%";
        $stmt->bind_param("s", $like);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $nome_js = htmlspecialchars($linha['nome_produto'], ENT_QUOTES);
            echo "<div onclick=\"selecionarProduto('{$linha['id_produto']}', '{$nome_js}')\">
                    {$linha['nome_produto']}
                  </div>";
        }
    } else {
        echo "<div>Produto não encontrado.</div>";
    }
}
?>
