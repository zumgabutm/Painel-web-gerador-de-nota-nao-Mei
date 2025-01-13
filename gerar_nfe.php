<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerar Nota Fiscal - Sistema de Emissão de Notas Fiscais</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos adicionais específicos para esta página */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #ffc371, #c06cff);
            text-align: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: 600% 600%;
            animation: gradientBG 10s ease infinite;
            padding: 20px;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }

        /* Estilos para o container principal */
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7); /* 70% de transparência */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 18px;
            color: #555;
        }

        select, input[type="number"] {
            width: calc(100% - 10px);
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 12px 24px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        button:hover {
            background-color: #c0392b;
        }

        input[type="submit"] {
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Estilos para os cartões de produtos */
        .card {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Gerar Nota Fiscal</h1>
    
    <!-- Formulário para Gerar Nota Fiscal -->
    <form action="processar_nfe.php" method="post">
        <label for="cliente">Cliente:</label>
        <select name="cliente" id="cliente">
            <option value="">Selecione um cliente...</option>
            <?php
            // Incluir arquivo de conexão com o banco de dados
            require_once 'conexao.php';

            // Consultar clientes no banco de dados
            $sql_clientes = "SELECT id, nome FROM clientes";
            $result_clientes = $conn->query($sql_clientes);

            if ($result_clientes->num_rows > 0) {
                // Loop through each row
                while($row_cliente = $result_clientes->fetch_assoc()) {
                    echo "<option value='" . $row_cliente['id'] . "'>" . $row_cliente['nome'] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>
        
        <!-- Container para produtos selecionados -->
        <div id="produtos-container">
            <!-- Cartão de produto -->
            <div class="card">
                <select name="produto[]" id="produto">
                    <option value="">Selecione um produto...</option>
                    <?php
                    // Consultar produtos no banco de dados
                    $sql_produtos = "SELECT id, nome, preco FROM produtos";
                    $result_produtos = $conn->query($sql_produtos);

                    if ($result_produtos->num_rows > 0) {
                        // Loop through each row
                        while($row_produto = $result_produtos->fetch_assoc()) {
                            echo "<option value='" . $row_produto['id'] . "'>" . $row_produto['nome'] . " - R$ " . number_format($row_produto['preco'], 2, ',', '.') . "</option>";
                        }
                    }
                    ?>
                </select>
                <br><br>
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade[]" id="quantidade" placeholder="Quantidade">
                <button type="button" onclick="removerProduto(this)">Remover</button>
            </div>
        </div>
        <button type="button" onclick="adicionarProduto()">Adicionar Produto</button>
        <br><br>
        
        <input type="submit" value="Gerar NFe">
    </form>
</div>

<!-- JavaScript para adicionar/remover produtos dinamicamente -->
<script>
    function adicionarProduto() {
        var produtosDiv = document.getElementById("produtos-container");
        var novoProdutoDiv = document.createElement("div");
        novoProdutoDiv.classList.add("card");
        novoProdutoDiv.innerHTML = `
            <select name="produto[]" id="produto">
                <option value="">Selecione um produto...</option>
                <?php
                // Consultar produtos no banco de dados novamente para carregar dinamicamente
                $result_produtos = $conn->query($sql_produtos); // Reexecutando a consulta

                if ($result_produtos->num_rows > 0) {
                    // Loop through each row
                    while($row_produto = $result_produtos->fetch_assoc()) {
                        echo "<option value='" . $row_produto['id'] . "'>" . $row_produto['nome'] . " - R$ " . number_format($row_produto['preco'], 2, ',', '.') . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade[]" id="quantidade" placeholder="Quantidade">
            <button type="button" onclick="removerProduto(this)">Remover</button>
        `;
        produtosDiv.appendChild(novoProdutoDiv);
    }

    function removerProduto(elemento) {
        elemento.parentNode.remove();
    }
</script>

</body>
</html>

<?php
// Fechar conexão com o banco de dados após a utilização
$conn->close();
?>
