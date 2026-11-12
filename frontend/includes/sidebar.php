<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon-small"></div>
        <h2>Escola Brasil</h2>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($pagina_solicitada == 'visao-geral') ? 'active' : '' ?>">
                <a href="index.php?pagina=visao-geral"><span class="material-symbols-outlined">dashboard</span> Visão Geral</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'alunos') ? 'active' : '' ?>">
                <a href="index.php?pagina=alunos"><span class="material-symbols-outlined">school</span> Alunos</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'turmas') ? 'active' : '' ?>">
                <a href="index.php?pagina=turmas"><span class="material-symbols-outlined">meeting_room</span> Turmas</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'professores') ? 'active' : '' ?>">
                <a href="index.php?pagina=professores"><span class="material-symbols-outlined">cast_for_education</span> Professores</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'disciplinas') ? 'active' : '' ?>">
                <a href="index.php?pagina=disciplinas"><span class="material-symbols-outlined">menu_book</span> Disciplinas</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'calendario') ? 'active' : '' ?>">
                <a href="index.php?pagina=calendario"><span class="material-symbols-outlined">calendar_month</span> Calendário</a>
            </li>
            <li class="<?= ($pagina_solicitada == 'relatorios') ? 'active' : '' ?>">
                <a href="index.php?pagina=relatorios"><span class="material-symbols-outlined">bar_chart</span> Relatórios</a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <span class="material-symbols-outlined">logout</span> Sair do Sistema
        </a>
    </div>
</div>