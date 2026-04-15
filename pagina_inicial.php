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
    <meta charset="UTF-8" />
    <title>Controle de estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px; /* tamanho padrão */
        }
        .tabs {
            display: flex;
            background-color: #333;
        }
        .tab-button {
            flex: 1;
            padding: 15px;
            color: white;
            text-align: center;
            cursor: pointer;
            background-color: #333;
            border: none;
            font-size: 18px;
        }
        .tab-button:hover, .tab-button.active {
            background-color: #555;
        }
        .tab-content {
            display: none;
            padding: 40px;
            background-size: cover;
            background-position: center;
            min-height: 80vh;
        }
        .tab-content.active {
            display: block;
        }
        .button {
            display: block;
            margin: 20px auto;
            padding: 20px 40px;
            font-size: 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            width: 300px;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .button:hover {
            transform: scale(1.05);
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="tabs">
        <button class="tab-button active" onclick="showTab('cadastro')">📦 Cadastro & Estoque</button>
        <button class="tab-button" onclick="showTab('vendas')">🛒 Vendas</button>
        <button class="tab-button" onclick="showTab('relatorios')">📊 Relatórios</button>
        <button class="tab-button" onclick="showTab('financeiro')">💰 Financeiro</button>
        <button class="tab-button" onclick="sair()">🚪 Sair</button>
    </div>

    <div id="cadastro" class="tab-content active" style="background-color: #cce5ff;">
        <a href="cadastrar_produto.php"><button class="button">➕ Cadastrar Produto</button></a>
        <a href="editar_produto.php"><button class="button">✏️ Editar Produto</button></a>
        <a href="listar_produtos.php"><button class="button">📦 Excluir Produto</button></a>
        <a href="adicionar_estoque.php"><button class="button">🔼 Adicionar Estoque</button></a>
    </div>

    <div id="vendas" class="tab-content" style="background-color: #d4edda;">
        <a href="registrar_venda.php"><button class="button">🛒 Registrar Venda</button></a>
        <a href="relatorio_opcoes.php"><button class="button">🔍 Consultar Vendas por Período</button></a>
        <a href="teste_ia.php"><button class="button">📊 Previsão de reposição feita com IA</button></a>
        <a href="exportar_vendas_csv.php"><button class="button">📋 Baixar Relatório de vendas</button></a>
    </div>

    <div id="relatorios" class="tab-content" style="background-color: #d3d3d3;">
        <a href="gerar_estoque_completo.php"><button class="button">📋 Planilha de Estoque Completo</button></a>
        <a href="relatorio_reposicao.php"><button class="button">📉 Relatório de Reposição</button></a>
    </div>

    <div id="financeiro" class="tab-content" style="background-color: #fff3cd;">
        <a href="buscar_venda_cupom.php" target="_blank">
            <button class="button">🧾 Imprimir Cupom</button>
        </a>
        <a href="relatorio_vendas_financeiro.php"><button class="button">📋 Relatório de Vendas</button></a>
        
        
        
    </div>

    <!-- Inclui o botão e script de acessibilidade -->
    <?php include 'acessibilidade.php'; ?>

    <script>
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab-content');
            const buttons = document.querySelectorAll('.tab-button');
            tabs.forEach(tab => tab.classList.remove('active'));
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function sair() {
            if (confirm('Deseja realmente sair?')) {
                window.location.href = 'logout.php';
            }
        }
    </script>

</body>
</html>
