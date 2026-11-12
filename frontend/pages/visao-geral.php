<?php
// Inclui a conexão com o banco de dados
require_once '../backend/config/conexao.php';

// 1. Contar Alunos Ativos
$stmtAlunos = $conn->query("SELECT COUNT(*) as total FROM alunos WHERE status = 'Ativo'");
$total_alunos = $stmtAlunos->fetch(PDO::FETCH_ASSOC)['total'];

// 2. Contar Turmas Ativas
$stmtTurmas = $conn->query("SELECT COUNT(*) as total FROM turmas WHERE status = 'Ativa'");
$total_turmas = $stmtTurmas->fetch(PDO::FETCH_ASSOC)['total'];

// 3. Contar Professores Ativos
$stmtProfessores = $conn->query("SELECT COUNT(*) as total FROM professores WHERE status = 'Ativo'");
$total_professores = $stmtProfessores->fetch(PDO::FETCH_ASSOC)['total'];

// 4. Contar Próximos Eventos (Substituindo as Ocorrências)
$stmtEventos = $conn->query("SELECT COUNT(*) as total FROM calendario_eventos WHERE data_evento >= CURDATE()");
$total_eventos = $stmtEventos->fetch(PDO::FETCH_ASSOC)['total'];
?>

<div class="page-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Visão Geral do Semestre</h1>
    <p style="color: var(--color-text-muted); font-size: 0.95rem;">Acompanhe os principais indicadores da instituição em tempo real.</p>
</div>

<!-- CARDS DE RESUMO DINÂMICOS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <span class="material-symbols-outlined">groups</span>
        </div>
        <div class="stat-details">
            <!-- Imprime o total real de alunos -->
            <h3><?= $total_alunos ?></h3>
            <p>Alunos Ativos</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <span class="material-symbols-outlined">meeting_room</span>
        </div>
        <div class="stat-details">
            <!-- Imprime o total real de turmas -->
            <h3><?= $total_turmas ?></h3>
            <p>Turmas Ativas</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon gold">
            <span class="material-symbols-outlined">badge</span>
        </div>
        <div class="stat-details">
            <!-- Imprime o total real de professores -->
            <h3><?= $total_professores ?></h3>
            <p>Professores Ativos</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon alert">
            <span class="material-symbols-outlined">event</span>
        </div>
        <div class="stat-details">
            <!-- Imprime os eventos futuros -->
            <h3><?= $total_eventos ?></h3>
            <p>Eventos Agendados</p>
        </div>
    </div>
</div>

<!-- SEÇÃO DE ACESSO RÁPIDO -->
<div class="quick-actions" style="margin-top: 40px;">
    <h2 style="font-size: 1.2rem; color: var(--color-blue-navy); margin-bottom: 20px;">Acesso Rápido</h2>
    <div class="actions-grid">
        
        <!-- Os botões agora redirecionam para as páginas corretas -->
        <button onclick="window.location.href='index.php?pagina=alunos'" class="action-btn" style="cursor: pointer;">
            <span class="material-symbols-outlined">person_add</span>
            Nova Matrícula
        </button>
        
        <button onclick="window.location.href='index.php?pagina=turmas'" class="action-btn" style="cursor: pointer;">
            <span class="material-symbols-outlined">class</span>
            Abrir Turma
        </button>
        
        <button onclick="window.location.href='index.php?pagina=calendario'" class="action-btn" style="cursor: pointer;">
            <span class="material-symbols-outlined">event</span>
            Agendar Evento
        </button>
        
        <!-- Botão Boletins foi removido conforme solicitado -->

    </div>
</div>