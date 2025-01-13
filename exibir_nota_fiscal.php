<?php
// Verificar se o ID da nota fiscal está sendo passado via parâmetro GET
if (isset($_GET['id_nota'])) {
    // Definir as configurações do banco de dados
    $hostname = "localhost";
$username = "root";
$password = "";
$database = "client2";

    // Incluir arquivo de conexão com o banco de dados
    require_once 'conexao.php';

    // Conectar ao banco de dados
    $conn = new mysqli($hostname, $username, $password, $database);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Limpar dados de entrada para evitar SQL Injection
    $id_nota = $_GET['id_nota'];
    $id_nota = mysqli_real_escape_string($conn, $id_nota);

    // Consultar a nota fiscal no banco de dados
    $sql = "SELECT * FROM notas_fiscais WHERE id = $id_nota";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Exibir os detalhes da nota fiscal
        $nota = $result->fetch_assoc();
        echo "Detalhes da Nota Fiscal:<br>";
        echo "ID: " . $nota['id'] . "<br>";
        echo "Cliente ID: " . $nota['cliente_id'] . "<br>";
        echo "Produto ID: " . $nota['produto_id'] . "<br>";
        echo "Valor Total: R$ " . number_format($nota['valor_total'], 2, ',', '.') . "<br>";
        echo "Data de Emissão: " . $nota['data_emissao'] . "<br>";

        // Exibir mais detalhes se necessário
    } else {
        echo "Nenhuma nota fiscal encontrada com o ID fornecido.";
    }

    // Fechar conexão com o banco de dados
    $conn->close();
} else {
    echo "ID da nota fiscal não especificado.";
}
?>
