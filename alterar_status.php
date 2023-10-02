<?php
// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere o novo status, novo estágio e o ID da solicitação do formulário
    $novo_status = $_POST["novo_status"];
    $novo_estagio = $_POST["novo_estagio"];
    $solicitacao_id = $_POST["solicitacao_id"];

    // Conexão com o banco de dados (substitua 'nome_do_banco', 'usuario' e 'senha' pelos valores corretos)
    $conn = new mysqli("127.0.0.1", "root", "", "solicitacoes");

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Atualize o status e o estágio da solicitação no banco de dados
    $sql = "UPDATE solicitacoes SET status = '$novo_status', estagio = '$novo_estagio' WHERE id = '$solicitacao_id'";

    if ($conn->query($sql) === TRUE) {
        // Status e estágio atualizados com sucesso, redirecione para index.php
        header("Location: index.php");
        exit(); // Certifique-se de que o código pare de ser executado após o redirecionamento
    } else {
        echo "Erro ao atualizar o status e o estágio: " . $conn->error;
    }

    // Feche a conexão com o banco de dados
    $conn->close();
}
?>
