<?php
// Conectar ao banco de dados e buscar até 5 clientes correspondentes ao termo digitado
$termo = $_POST['nomeCliente'];
// Faça a consulta SQL para buscar clientes com base no termo
// Substitua 'seu_usuario', 'sua_senha' e 'sua_base_de_dados' com suas credenciais reais
$conn = new mysqli('127.0.0.1', 'root', '', 'solicitacoes');
$query = "SELECT nome_cliente FROM clientes WHERE nome_cliente LIKE '%$termo%' LIMIT 5";
$resultado = $conn->query($query);

// Construir a lista de clientes correspondentes com botões personalizados usando classes de Bootstrap
if ($resultado->num_rows > 0) {
    echo '<div class="box-lista-clientes">';
    echo '<ul class="list-group">';
    while ($row = $resultado->fetch_assoc()) {
        echo '<li class="list-group-item"><button type="button" class="btn btn-light btn-block cliente-button" data-nome="' . $row['nome_cliente'] . '">' . $row['nome_cliente'] . '</button></li>';
    }
    echo '</ul>';
    echo '</div>';
} else {
    echo '<div class="box-lista-clientes">';
    echo '<div class="list-group-item">Nenhum cliente encontrado</div>';
    echo '</div>';
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
