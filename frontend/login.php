<?php
// Inicia a sessão (preparação para quando você conectar o login ao banco de dados)
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Escola Brasil</title>
    <!-- Link apontando para a pasta CSS -->
    <link rel="stylesheet" href="css/estilo.css">
    <!-- Fonte do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Teste de Responsividade Forçada */
        @media (max-width: 768px) {
            body { flex-direction: column !important; }
            
            /* Se a sua div principal não tiver a classe .app-container, isso força pelo layout */
            body > div:first-of-type { 
                display: flex !important; 
                flex-direction: column !important; 
            }

            .sidebar {
                width: 100% !important;
                position: relative !important;
                min-height: auto !important;
                height: auto !important;
                padding: 20px !important;
            }

            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                padding: 15px !important;
            }

            .page-header {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 15px !important;
            }

            .stats-grid, .actions-grid {
                display: grid !important;
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>

    <!-- ESSA DIV É A RESPONSÁVEL POR CENTRALIZAR A TELA DE LOGIN -->
    <div class="login-page-wrapper">
        
        <div class="login-container">
            <!-- Lado Esquerdo: Branding / Apresentação -->
            <div class="login-banner">
                <div class="banner-content">
                    <div class="logo-icon"></div>
                    <h1>Escola Brasil</h1>
                    <p>Gestão educacional de excelência. Acesse seu painel de controle.</p>
                </div>
                <!-- Elementos decorativos de fundo -->
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
            </div>

            <!-- Lado Direito: Formulário de Login -->
            <div class="login-form-section">
                <div class="form-wrapper">
                    <h2>Bem-vindo de volta</h2>
                    <p class="subtitle">Insira suas credenciais para acessar o dashboard.</p>

                    <!-- O action agora aponta para o index.php (o roteador do seu dashboard) via POST -->
                    <form action="index.php" method="POST">
                        <div class="input-group">
                            <label for="email">E-mail corporativo</label>
                            <input type="email" id="email" name="email" placeholder="nome@escolabrasil.edu.br" required>
                        </div>

                        <div class="input-group">
                            <label for="password">Senha</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>

                        <div class="form-actions">
                            <div class="remember-me">
                                <input type="checkbox" id="remember">
                                <label for="remember">Lembrar de mim</label>
                            </div>
                            <a href="#" class="forgot-password">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="btn-login">Entrar no Dashboard</button>
                    </form>

                    <div class="form-footer">
                        <p>Precisa de ajuda? <a href="#">Contate o suporte TI</a></p>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- Fim do login-page-wrapper -->


</body>
</html>