<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "solicitacoes";

// Criar uma conexão
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar a conexão
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $produto = $_POST["produto"];
    $detalhes = $_POST["detalhes"];
    $valor = $_POST["valor"];
    $quantidade = $_POST["quantidade"];

    // Preparar a consulta SQL para inserção de dados
    $sql = "INSERT INTO produtos_em_estoque (produto, detalhes, valor, quantidade) VALUES (?, ?, ?, ?)";

    // Preparar e executar a instrução
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular os parâmetros
        mysqli_stmt_bind_param($stmt, "ssdi", $produto, $detalhes, $valor, $quantidade);

        // Tentar executar a instrução
        if (mysqli_stmt_execute($stmt)) {
            echo "Dados inseridos com sucesso!";
        } else {
            echo "Erro ao inserir os dados: " . mysqli_error($conn);
        }

        // Fechar a declaração
        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da instrução: " . mysqli_error($conn);
    }
}
header("Location: index.php");
exit; // Certifique-se de sair do script após o redirecionamento
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
