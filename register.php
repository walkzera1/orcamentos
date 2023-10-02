<?php
// Arquivo: register.php

// Configurações do banco de dados (substitua com suas próprias informações)
$host = 'localhost';
$dbname = 'solicitacoes';
$username = 'root';
$password = '';

try {
    // Conexão com o banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("USE $dbname"); // Selecionar o banco de dados

    // Dados do formulário
    $full_name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $account_type = $_POST['account_type']; // Corrigido o nome do campo

    // Inserir dados na tabela de usuários
    $sql = "INSERT INTO users (full_name, email, password_hash, account_type) VALUES (:full_name, :email, :password, :account_type)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':account_type', $account_type);
    $stmt->execute();

    // Redirecionar para a página de sucesso ou fazer o que desejar após o registro
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    // Lidar com erros de banco de dados
    echo 'Erro: ' . $e->getMessage();
}

?>
