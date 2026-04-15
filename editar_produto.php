<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

$msg_erro = "";
$msg_sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexao.php");

    $id_produto = intval($_POST["id_produto"]);
    $novo_nome = trim($_POST["novo_nome"]);
    $novo_valor_raw = str_replace(',', '.', trim($_POST["novo_valor"]));
    $novo_valor = is_numeric($novo_valor_raw) ? floatval($novo_valor_raw) : null;
    
    // Ateracao de quantidade em estoque
    $nova_qtde = str_replace(',', '.', trim($_POST["nova_qtde"]));
    $nova_qtde = is_numeric($nova_qtde) ? intval($nova_qtde) : null;

    if ($novo_valor === null) {
        $msg_erro = "Valor do produto inválido.";
    } else {
        $sql_verifica = "SELECT * FROM produtos WHERE id_produto = ?";
        $stmt_verifica = $conn->prepare($sql_verifica);
        $stmt_verifica->bind_param("i", $id_produto);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $stmt_verifica->close();

            $sql_update = "UPDATE produtos SET nome_produto = ?, valor_unitario = ?, qtde_estoque = ? WHERE id_produto = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!$stmt_update) {
                $msg_erro = "Erro na preparação da query: " . $conn->error;
            } else {
                $stmt_update->bind_param("sdii", $novo_nome, $novo_valor, $nova_qtde, $id_produto);
                if ($stmt_update->execute()) {
                    $msg_sucesso = "Produto atualizado com sucesso!";
                } else {
                    $msg_erro = "Erro ao atualizar produto: " . $stmt_update->error;
                }
                $stmt_update->close();
            }
        } else {
            $msg_erro = "Produto não encontrado.";
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Produto</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fafafa; }
        form { max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
        label, input { display: block; width: 100%; margin-bottom: 10px; }
        input { padding: 8px; font-size: 16px; }
        button { padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button#voltar { background: #7f8c8d; margin-top: 15px; }
        .erro { color: red; margin-bottom: 15px; text-align: center; }
        .sucesso { color: green; margin-bottom: 15px; text-align: center; }
        #lista_sugestoes { border:1px solid #ccc; max-width: 400px; background: white; cursor:pointer; position: absolute; z-index: 1000; }
        #lista_sugestoes div { padding: 5px; }
        #lista_sugestoes div:hover { background-color: #ddd; }
        .campo-container { position: relative; max-width: 400px; margin: auto; }
    </style>
</head>
<body>

    <?php include 'acessibilidade.php'; ?>

    
<h2 style="text-align:center;">Editar Produto</h2>

<?php if (!empty($msg_erro)) : ?>
    <div class="erro"><?= htmlspecialchars($msg_erro) ?></div>
<?php endif; ?>

<?php if (!empty($msg_sucesso)) : ?>
    <div class="sucesso"><?= htmlspecialchars($msg_sucesso) ?></div>
<?php endif; ?>

<form method="post" action="editar_produto.php" autocomplete="off">
    <input type="hidden" id="id_produto" name="id_produto" required>

    <label for="codigo_barras">Ler código de barras:</label>
	<input type="text" id="codigo_barras" placeholder="Passe o leitor aqui">

    <div class="campo-container">
        <label for="nome_antigo">Nome atual do produto:</label>
        <input type="text" id="nome_antigo" name="nome_antigo" required>
        <div id="lista_sugestoes"></div>
    </div>

    <label for="novo_nome">Novo nome do produto:</label>
    <input type="text" id="novo_nome" name="novo_nome" required>

    <label for="novo_valor">Novo valor do produto (R$):</label>
    <input type="text" id="novo_valor" name="novo_valor" placeholder="Ex: 12,50" required>
    
        
    <label for="nova_qtde">Nova quantidade do produto:</label>
    <input type="text" id="nova_qtde" name="nova_qtde" min="0" required>

    <button type="submit">Alterar Produto</button>
</form>

<form action="pagina_inicial.php" method="get" style="text-align:center;">
    <button type="submit" id="voltar">Voltar ao Menu Principal</button>
</form>

<script>
    // Busca dinâmica de produtos para autocomplete
    const inputProduto = document.getElementById('nome_antigo');
    const listaSugestoes = document.getElementById('lista_sugestoes');

    function buscarProduto() {
    const termo = inputProduto.value.trim();

    if (termo.length < 2) {
        listaSugestoes.innerHTML = '';
        return;
    }

    fetch('buscar_produto.php?termo=' + encodeURIComponent(termo))
        .then(response => response.text())
        .then(html => {
            listaSugestoes.innerHTML = html;
        });
}

// busca enquanto digita
inputProduto.addEventListener('input', buscarProduto);

// busca quando leitor envia ENTER
inputProduto.addEventListener('keyup', function(e) {
    if (e.key === "Enter") {
        buscarProduto();
    }
});


    document.addEventListener('click', function(e) {
        if (e.target !== inputProduto) {
            listaSugestoes.innerHTML = '';
        }
    });

    // Função chamada pelas sugestões para preencher os campos
    function selecionarProduto(id, nome) {
        document.getElementById('nome_antigo').value = nome;
        document.getElementById('id_produto').value = id;
        document.getElementById('lista_sugestoes').innerHTML = '';

        // Buscar o valor atual do produto e preencher o campo novo_valor
        fetch('buscar_valor_produto.php?id=' + id)
    .then(res => res.json())
    .then(dados => {
        document.getElementById('novo_valor').value =
            dados.valor_unitario.toString().replace('.', ',');

        document.getElementById('nova_qtde').value =
            dados.qtde_estoque;
    });

    }

    // Validação e máscara para campo valor
    function validarValor(valor) {
        const regex = /^\d+([,.]\d{1,2})?$/;
        return regex.test(valor.trim());
    }

    function aplicarMascaraValor(input) {
        let valor = input.value;
        valor = valor.replace(/\./g, ',');
        valor = valor.replace(/[^\d,]/g, '');
        const partes = valor.split(',');
        if (partes.length > 2) {
            valor = partes[0] + ',' + partes[1];
        }
        input.value = valor;
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const inputValor = document.getElementById('novo_valor');
        if (!validarValor(inputValor.value)) {
            e.preventDefault();
            alert('Por favor, insira um valor válido para o produto. Exemplo: 12,50');
            inputValor.focus();
            return false;
        }
    });

    document.getElementById('novo_valor').addEventListener('input', function() {
        aplicarMascaraValor(this);
    });
    
    document.getElementById("codigo_barras").addEventListener("change", function() {
    const codigo = this.value.trim();

    if (!codigo) return;

    fetch("buscar_produto.php?termo=" + encodeURIComponent(codigo))
        .then(res => res.text())
        .then(html => {
            listaSugestoes.innerHTML = html;
        });

    this.value = "";
});

</script>

</body>
</html>
