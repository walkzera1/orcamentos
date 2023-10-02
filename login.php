<?php
// Arquivo: login.php

// Configurações do banco de dados (substitua com suas próprias informações)
$host = 'localhost';
$dbname = 'solicitacoes'; // Nome do seu banco de dados
$username = 'root';
$password = '';

// Função para verificar se o usuário está autenticado
function is_authenticated() {
    return isset($_SESSION['user_id']);
}

// Inicie a sessão (se ainda não estiver iniciada)
session_start();

if (is_authenticated()) {
    // Se o usuário já estiver autenticado, redirecione para index.php
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Conexão com o banco de dados
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Dados do formulário
        $email = $_POST['username']; // Alterado para usar o campo 'username' como email
        $password = $_POST['password'];

        // Consulta SQL para verificar as credenciais
        $sql = "SELECT * FROM users WHERE email = :email"; // Usando 'email' em vez de 'username'
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email); // Alterado para 'email'
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifique se o usuário existe e a senha está correta
        if ($user && password_verify($password, $user['password_hash'])) {
            // Armazene informações do usuário na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_full_name'] = $user['full_name'];
            $_SESSION['user_account_type'] = $user['account_type'];

            // Redirecione para index.php após o login bem-sucedido
            header('Location: index.php');
            exit();
        } else {
            $error_message = 'Credenciais inválidas. Tente novamente.';
        }
    } catch (PDOException $e) {
        // Lidar com erros de banco de dados
        $error_message = 'Erro: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Páginas / Login - Modelo NiceAdmin Bootstrap</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Nome do Modelo: NiceAdmin
  * Atualizado: 18 de Setembro de 2023 com Bootstrap v5.3.2
  * URL do Modelo: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Autor: BootstrapMade.com
  * Licença: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">NiceAdmin</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Faça Login na Sua Conta</h5>
                    <p class="text-center small">Digite seu nome de usuário e senha para fazer login</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate method="POST">

                    <div class="col-12">
                      <label for="seuNomeDeUsuario" class="form-label">Nome de Usuário</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="seuNomeDeUsuario" required>
                        <div class="invalid-feedback">Por favor, insira seu nome de usuário.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="suaSenha" class="form-label">Senha</label>
                      <input type="password" name="password" class="form-control" id="suaSenha" required>
                      <div class="invalid-feedback">Por favor, insira sua senha!</div>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="lembrarDeMim">
                        <label class="form-check-label" for="lembrarDeMim">Lembrar de mim</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <?php if (isset($error_message)) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php } ?>
                  </form>
                </div>
              </div>
              <div class="credits">
                <!-- Todos os links no rodapé devem permanecer intactos. -->
                <!-- Você só pode excluir os links se tiver adquirido a versão pro. -->
                <!-- Informações de Licenciamento: https://bootstrapmade.com/license/ -->
                <!-- Adquira a versão pro com formulário de contato PHP/AJAX funcional: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Projetado por <a href="https://bootstrapmade.com/">BootstrapMade</a>
              </div>
      </section>
  </main><!-- End #main -->
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>
