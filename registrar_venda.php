<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: sistema_login.php");
    exit;
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

include 'conexao.php';

if (!empty($_GET['sucesso']) && $_GET['sucesso'] == '1' && !empty($_GET['id_venda'])) {
    $id_venda = (int) $_GET['id_venda'];
    echo "<div class='sucesso'>Venda registrada com sucesso! 
        <a href='imprimir_cupom.php?id_pedido=$id_venda' target='_blank'>Imprimir cupom</a></div>";
}

function calcularTotalCarrinho($carrinho) {
    $total = 0;
    foreach ($carrinho as $item) {
        $total += $item['quantidade'] * $item['valor_unitario'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venda</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body { margin: 0; background-color: #dfefff; font-family: Arial; }
        .carrinho-lateral {
            position: fixed;
            top: 0; left: 0;
            width: 320px; height: 100%;
            background: #f1f1f1;
            padding: 15px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .container {
            width: 600px;
            margin-left: 360px;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border-bottom: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }
        button, .btn-voltar {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 10px auto;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-voltar {
            background-color: #007BFF;
            color: white;
        }
        .btn-voltar:hover { background-color: #0056b3; }
        .btn-registrar-venda {
            background-color: #007BFF;
            color: white;
        }
        .btn-registrar-venda:hover { background-color: #0056b3; }
        .mensagem-sucesso {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .erro-msg {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        #sugestoes {
            border: 1px solid #ccc;
            max-width: 400px;
            background: white;
            position: absolute;
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>

<?php include 'acessibilidade.php'; ?>

<div class="carrinho-lateral">
    <h3>🛒 Itens da Venda</h3>
    <?php if(count($_SESSION['carrinho'])>0): ?>
        <form method="POST" action="atualizar_quantidade.php">
            <table>
                <thead>
                    <tr><th>Produto</th><th>Qtd</th><th>Valor</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach($_SESSION['carrinho'] as $index => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><input type="number" name="quantidade[<?= $index ?>]" value="<?= $item['quantidade'] ?>" min="1" style="width:60px;"></td>
                        <td>R$<?= number_format($item['valor_unitario'],2,',','.') ?></td>
                        <td><a href="remover_item.php?id=<?= urlencode($item['id_produto']) ?>" onclick="return confirm('Remover este item?')">🗑️</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" style="margin-top:10px;">Atualizar Quantidades</button>
        </form>
        <hr>
        <strong>Total: R$<?= number_format(calcularTotalCarrinho($_SESSION['carrinho']),2,',','.') ?></strong>

        <form method="POST" action="registrar_venda_processar.php" style="margin-top: 20px;">
            <label for="forma_pagamento">Forma de Pagamento:</label>
            <select name="forma_pagamento" id="forma_pagamento" required>
                <option value="">Selecione</option>
                <option value="Dinheiro">Dinheiro</option>
                <option value="Pix">Pix</option>
                <option value="Cartão de Débito">Cartão de Débito</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
            </select>

            <?php date_default_timezone_set('America/Sao_Paulo'); ?>
            <label for="data_venda">Data e Hora da Venda:</label>
            <input type="datetime-local" id="data_venda" name="data_venda" required value="<?= date('Y-m-d\TH:i') ?>">

            <label for="desconto">Desconto (R$):</label>
            <input type="number" step="0.01" name="desconto" id="desconto" value="0.00">

            <button type="submit" class="btn-registrar-venda">Registrar Venda</button>
        </form>
    <?php else: ?>
        <p>Nenhum item adicionado.</p>
    <?php endif; ?>
</div>

<div class="container">
    <h2 style="text-align:center;">Registrar Venda</h2>

    <form method="POST" action="adicionar_item.php" autocomplete="off">
        <div style="display: flex; gap: 8px; align-items: center;">
            <input type="text" id="campoBusca" name="nome_produto" placeholder="Digite o nome ou escaneie o código" required onkeyup="buscarProdutos(this.value)">
            <button type="button" onclick="document.getElementById('campoBusca').focus()">📷 Ler Código de Barras</button>
        </div>
        <div id="sugestoes"></div>
        <input type="hidden" name="id_produto" id="id_produto">
        <input type="hidden" name="valor_unitario" id="valor_unitario">
        <input type="number" name="qtde_vendida" placeholder="Quantidade" required min="1">
        <button type="submit">Adicionar ao Pedido</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="pagina_inicial.php" class="btn-voltar">🔙 Voltar ao Menu</a>
    </div>
</div>

<script>
function buscarProdutos(termo) {
    const sugestoes = document.getElementById('sugestoes');
    if (termo.length < 3) {
        sugestoes.style.display = 'none';
        sugestoes.innerHTML = '';
        return;
    }

    fetch('buscar_produto.php?termo=' + encodeURIComponent(termo))
        .then(res => res.text())
        .then(html => {
            sugestoes.innerHTML = html;
            sugestoes.style.display = 'block';
        });
}

// Seleciona produto com 1 clique
function selecionarProduto(id, nome) {
    document.getElementById('campoBusca').value = nome;
    document.getElementById('id_produto').value = id;
    document.getElementById('sugestoes').style.display = 'none';

    fetch('buscar_valor_produto.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('valor_unitario').value = data.valor_unitario;
        });
}

// Fecha sugestões se clicar fora
document.addEventListener('click', function(e) {
    if (!e.target.closest('#campoBusca, #sugestoes')) {
        document.getElementById('sugestoes').style.display = 'none';
    }
});
</script>

</body>
</html>
