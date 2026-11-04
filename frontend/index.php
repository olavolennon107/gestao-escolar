<?php
session_start();

// Define qual página deve ser carregada. Se não houver nada na URL, carrega a 'visao-geral'
$pagina_solicitada = isset($_GET['pagina']) ? $_GET['pagina'] : 'visao-geral';

// Lista de páginas que existem no sistema (Segurança)
$paginas_permitidas = ['visao-geral', 'alunos', 'turmas', 'professores', 'disciplinas', 'calendario', 'relatorios'];

// Se tentarem acessar uma página que não existe, força voltar para a visão geral
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
</head>
<body>

    <div class="dashboard-layout">
        
        <!-- Puxa o Menu Lateral -->
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            
            <!-- Puxa o Cabeçalho Superior -->
            <?php include 'includes/header.php'; ?>

            <!-- Área onde o conteúdo muda -->
            <main class="dashboard-content">
                <?php include "pages/{$pagina_solicitada}.php"; ?>
            </main>
            
        </div>
    </div>

</body>
</html>