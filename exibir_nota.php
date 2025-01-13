<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$database = "client2"; // Substitua pelo seu nome de banco de dados

// Incluir arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Processar dados do formulário enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter dados do formulário
    $cliente_id = $_POST['cliente'];
    $produtos = $_POST['produto'];
    $quantidades = $_POST['quantidade'];

    // Verificar se o cliente e os produtos foram selecionados
    if (empty($cliente_id) || empty($produtos)) {
        die("Por favor, selecione um cliente e pelo menos um produto.");
    }

    // Iniciar transação
    mysqli_autocommit($conn, false);

    // Inserir nota fiscal
    $sql_nota = "INSERT INTO notas_fiscais (cliente_id, valor_total) VALUES ('$cliente_id', '0')";
    if ($conn->query($sql_nota) === TRUE) {
        $id_nota_fiscal = $conn->insert_id; // Obter o ID da nota fiscal inserida
    } else {
        echo "Erro ao inserir nota fiscal: " . $conn->error;
        mysqli_rollback($conn); // Rollback em caso de erro
        exit;
    }

    // Calcular e inserir itens da nota fiscal
    $valor_total = 0;
    $erro_insercao = false;
    for ($i = 0; $i < count($produtos); $i++) {
        $produto_id = $produtos[$i];
        $quantidade = $quantidades[$i];

        // Obter preço unitário do produto
        $sql_preco = "SELECT preco FROM produtos WHERE id = '$produto_id'";
        $resultado_preco = $conn->query($sql_preco);
        if ($resultado_preco->num_rows > 0) {
            $row = $resultado_preco->fetch_assoc();
            $preco_unitario = $row['preco'];
        } else {
            echo "Produto não encontrado.";
            $erro_insercao = true;
            break;
        }

        // Calcular valor total do item
        $valor_item = $preco_unitario * $quantidade;
        $valor_total += $valor_item;

        // Inserir item da nota fiscal
        $sql_item = "INSERT INTO notas_fiscais_itens (id_nota_fiscal, id_produto, quantidade, preco_unitario) 
                     VALUES ('$id_nota_fiscal', '$produto_id', '$quantidade', '$preco_unitario')";
        if ($conn->query($sql_item) !== TRUE) {
            echo "Erro ao inserir item da nota fiscal: " . $conn->error;
            $erro_insercao = true;
            break;
        }
    }

    // Atualizar valor total da nota fiscal
    if (!$erro_insercao) {
        $sql_atualizar_total = "UPDATE notas_fiscais SET valor_total = '$valor_total' WHERE id = '$id_nota_fiscal'";
        if ($conn->query($sql_atualizar_total) !== TRUE) {
            echo "Erro ao atualizar valor total da nota fiscal: " . $conn->error;
            $erro_insercao = true;
        }
    }

    // Commit ou rollback da transação
    if (!$erro_insercao) {
        mysqli_commit($conn);
        echo "Nota fiscal processada com sucesso! <br>";
        echo '<script>window.print();</script>'; // Imprimir automaticamente ao finalizar
        echo "<br><a href='index.php'>Voltar</a>";
    } else {
        mysqli_rollback($conn);
    }

    // Fechar conexão com o banco de dados
    $conn->close();
}
?>
