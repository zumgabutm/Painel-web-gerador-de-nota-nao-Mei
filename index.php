<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Emissão de Notas Fiscais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset de Estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos Gerais */
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
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.5); /* 50% de transparência */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .menu ul li {
            flex: 1 1 30%; /* Distribuição equitativa dos itens */
            margin: 10px;
        }

        .menu ul li a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .menu ul li a:hover {
            background-color: #f0f0f0;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            padding: 15px 30px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px;
            text-align: center;
            width: calc(50% - 20px); /* Distribui os botões em 50% do container com margens */
            background-color: #e74c3c;
            border: 1px solid #c0392b;
        }

        .btn:hover {
            background-color: #c0392b;
        }

        .btn span {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .menu ul li {
                flex: 1 1 100%; /* Altera a largura dos itens do menu para 100% em telas menores */
            }

            .btn {
                width: 100%; /* Altera a largura dos botões para 100% em telas menores */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Sistema de Emissão de Notas Fiscais</h1>
        <div class="menu">
            <ul>
                <!--
                <li><a href="cadastro_cliente.php">Cadastro de Clientes</a></li>
                <li><a href="cadastro_produto.php">Cadastro de Produtos</a></li>
                <li><a href="gerar_nfe.php">Gerar Nota Fiscal</a></li>
                <li><a href="listar_notas.php">Listar Notas Fiscais</a></li>
                 Adicione outros links conforme necessário -->
            </ul>
        </div>

        <div class="buttons">
            <a href="cadastro_cliente.php" class="btn"><span>Cadastro de Clientes</span></a>
            <a href="cadastro_produto.php" class="btn"><span>Cadastro de Produtos</span></a>
            <a href="gerar_nfe.php" class="btn"><span>Gerar Nota Fiscal</span></a>
            <a href="listar_notas.php" class="btn"><span>Listar Notas Fiscais</span></a>
            <!--
            <a href="listar_client.php" class="btn"><span>Listar Client</span></a>
            <a href="listar_produtos.php" class="btn"><span>Listar Produtos</span></a>
        </div>
    </div>
</body>
</html>
