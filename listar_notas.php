<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Notas Fiscais - Sistema de Emissão de Notas Fiscais</title>
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

        /* Estilos para a tabela de listagem */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
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

        p {
            font-size: 18px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Listagem de Notas Fiscais</h1>

    <?php
    // Conectar ao banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$database = "client2"; // Substitua pelo seu nome de banco de dados

    require_once 'conexao.php';

    // Consultar notas fiscais no banco de dados
    $sql = "SELECT * FROM notas_fiscais";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Cliente</th><th>Valor Total</th><th>Data de Emissão</th><th>Ações</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $id_nota = $row['id'];
            $cliente_id = $row['cliente_id'];
            $valor_total = $row['valor_total'];
            $data_emissao = $row['data_emissao'];

            // Obter nome do cliente
            $sql_cliente = "SELECT nome FROM clientes WHERE id = '$cliente_id'";
            $result_cliente = $conn->query($sql_cliente);
            if ($result_cliente->num_rows > 0) {
                $row_cliente = $result_cliente->fetch_assoc();
                $nome_cliente = $row_cliente['nome'];
            } else {
                $nome_cliente = "Cliente não encontrado";
            }

            echo "<tr>";
            echo "<td>$id_nota</td>";
            echo "<td>$nome_cliente</td>";
            echo "<td>R$ " . number_format($valor_total, 2, ',', '.') . "</td>";
            echo "<td>$data_emissao</td>";
            echo "<td>";
            echo "<a href='remover_nota.php?id=$id_nota' class='btn' onclick='return confirm(\"Tem certeza que deseja remover esta nota fiscal?\")'>Remover</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhuma nota fiscal encontrada.</p>";
    }

    // Fechar conexão com o banco de dados
    $conn->close();
    ?>

    <br>
    <a href="index.php" class="btn">Voltar</a>
</div>

</body>
</html>
