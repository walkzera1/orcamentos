<?php
// Verifique se o ID da solicitação foi fornecido na URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $solicitacao_id = $_GET['id'];

    // Conexão com o banco de dados (substitua 'nome_do_banco', 'usuario' e 'senha' pelos valores corretos)
    $conn = new mysqli("127.0.0.1", "root", "", "solicitacoes");

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para obter os detalhes da solicitação com base no ID
    $sql = "SELECT * FROM solicitacoes WHERE id = $solicitacao_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Exiba as informações detalhadas da solicitação
        $row = $result->fetch_assoc();
        echo "<h1>Detalhes da Solicitação</h1>";
        echo "<p><strong>Chamado:</strong> " . $row['id'] . "</p>";
        echo "<p><strong>Cliente:</strong> " . $row['nomeCliente'] . "</p>";
        echo "<p><strong>Item:</strong> " . $row['itemSolicitado'] . "</p>";
        echo "<p><strong>Especificação Técnica/Modelos:</strong> " . $row['especificacaoTecnica'] . "</p>";
        echo "<p><strong>Sugestão de Marca:</strong> " . $row['sugestaoMarca'] . "</p>";
        echo "<p><strong>Quantidade:</strong> " . $row['quantidade'] . "</p>";
        echo "<p><strong>Nome do Solicitante:</strong> " . $row['nomeSolicitante'] . "</p>";
        echo "<p><strong>Onde Será Utilizado:</strong> " . $row['ondeSeraUtilizado'] . "</p>";
        echo "<p><strong>Prioridade:</strong> " . $row['prioridade'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "<p><strong>Data da Solicitação:</strong> " . $row['dataSolicitacao'] . "</p>";
    } else {
        echo "Solicitação não encontrada.";
    }

    // Feche a conexão com o banco de dados
    $conn->close();
} else {
    echo "ID de solicitação inválido.";
}
?>
