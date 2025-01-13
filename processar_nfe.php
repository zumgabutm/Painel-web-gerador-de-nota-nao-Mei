<?php
// Definir as configurações do banco de dados
$hostname = "localhost";
$username = "rafael_db5";
$password = "RpBfJH#a9zYUkV#yd*";
$database = "rafael_db5"; // Substitua pelo seu nome de banco de dados

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

    // Obter dados do cliente selecionado
    $sql_cliente = "SELECT nome, cpf_cnpj FROM clientes WHERE id = '$cliente_id'";
    $resultado_cliente = $conn->query($sql_cliente);
    if ($resultado_cliente === false) {
        echo "Erro na consulta SQL: " . $conn->error;
        mysqli_rollback($conn); // Rollback em caso de erro
        exit;
    }

    // Verificar se o cliente foi encontrado
    if ($resultado_cliente->num_rows === 0) {
        echo "Cliente não encontrado.";
        mysqli_rollback($conn); // Rollback em caso de cliente não encontrado
        exit;
    }

    // Se o cliente foi encontrado, obter seus dados
    $row_cliente = $resultado_cliente->fetch_assoc();
    $nome_cliente = $row_cliente['nome'];
    $cpf_cliente = $row_cliente['cpf_cnpj'];

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
        $sql_preco = "SELECT nome, preco FROM produtos WHERE id = '$produto_id'";
        $resultado_preco = $conn->query($sql_preco);
        if ($resultado_preco->num_rows > 0) {
            $row = $resultado_preco->fetch_assoc();
            $nome_produto = $row['nome'];
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

        // Exibir detalhes da nota fiscal em formato profissional
        echo "<!DOCTYPE html>";
        echo "<html lang='pt-br'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Nota Fiscal Eletrônica</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; }";
        echo "h1, h2, h3 { text-align: center; }";
        echo "table { width: 100%; border-collapse: collapse; margin-top: 20px; }";
        echo "table, th, td { border: 1px solid #ccc; }";
        echo "th, td { padding: 8px; text-align: left; }";
        echo "th { background-color: #f2f2f2; }";
        echo ".container { display: flex; justify-content: space-between; }";
        echo ".left, .center, .right { width: 32%; }";
        echo ".client { background-color: #f0e6ff; }"; // Cor de fundo para informações do cliente
        echo ".company { background-color: #f2dede; }"; // Cor de fundo para informações da empresa
        echo ".impostos { background-color: #e6f7ff; }"; // Cor de fundo para informações de impostos
        echo ".footer { text-align: center; margin-top: 20px; }"; // Rodapé
        echo ".signature { margin-top: 30px; text-align: right; }"; // Assinatura
        echo "</style>";
        echo "</head>";
        echo "<body>";

        // Cabeçalho da nota fiscal com logo e ícone
        echo "<div style='text-align: center;'>";
        echo "<img src='https://i.ibb.co/pjMZNZx/Placa-de-aviso-proibido-fumar-informativo-branco-vermelho.png' alt='RS.CONNECT_INFOR' style='max-width: 150px; display: block; margin: 0 auto;'>";
        echo "<h1>Nota eletronica</h1>";
        echo "<img src='https://img.icons8.com/ios-filled/50/000000/invoice.png' alt='Ícone Nota Fiscal' style='display: block; margin: 0 auto;'>";
        echo "</div>";

        // Informações da empresa
        echo "<div class='container'>";
        echo "<div class='left company'>";
        echo "<h2>Dados da Empresa</h2>";
        echo "<p><strong>Rafael da silva lourenco</strong></p>";
        echo "<p><strong>CNPJ:</strong> 132.954.454-43</p>";
        echo "<p><strong>Endereço:</strong> Caruaru, 23</p>";
        echo "<p><strong>Garantia:</strong> de 7 dias para reparo ou troca, de produtos com defeito de fabricação.</p>";     
        echo "</div>";

        // Dados da nota fiscal
        echo "<div class='center'>";
        echo "<h2>Dados da Nota Fiscal</h2>";
        echo "<p><strong>ID da Nota Fiscal:</strong> $id_nota_fiscal</p>";
        echo "<p><strong>Data de Emissão:</strong> " . date('d/m/Y H:i:s') . "</p>"; // Data atual
        echo "<p><strong>Número do Pedido:</strong> #924926</p>"; // Número do pedido fictício
        /*
echo "<p><strong>Data de Entrega:</strong> " . date('d/m/Y', strtotime('+5 days')) . "</p>"; // Data de entrega fictícia
*/
echo "</div>";


        // Dados do cliente
        echo "<div class='right client'>";
        echo "<h2>Dados do Cliente</h2>";
        echo "<p><strong>Nome:</strong> $nome_cliente</p>";
        echo "<p><strong>CPF/CNPJ:</strong> $cpf_cliente</p>";
        echo "</div>";
        echo "</div>";

        // Calcular valores de impostos fictícios
        $icms = $valor_total * (rand(10, 20) / 100); // ICMS entre 10% e 20%
        $ipi = $valor_total * (rand(5, 10) / 100);   // IPI entre 5% e 10%
        $pis = $valor_total * (rand(1, 2) / 100);    // PIS entre 1% e 2%
        $cofins = $valor_total * (rand(3, 8) / 100); // COFINS entre 3% e 8%

        // Tabela com os itens da nota fiscal
        echo "<table>";
        echo "<tr>";
        echo "<th>Produto</th>";
        echo "<th>Quantidade</th>";
        echo "<th>Preço Unitário</th>";
        echo "<th>Valor Total</th>";
        echo "</tr>";
        for ($i = 0; $i < count($produtos); $i++) {
            $produto_id = $produtos[$i];
            $quantidade = $quantidades[$i];

            // Obter nome e preço unitário do produto
            $sql_produto = "SELECT nome, preco FROM produtos WHERE id = '$produto_id'";
            $resultado_produto = $conn->query($sql_produto);
            if ($resultado_produto->num_rows > 0) {
                $row = $resultado_produto->fetch_assoc();
                $nome_produto = $row['nome'];
                $preco_unitario = $row['preco'];
            } else {
                $nome_produto = "Produto não encontrado";
                $preco_unitario = 0;
            }

            // Calcular valor total do item
            $valor_item = $preco_unitario * $quantidade;

            echo "<tr>";
            echo "<td>$nome_produto</td>";
            echo "<td>$quantidade</td>";
            echo "<td>R$ " . number_format($preco_unitario, 2, ',', '.') . "</td>";
            echo "<td>R$ " . number_format($valor_item, 2, ',', '.') . "</td>";
        }
        echo "<tr>";
        echo "<td colspan='3' style='text-align: right;'><strong>Valor Total:</strong></td>";
        echo "<td><strong>R$ " . number_format($valor_total, 2, ',', '.') . "</strong></td>";
        echo "</tr>";
        echo "</table>";

        // Exibir impostos fictícios
/*
echo "<div class='container'>";
echo "<div class='left impostos'>";
echo "<h2>Impostos</h2>";
echo "<p><strong>ICMS:</strong> R$ " . number_format($icms, 2, ',', '.') . " (" . (rand(10, 20)) . "%)</p>";
echo "<p><strong>IPI:</strong> R$ " . number_format($ipi, 2, ',', '.') . " (" . (rand(5, 10)) . "%)</p>";
echo "<p><strong>PIS:</strong> R$ " . number_format($pis, 2, ',', '.') . " (" . (rand(1, 2)) . "%)</p>";
echo "<p><strong>COFINS:</strong> R$ " . number_format($cofins, 2, ',', '.') . " (" . (rand(3, 8)) . "%)</p>";
echo "</div>";
echo "</div>";
*/


        // Assinatura do emitente
        // echo "<div class='signature'>";
// echo "<p><strong>Assinatura do Emitente:</strong></p>";
// echo "<p>______________________</p>";
// echo "</div>";

        echo "<p><strong>RS.CONNECT_INFOR</strong></p>";
        echo "</div>";

        // Rodapé
        echo "<div class='footer'>";
        echo "<p>Obrigado pela sua compra! Esta é uma nota fiscal emitida por uma Pessoa individual, com a mesma Responsabilidade legal de uma nota fiscal de MEI. Se precisar de mais informações, estamos à disposição</p>";
        echo "<p><strong>Email:</strong> dmrafael592@gmail.com Telefone (81) 98507-2087</p>";
        echo "</div>";

        echo "</body>";
        echo "</html>";
    } else {
        mysqli_rollback($conn); // Rollback em caso de erro
    }

    // Fechar conexão com o banco de dados
    $conn->close();
}
?>
