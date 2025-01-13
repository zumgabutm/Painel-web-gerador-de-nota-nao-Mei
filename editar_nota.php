<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "rafael_db5";
$password = "RpBfJH#a9zYUkV#yd*";
$database = "rafael_db5"; // Substitua pelo seu nome de banco de dados

// Incluir arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verificar se foi enviado um ID de nota fiscal via GET
if (isset($_GET['id'])) {
    $id_nota = $_GET['id'];

    // Verificar se o formulário foi enviado via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $novo_cliente_id = $_POST['cliente'];

        // Atualizar cliente associado à nota fiscal
        $sql = "UPDATE notas_fiscais SET cliente_id = '$novo_cliente_id' WHERE id = '$id_nota'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Nota fiscal editada com sucesso.'); window.location.href = 'listar_notas.php';</script>";
        } else {
            echo "Erro ao editar nota fiscal: " . $conn->error;
        }
    }

    // Consultar dados da nota fiscal para exibir no formulário
    $sql_consulta = "SELECT cliente_id FROM notas_fiscais WHERE id = '$id_nota'";
    $resultado = $conn->query($sql_consulta);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $cliente_id_atual = $row['cliente_id'];
    } else {
        echo "Nota fiscal não encontrada.";
        exit;
    }
}

// Fechar conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Nota Fiscal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Nota Fiscal</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_nota; ?>" method="post">
            <label for="cliente">Cliente:</label>
            <select name="cliente" id="cliente">
                <option value="">Selecione um cliente...</option>
                <?php
                // Consultar clientes no banco de dados
                $sql_clientes = "SELECT id, nome FROM clientes";
                $resultado_clientes = $conn->query($sql_clientes);

                if ($resultado_clientes->num_rows > 0) {
                    while ($row_cliente = $resultado_clientes->fetch_assoc()) {
                        $selected = ($row_cliente['id'] == $cliente_id_atual) ? 'selected' : '';
                        echo '<option value="' . $row_cliente['id'] . '" ' . $selected . '>' . $row_cliente['nome'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>Nenhum cliente encontrado</option>';
                }
                ?>
            </select>
            <br><br>
            
            <input type="submit" value="Salvar Alterações">
        </form>
    </div>
</body>
</html>
