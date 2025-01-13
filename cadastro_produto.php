<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <style>
        /* Reset de Estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos para o Corpo da Página */
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
        }

        /* Estilos para o Contêiner Principal */
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.5); /* 50% de transparência */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para o Cabeçalho */
        .container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Estilos para o Formulário */
        form {
            text-align: left;
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"], form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        form input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        form input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastro de Produto</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" min="0" required>

        <label for="imposto">Imposto (%):</label>
        <input type="number" id="imposto" name="imposto" step="0.01" min="0" required>

        <input type="submit" value="Cadastrar">
    </form>
</div>

</body>
</html>

<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$database = "client2";

// Incluir arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Processar dados do formulário enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $imposto = $_POST['imposto'];

    // Conectar ao banco de dados
    $conn = new mysqli($hostname, $username, $password, $database);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Preparar e executar a query SQL para inserir produto
    $sql = "INSERT INTO produtos (nome, preco, imposto) VALUES ('$nome', '$preco', '$imposto')";

    if ($conn->query($sql) === TRUE) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar produto: " . $conn->error;
    }

    // Fechar conexão com o banco de dados
    $conn->close();
}
?>
