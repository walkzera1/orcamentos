<?php
// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    // Conexão com o banco de dados (substitua 'nome_do_banco', 'usuario' e 'senha' pelos valores corretos)
    $conn = new mysqli("localhost", "root", "", "solicitacoes");

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obtenha os valores do formulário
    $nomeCliente = $_POST["nomeCliente"];
    $itemSolicitado = $_POST["itemSolicitado"];
    $especificacaoTecnica = $_POST["especificacaoTecnica"];
    $sugestaoMarca = $_POST["sugestaoMarca"];
    $quantidade = $_POST["quantidade"];
    $nomeSolicitante = $_POST["nomeSolicitante"];
    $ondeSeraUtilizado = $_POST["ondeSeraUtilizado"];
    $prioridade = $_POST["prioridade"];
    $dataSolicitacao = $_POST["dataSolicitacao"];

    $status = "Novo";

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO solicitacoes (nomeCliente, itemSolicitado, especificacaoTecnica, sugestaoMarca, quantidade, nomeSolicitante, ondeSeraUtilizado, prioridade, dataSolicitacao, status) VALUES ('$nomeCliente', '$itemSolicitado', '$especificacaoTecnica', '$sugestaoMarca', $quantidade, '$nomeSolicitante', '$ondeSeraUtilizado', '$prioridade', '$dataSolicitacao','$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Solicitação enviada com sucesso!";
    } else {
        echo "Erro ao enviar a solicitação: " . $conn->error;
    }

    // Feche a conexão com o banco de dados
    $conn->close();
} else {
    echo "O formulário não foi enviado corretamente.";
}
header("Location: index.php");
exit; // Certifique-se de sair do script após o redirecionamento
?>
