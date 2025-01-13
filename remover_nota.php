<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$database = "client2";; // Substitua pelo seu nome de banco de dados

// Incluir arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verificar se foi enviado um ID de nota fiscal via GET
if (isset($_GET['id'])) {
    $id_nota = $_GET['id'];

    // Excluir itens da nota fiscal
    $sql_excluir_itens = "DELETE FROM notas_fiscais_itens WHERE id_nota_fiscal = '$id_nota'";
    if ($conn->query($sql_excluir_itens) === TRUE) {
        // Agora podemos excluir a nota fiscal
        $sql_excluir_nota = "DELETE FROM notas_fiscais WHERE id = '$id_nota'";
        if ($conn->query($sql_excluir_nota) === TRUE) {
            echo "<script>alert('Nota fiscal removida com sucesso.'); window.location.href = 'listar_notas.php';</script>";
        } else {
            echo "Erro ao excluir nota fiscal: " . $conn->error;
        }
    } else {
        echo "Erro ao excluir itens da nota fiscal: " . $conn->error;
    }
}

// Fechar conexão com o banco de dados
$conn->close();
?>
