<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "root";
$password = "";
$database = "client2";

// Conectar ao banco de dados
$conn = new mysqli($hostname, $username, $password, $database);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>
