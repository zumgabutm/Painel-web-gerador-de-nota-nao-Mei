<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Clientes - Sistema de Gerenciamento</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos específicos para esta página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

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

        .btn-remove {
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Listagem de Clientes</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>
        <?php
        // Incluir arquivo de conexão com o banco de dados
        require_once 'conexao.php';

        // Consultar clientes no banco de dados
        $sql = "SELECT * FROM clientes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td><button class='btn-remove' onclick=\"removerCliente(" . $row['id'] . ")\">Remover</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum cliente encontrado.</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Script JavaScript para remover cliente -->
<script>
    function removerCliente(idCliente) {
        if (confirm("Tem certeza que deseja remover este cliente?")) {
            // Requisição AJAX para remover cliente
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Atualizar página após remoção
                    location.reload();
                }
            };
            xhr.open("GET", "remover_cliente.php?id=" + idCliente, true);
            xhr.send();
        }
    }
</script>

</body>
</html>

<?php
// Fechar conexão com o banco de dados após a utilização
$conn->close();
?>
