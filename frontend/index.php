<?php
session_start();

// 1. RECEBE OS DADOS DO LOGIN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    if ($email === 'admin@escolabrasil.edu.br' && $senha === '123Brasil@') {
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?erro=1");
        exit();
    }
}

// 2. PROTEÇÃO DE TELA (Bloqueia quem não fez login)
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// 3. ROTEAMENTO DAS PÁGINAS
$pagina_solicitada = isset($_GET['pagina']) ? $_GET['pagina'] : 'visao-geral';
$paginas_permitidas = ['visao-geral', 'alunos', 'turmas', 'professores', 'disciplinas', 'calendario', 'relatorios'];

if (!in_array($pagina_solicitada, $paginas_permitidas)) {
    $pagina_solicitada = 'visao-geral';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Escola Brasil</title>
    
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

   <style>
    /* ========================================== */
    /* ESTRUTURA BASE DO DASHBOARD (COMPUTADOR)   */
    /* ========================================== */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background-color: var(--color-bg, #f1f5f9);
        overflow: hidden; /* Corta a rolagem dupla do navegador */
    }

    .app-container {
        display: flex;
        flex-direction: row;
        align-items: flex-start; /* Garante que o menu não desce */
        width: 100%;
        height: 100vh;
    }

    /* Oculta as quebras e esconde a barra de rolagem */
    .sidebar {
        width: 260px;
        height: 100vh;
        background-color: #0f172a;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .sidebar::-webkit-scrollbar {
        display: none;
    }

    .main-content {
        flex: 1;
        min-width: 0; /* O SEGREDO: Impede a tabela de quebrar o layout */
        height: 100vh;
        padding: 32px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* ========================================== */
    /* MAGIA DA RESPONSIVIDADE (CELULARES)        */
    /* ========================================== */
    @media (max-width: 768px) {
        .app-container {
            flex-direction: column;
        }

        .sidebar {
            width: 100% !important;
            height: auto !important;
        }

        .main-content {
            padding: 16px !important;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px;
        }

        .page-header button {
            width: 100%;
        }

        .stats-grid, .actions-grid {
            grid-template-columns: 1fr !important;
            display: grid;
            gap: 16px;
        }

        div[id^="modal"] > div {
            width: 95% !important;
            padding: 20px !important;
            max-height: 90vh;
            overflow-y: auto;
        }

        form > div[style*="display: flex"] {
            flex-direction: column;
            gap: 16px !important;
        }
    }
</style>
</head>
<body>
    
    <div class="app-container">
        
        <!-- MENU LATERAL -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- CONTEÚDO DA PÁGINA (Telas) -->
        <main class="main-content">
            <?php 
                $arquivo = "pages/{$pagina_solicitada}.php";
                if (file_exists($arquivo)) {
                    include $arquivo;
                } else {
                    echo "<h2 style='color: var(--color-blue-navy);'>Página não encontrada!</h2>";
                }
            ?>
        </main>

    </div>

</body>
</html>