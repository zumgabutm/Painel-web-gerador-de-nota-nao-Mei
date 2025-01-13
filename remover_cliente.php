<?php
// Incluir arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Função para remover um cliente
function removerCliente($conn, $id) {
    $sql = "DELETE FROM clientes WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        return true; // Remoção bem-sucedida
    } else {
        return false; // Erro ao remover
    }
}

// Verificar se foi solicitada a remoção de um cliente
if (isset($_GET['action']) && $_GET['action'] == 'remover' && isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Chamar a função para remover o cliente
    if (removerCliente($conn, $id_cliente)) {
        echo "<script>alert('Cliente removido com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao remover cliente');</script>";
    }
}

// Consultar clientes no banco de dados
$sql_clientes = "SELECT * FROM clientes";
$result_clientes = $conn->query($sql_clientes);

if ($result_clientes->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Ações</th></tr>";
    while ($row = $result_clientes->fetch_assoc()) {
        $id_cliente = $row['id'];
        $nome_cliente = $row['nome'];
        $email_cliente = isset($row['email']) ? $row['email'] : 'Email não cadastrado'; // Verifica se a chave 'email' existe
        $telefone_cliente = $row['telefone'];

        echo "<tr>";
        echo "<td>$id_cliente</td>";
        echo "<td>$nome_cliente</td>";
        echo "<td>$email_cliente</td>";
        echo "<td>$telefone_cliente</td>";
        echo "<td>";
        // Ajuste os links de ação conforme necessário
        echo "<a href='editar_cliente.php?id=$id_cliente'>Editar</a> | ";
        echo "<a href='listar_client.php?action=remover&id=$id_cliente' onclick='return confirm(\"Tem certeza que deseja remover este cliente?\")'>Remover</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum cliente encontrado.</p>";
}

// Fechar conexão com o banco de dados
$conn->close();
?>
