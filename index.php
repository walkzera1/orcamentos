<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirecionar para a página de login se o usuário não estiver autenticado
    header('Location: login.php');
    exit();
}

// Conexão com o banco de dados (substitua com suas credenciais)
$mysqli = new mysqli("127.0.0.1", "root", "", "solicitacoes");

// Verifique a conexão
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Recuperar o ID do usuário logado
$user_id = $_SESSION['user_id'];
$query = "SELECT full_name FROM users WHERE id = $user_id";
$result = $mysqli->query($query);

if ($result) {
    $userData = $result->fetch_assoc();
    $userFullName = $userData['full_name'];
} else {
    // Trate erros aqui, se necessário
    $userFullName = "Nome do Usuário Desconhecido";
}

// Consulta SQL para buscar notificações do banco de dados relacionadas ao usuário logado
$query = "SELECT * FROM suas_notificacoes WHERE user_id = $user_id";
// Exemplo para criar uma notificação de novo orçamento
$newBudgetMessage = "Novo orçamento criado: [Nome do Orçamento]";
$insertNewBudgetNotification = "INSERT INTO suas_notificacoes (message, estagio, timestamp) VALUES ('$newBudgetMessage', 'Precificação', NOW())";
// Execute a consulta SQL para inserir a notificação no banco de dados

// Exemplo para criar uma notificação de alteração de status
$statusChangeMessage = "Status do orçamento [Nome do Orçamento] alterado para [Novo Status]";
$insertStatusChangeNotification = "INSERT INTO suas_notificacoes (message, timestamp) VALUES ('$statusChangeMessage', NOW())";
// Execute a consulta SQL para inserir a notificação no banco de dados

$result = $mysqli->query($query);

if (!$result) {
    die("Erro ao buscar notificações: " . $mysqli->error);
}

$notifications = array();

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
function getIconNameForEstagio($estagio) {
    $iconNames = [
        'Aguardando Fornecedor' => 'truck',
        'Concluído' => 'check-circle',
        'Pause' => 'pause-circle',
        'Precificação' => 'money-bill-wave',
        'Enviado ao Cliente' => 'envelope',
        'Nova Solicitação' => 'plus-circle',
        'Recebido' => 'check-circle'
        // Adicione outros mapeamentos de estágio para ícone aqui
    ];

    return isset($iconNames[$estagio]) ? $iconNames[$estagio] : 'question-circle'; // Ícone de ponto de interrogação se não houver correspondência
}
// Feche a conexão com o banco de dados
$mysqli->close();

// Recuperar o tipo de conta do usuário a partir da sessão
$userRole = $_SESSION['user_account_type'];

// Função para obter a classe de estilo do status

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <title>Painel </title>
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
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-KiIQiME1lS/5ho/axVKLbEOptSDGjTLxFn3e+4C2eIM2fzaFcTfTPvZMyxxZB4lZ6jTkL51iFw84ixbXX07aVQ==" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- =======================================================
  * Template Name: Rafael
  * Updated: Sep 18 2023 with Rafael Altissimo
  * Author: Rafael Altissimo
  ======================================================== -->
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">Sysmain</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
        <li class="nav-item dropdown pe-3">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $userFullName; ?></span>
    </a><!-- End Profile Image Icon -->
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
            <h6><?php echo $userFullName; ?></h6>
        </li>
 

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav>
    <!-- Formulário oculto -->
<div id="formularioOrcamento" class="card" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);  width: 35%;">
                <div class="card-body">
                    <h5 class="card-title" style="height: 150%;">Solicitar Novo Orçamento</h5>
                    <!-- Formulário de Solicitação -->
                    <form method="POST" action="processar_formulario.php">
                    <div class="mb-3">
    <label for="nomeCliente" class="form-label">NOME DO CLIENTE:</label>
    <input type="text" class="form-control" id="nomeCliente" name="nomeCliente" required autocomplete="off" oninput="buscarClientes(this.value)">
    <div id="listaClientes" class="lista-clientes"></div>
</div>

                        <div class="mb-3">
                            <label for="itemSolicitado" class="form-label">ÍTEM SOLICITADO:</label>
                            <input type="text" class="form-control" id="itemSolicitado" name="itemSolicitado" required>
                        </div>

                        <div class="mb-3">
                            <label for="especificacaoTecnica" class="form-label">ESPECIFICAÇÃO TÉCNICA/MODELOS:</label>
                            <input class="form-control" id="especificacaoTecnica" name="especificacaoTecnica" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sugestaoMarca" class="form-label">SUGESTÃO DE MARCA:</label>
                            <input type="text" class="form-control" id="sugestaoMarca" name="sugestaoMarca">
                        </div>

                        <div class="mb-3">
                            <label for="quantidade" class="form-label">QUANTIDADE:</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>

                        <div class="mb-3">
                            <label for="nomeSolicitante" class="form-label">NOME DO SOLICITANTE:</label>
                            <input type="text" class="form-control" id="nomeSolicitante" name="nomeSolicitante" required>
                        </div>

                        <div class="mb-3">
                            <label for="ondeSeraUtilizado" class="form-label">ONDE SERÁ UTILIZADO:</label>
                            <input type="text" class="form-control" id="ondeSeraUtilizado" name="ondeSeraUtilizado" required>
                        </div>

                        <div class="mb-3">
                            <label for="prioridade" class="form-label">PRIORIDADE:</label>
                            <select class="form-select" id="prioridade" name="prioridade" required>
                                <option value="baixa">BAIXA</option>
                                <option value="media">MÉDIA</option>
                                <option value="urgente">URGENTE</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" id="dataSolicitacao" name="dataSolicitacao" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar Solicitação</button>
                        <button type="button" class="btn btn-secondary" id="fecharFormularioOrcamento">Fechar</button>

                    </form>
                </div>
            </div>
            </div>
            </div>
  </header><!-- End Header -->
 <!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
    <?php if ($userRole === 'Técnico') { ?>
    <!-- Conteúdo da sessão "Dashboard Técnico" -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-grid"></i>
                    <span>Técnico</span>
                </a>
            </li>
        <?php } ?>
        
        <?php if ($userRole === 'Comercial') { ?>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="toggleComercialSection();">Comercial</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.html" onclick="toggleComercialSection();">Novo Usuario</a>
            </li>
        <?php } ?>
      
        <li class="nav-heading">Menu</li>
        
         <li class="nav-item">
         <span id="novoOrcamentoBtn" class="btn btn-primary">Novo Orçamento</span>
            </li>
            <li class="nav-item">
            <span id="mostrarFormulario" class="btn btn-primary">Adicionar Item ao Estoque</span>
            </li>
    </ul>
    <div id="formularioEstoque" class="card top-selling overflow-auto" style="display: none;">
    <div class="card-body pb-0">
        <h5 class="card-title">Novo Item no Estoque</h5>
        <form method="POST" action="processar_estoque.php">
            <div class="mb-3">
                <label for="produto" class="form-label">Produto</label>
                <input type="text" class="form-control" id="produto" name="produto" required>
            </div>
            <div class="mb-3">
                <label for="detalhes" class="form-label">Detalhes</label>
                <input type="text" class="form-control" id="detalhes" name="detalhes">
            </div>
            <div class="mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="text" class="form-control" id="valor" name="valor" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>
</div>
</aside>
<div class="row">
<!-- End Sidebar -->
<?php if ($userRole === 'Técnico') { ?>
        <section class="dashboard-tecnico">
            <main id="main" class="main">
                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">
            <?php if ($userRole === 'Técnico') { ?>
    <!-- Conteúdo da sessão "Dashboard Técnico" -->
  
                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Orçamentos</h5>
                            
                                <table class="table table-borderless datatable table-orcamentos">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nº Solicitação</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Estágio</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        function getStatusBadgeClass($status, $statusClasses) {
                                            return isset($statusClasses[$status]) ? $statusClasses[$status] : 'bg-secondary-light';
                                        }
                                        $statusClassesTecnico = [
                                            'Novo' => 'bg-estagio-novo',
                                            'Aprovado' => 'bg-estagio-aprovado',
                                            'Pendente' => 'bg-estagio-pendente',
                                            'Recusado' => 'bg-estagio-recusado',
                                            // Adicione outras classes de status aqui
                                        ];
                                        
                                        // Na seção do Dashboard Comercial
                                  

                                        // Conexão com o banco de dados (substitua pelos seus dados de conexão)
                                        $conexao = mysqli_connect("127.0.0.1", "root", "", "solicitacoes");
                                        // Verifica a conexão
                                        if (mysqli_connect_errno()) {
                                            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
                                        }

                                        if (!empty($_POST['query'])) {
                                            $query = $_POST['query'];
                                            // Consulta SQL para buscar dados da tabela "solicitacoes" com base na busca
                                            $sql = "SELECT * FROM solicitacoes WHERE nomeCliente LIKE '%$query%' OR itemSolicitado LIKE '%$query%' OR especificacaoTecnica LIKE '%$query%'";
                                        } else {

                                            $sql = "SELECT * FROM solicitacoes";
                                        }

                                        $result = mysqli_query($conexao, $sql);

                                        $statusClasses = [
                                            'Novo' => 'bg-estagio-novo',
                                            'Aprovado' => 'bg-estagio-aprovado',
                                            'Pendente' => 'bg-estagio-pendente',
                                            'Recusado' => 'bg-estagio-recusado',
                                            // Adicione outras classes de status aqui
                                        ];
                                        $classIndex = 0;
                                        $statusClassesComercial = $statusClasses;

                                        if (mysqli_num_rows($result) > 0) {
                                          $zebraClass = ''; // Classe para controle de zebra
                                          
                                          if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td><a href="#" data-bs-toggle="modal" data-bs-target="#solicitacaoModal' . $row['id'] . '">' . $row['id'] . '</a></td>';
                                                echo '<td>' . $row['nomeCliente'] . '</td>';
                                                echo '<td>' . $row['itemSolicitado'] . '</td>';
                                                echo '<td><span class="badge ' . getStatusBadgeClass($row['status'], $statusClasses) . '">' . $row['status'] . '</span></td>';

                                                $estagio = $row['estagio'];
                                                $estagioBadgeClass = isset($coresEstagio[$estagio]) ? 'style="background-color:' . $coresEstagio[$estagio] . '"' : '';

                                                echo '<td ' . $estagioBadgeClass . '><i class="fas fa-' . getIconNameForEstagio($estagio) . '"></i> ' . $row['estagio'] . '</td>';
                                                echo '</tr>';
                                                $classIndex = ($classIndex + 1) % count($statusClasses);
                                                // Modal para exibir detalhes da solicitação
                                                echo '<div class="modal fade" id="solicitacaoModal' . $row['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                                                echo '<div class="modal-dialog">';
                                                echo '<div class="modal-content">';
                                                echo '<div class="modal-header">';
                                                echo '<h5 class="modal-title" id="exampleModalLabel">Detalhes do Chamado #' . $row['id'] . '</h5>';
                                                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                                echo '</div>';
                                                echo '<div class="modal-body">';
                                                echo '<p><strong>Cliente:</strong> ' . $row['nomeCliente'] . '</p>';
                                                echo '<p><strong>Item:</strong> ' . $row['itemSolicitado'] . '</p>';
                                                echo '<p><strong>Especificação:</strong> ' . $row['especificacaoTecnica'] . '</p>';
                                                echo '<p><strong>Quantidade:</strong> ' . $row['quantidade'] . '</p>';
                                                echo '<p><strong>Solicitante:</strong> ' . $row['nomeSolicitante'] . '</p>';
                                                echo '<p><strong>Data:</strong> ' . $row['dataSolicitacao'] . '</p>';
                                                echo '<p><strong>Onde será Utilizado:</strong> ' . $row['ondeSeraUtilizado'] . '</p>';
                                                echo '<p><strong>Prioridade:</strong> ' . $row['prioridade'] . '</p>';
                                                // Adicione mais campos conforme necessário
                                                echo '</div>';
                                                echo '<div class="modal-footer">';
                                                echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="4">Nenhum orçamento encontrado.</td></tr>';
                                        }

                                        // Fecha a conexão
                                        mysqli_close($conexao);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div><!-- End Recent Sales -->
<?php } ?>
                    <!-- Top Selling -->
                    <div class="col-12">
                        <div class="card top-selling overflow-auto">
                            <div class="card-body pb-0">
                                <table class="table table-borderless">
                                                                    <h5 class="card-title">Produtos <span>em estoque</span></h5>

                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Produto</th>
                                            <th scope="col">Detalhes</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aqui você pode usar PHP para buscar os dados do banco de dados e preencher a tabela -->
                                        <?php
                                        // Conexão com o banco de dados (substitua pelos seus dados de conexão)
                                        $conexao = mysqli_connect("127.0.0.1", "root", "", "solicitacoes");
                                        // Verifica a conexão
                                        if (mysqli_connect_errno()) {
                                            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
                                        }

                                        $query = "SELECT * FROM produtos_em_estoque";
                                        $resultado = mysqli_query($conexao, $query);

                                        while ($row = mysqli_fetch_assoc($resultado)) {
                                            echo "<tr>";
                                            echo "<th scope='row'><a href='#'>" . $row['id'] . "</a></th>";
                                            echo "<td><a href='#' class='text-primary fw-bold'>" . $row['produto'] . "</a></td>";
                                            echo "<td>" . $row['detalhes'] . "</td>";
                                            echo "<td>R$" . number_format($row['valor'], 2, ',', '.') . "</td>";
                                            echo "<td class='fw-bold'>" . $row['quantidade'] . "</td>";
                                            echo "</tr>";
                                        }
                                        mysqli_close($conexao);
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- End Top Selling -->
    </section>
<?php } ?>
</main>
                        </div>
                    </div>
                </section>
            </div><!-- End Left side columns -->
        <div class="col-lg-6">
        </section>
<?php } ?>

</main><!-- End #main -->
<?php if ($userRole === 'Comercial') {
?>
<!-- Conteúdo específico para Comercial -->
<section class="dashboard-comercial">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 120vh;">
            <div class="col-md-8">
                <!-- Recent Sales -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">
                            <h5 class="card-title">Orçamentos</h5>
                            <table class="table table-borderless datatable table-orcamentos">
                                <thead>
                                    <tr>
                                        <th scope="col">Nº Orçamento</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Estágio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Conecte-se ao banco de dados
                                    $conn = new mysqli("127.0.0.1", "root", "", "solicitacoes");

                                    // Verifique a conexão
                                    if ($conn->connect_error) {
                                        die("Falha na conexão com o MySQL: " . $conn->connect_error);
                                    }

                                    // Consulta SQL para buscar os orçamentos
                                    $query = "SELECT * FROM solicitacoes"; // Substitua 'tabela_orcamentos' pelo nome da sua tabela no banco de dados
                                    $result = $conn->query($query);

                                    // Verifique se a consulta foi bem-sucedida
                                    if (!$result) {
                                        die("Erro na consulta ao banco de dados: " . $conn->error);
                                    }

                                    // Definir as classes de status
                                    $statusClasses = [
                                        'Novo' => 'bg-estagio-novo',
                                        'Aprovado' => 'bg-estagio-aprovado',
                                        'Pendente' => 'bg-estagio-pendente',
                                        'Recusado' => 'bg-estagio-recusado',
                                        // Adicione outras classes de status aqui
                                    ];
                                    $classIndex = 0;
                                    function getStatusBadgeClass($status, $statusClasses) {
                                        return isset($statusClasses[$status]) ? $statusClasses[$status] : 'bg-secondary-light';
                                    }
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td><a href="#" data-bs-toggle="modal" data-bs-target="#solicitacaoModal' . $row['id'] . '">' . $row['id'] . '</a></td>';
                                            echo '<td>' . $row['nomeCliente'] . '</td>';
                                            echo '<td>' . $row['itemSolicitado'] . '</td>';
                                            echo '<td><span class="badge ' . getStatusBadgeClass($row['status'], $statusClasses) . '">' . $row['status'] . '</span></td>';

                                            $estagio = $row['estagio'];
                                            $estagioBadgeClass = isset($coresEstagio[$estagio]) ? 'style="background-color:' . $coresEstagio[$estagio] . '"' : '';

                                            echo '<td ' . $estagioBadgeClass . '><i class="fas fa-' . getIconNameForEstagio($estagio) . '"></i> ' . $row['estagio'] . '</td>';
                                            echo '</tr>';
                                            $classIndex = ($classIndex + 1) % count($statusClasses);

                                            include('modal.php'); // Inclua o modal em um arquivo separado para melhor organização
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">Nenhum orçamento encontrado.</td></tr>';
                                    }

                                    // Feche a conexão com o banco de dados
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Selling -->
                <div class="col-12">
                    <div class="card top-selling overflow-auto">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Produtos <span>em estoque</span></h5>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Detalhes</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Conecte-se ao banco de dados
                                    $conexao = mysqli_connect("127.0.0.1", "root", "", "solicitacoes");

                                    if (mysqli_connect_errno()) {
                                        echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
                                    }

                                    $query = "SELECT * FROM produtos_em_estoque";
                                    $resultado = mysqli_query($conexao, $query);

                                    while ($row = mysqli_fetch_assoc($resultado)) {
                                        echo "<tr>";
                                        echo "<th scope='row'><a href='#'>" . $row['id'] . "</a></th>";
                                        echo "<td><a href='#' class='text-primary fw-bold'>" . $row['produto'] . "</a></td>";
                                        echo "<td>" . $row['detalhes'] . "</td>";
                                        echo "<td>R$" . number_format($row['valor'], 2, ',', '.') . "</td>";
                                        echo "<td class='fw-bold'>" . $row['quantidade'] . "</td>";
                                        echo "</tr>";
                                    }

                                    // Feche a conexão com o banco de dados
                                    mysqli_close($conexao);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- End Top Selling -->
            </div>
        </div>
    </div>
</section>
<?php } ?>

    <script>
        function abrirModal(id) {
            // Atualize as informações no modal com os detalhes do orçamento
            // Isso pode ser feito com JavaScript ou usando PHP para buscar os detalhes do orçamento com base no ID.
            // Aqui, estou usando informações de exemplo.
            document.getElementById("exampleModalLabel" + id).textContent = "Detalhes do Orçamento #" + id;
            document.getElementById("status" + id).value = "Novo"; // Defina o status padrão como "Pendente" (ou outro valor padrão)

            // Abra o modal
            var modal = new bootstrap.Modal(document.getElementById("solicitacaoModal" + id));
            modal.show();
        }
        $(document).ready(function() {
    $('#nomeCliente').on('input', function() {
        var inputText = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'buscar_clientes.php', // Arquivo PHP para buscar clientes no banco de dados
            data: { nomeCliente: inputText },
            success: function(response) {
                $('#listaClientes').html(response);
            }
        });
    });

    // Manipular a seleção de um cliente na lista
    // Manipular o clique em um botão de cliente
    $('#listaClientes').on('click', '.cliente-button', function() {
        var clienteNome = $(this).data('nome');
        $('#nomeCliente').val(clienteNome);
        $('#listaClientes').empty(); // Limpar a lista após a seleção do cliente
    });
});


$(document).ready(function() {
    // Função para atualizar a página
    function atualizarPagina() {
        location.reload(); // Recarrega a página
    }

    // Agende a atualização da página a cada 10 minutos (150000 milissegundos)
    setTimeout(atualizarPagina, 150000);
});
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const botaoMostrarFormulario = document.getElementById("mostrarFormulario");
            const formularioEstoque = document.getElementById("formularioEstoque");

            botaoMostrarFormulario.addEventListener("click", function () {
                if (formularioEstoque.style.display === "none") {
                    formularioEstoque.style.display = "block";
                } else {
                    formularioEstoque.style.display = "none";
                }
            });
        });
        // Obtenha referências para o botão e o formulário
        const novoOrcamentoBtn = document.getElementById("novoOrcamentoBtn");
        const formularioOrcamento = document.getElementById("formularioOrcamento");
        const fecharFormularioOrcamento = document.getElementById("fecharFormularioOrcamento");

        // Função para mostrar o formulário
        function mostrarFormulario() {
            formularioOrcamento.style.display = "block";
        }
        // Função para ocultar o formulário
        function ocultarFormulario() {
            formularioOrcamento.style.display = "none";
        }
        // Adicione um evento de clique ao botão para mostrar o formulário
        novoOrcamentoBtn.addEventListener("click", mostrarFormulario);
        // Adicione um evento de clique no botão de fechar para ocultar o formulário
        fecharFormularioOrcamento.addEventListener("click", ocultarFormulario);
        // Adicione um evento de clique fora do formulário para ocultá-lo
        window.addEventListener("click", function (event) {
            if (event.target === formularioOrcamento) {
                ocultarFormulario();
            }
        });
    </script>

    <script>
        // Função para atualizar as opções de estágio com base no status selecionado
        function updateEstagioOptions(statusSelect, estagioSelect) {
    const status = statusSelect.value;
    const estagio = estagioSelect.value;

    // Limpar as opções atuais
    estagioSelect.innerHTML = '';

    // Adicionar opções de estágio com base no status selecionado
    if (status === 'Aprovado') {
        const options = [
            { value: 'Aguardando Fornecedor', icon: 'fa-truck' },
            { value: 'Concluído', icon: 'fa-check-circle' },
            { value: 'Pause', icon: 'fa-pause-circle' }
        ];
        addOptionsWithIcons(options, estagioSelect);
    } else if (status === 'Pendente') {
        const options = [
            { value: 'Precificação', icon: 'fa-money-bill-wave' },
            { value: 'Enviado ao Cliente', icon: 'fa-envelope' },
            { value: 'Pause', icon: 'fa-pause-circle' }
        ];
        addOptionsWithIcons(options, estagioSelect);
    } else if (status === 'Recusado') {
        const options = [
            { value: 'Concluído', icon: 'fa-check-circle' },
            { value: 'Nova Solicitação', icon: 'fa-plus-circle' }
        ];
        addOptionsWithIcons(options, estagioSelect);
    } else if (status === 'Novo') {
        const options = [
            { value: 'Recebido', icon: 'fa-check-circle' },
            { value: 'Pause', icon: 'fa-pause-circle' }
        ];
        addOptionsWithIcons(options, estagioSelect);
    }
}

function addOptionsWithIcons(options, selectElement) {
    options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.textContent = option.value;
        const icon = document.createElement('i');
        icon.classList.add('fas', option.icon); // Adicione classes do Font Awesome para o ícone
        optionElement.prepend(icon); // Adicione o ícone antes do texto do item
        selectElement.appendChild(optionElement);
    });
}
       
        // Adicionar evento de alteração ao campo de status
        const statusSelects = document.querySelectorAll('.novo_status');
        const estagioSelects = document.querySelectorAll('.novo_estagio');

        statusSelects.forEach((statusSelect, index) => {
            const estagioSelect = estagioSelects[index];
            statusSelect.addEventListener('change', () => {
                updateEstagioOptions(statusSelect, estagioSelect);
            });

            // Chamar a função inicialmente para definir as opções de estágio com base no valor inicial do status
            updateEstagioOptions(statusSelect, estagioSelect);
        });
    </script>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>