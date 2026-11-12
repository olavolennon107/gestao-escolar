<?php
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/turma.php';

$turmaController = new TurmaController($conn);

// PROCESSAR AÇÕES DO BANCO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_turma') {
        $turmaController->cadastrarTurma($_POST['nome'], $_POST['turno'], $_POST['sala'], $_POST['capacidade'], $_POST['status']);
    } 
    elseif ($acao == 'editar_turma') {
        $turmaController->editarTurma($_POST['id'], $_POST['nome'], $_POST['turno'], $_POST['sala'], $_POST['capacidade'], $_POST['status']);
    } 
    elseif ($acao == 'excluir_turma') {
        $turmaController->excluirTurma($_POST['id']);
    }

    // Recarrega a página
    echo "<script>window.location.href='index.php?pagina=turmas';</script>";
    exit();
}

// Buscar lista de turmas
$listaTurmas = $turmaController->listarTurmas();
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Gestão de Turmas</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Administre as turmas, horários e salas de aula da instituição.</p>
    </div>
    <button type="button" onclick="abrirModalTurma()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Nova Turma</button>
</div>

<!-- TABELA -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">

<!-- Fecha a div do fundo branco -->
</div>
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Turma</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Turno</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Sala</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Ocupação</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Status</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaTurmas)): ?>
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhuma turma cadastrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaTurmas as $turma): 
                    $id = $turma['id'];
                    $nome_js = htmlspecialchars(addslashes($turma['nome']));
                    $turno_js = $turma['turno'];
                    $sala_js = htmlspecialchars(addslashes($turma['sala']));
                    $capacidade_js = $turma['capacidade'];
                    $alunos_matriculados = $turma['alunos_matriculados'];
                    $status_js = $turma['status'];
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= htmlspecialchars($turma['nome']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $turma['turno'] ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($turma['sala']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $alunos_matriculados ?> / <?= $turma['capacidade'] ?> alunos</td>
                        <td style="padding: 16px 12px;">
                            <?php if ($turma['status'] == 'Ativa'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Ativa</span>
                            <?php elseif ($turma['status'] == 'Lotada'): ?>
                                <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Lotada</span>
                            <?php else: ?>
                                <span style="background: rgba(100, 116, 139, 0.1); color: var(--color-text-muted); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= $turma['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            
                            <!-- VISUALIZAR -->
                            <button type="button" onclick="abrirModalViewTurma('<?= $nome_js ?>', '<?= $turno_js ?>', '<?= $sala_js ?>', '<?= $alunos_matriculados ?>', '<?= $capacidade_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar Detalhes">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>

                            <!-- EDITAR -->
                            <button type="button" onclick="abrirModalEditTurma('<?= $id ?>', '<?= $nome_js ?>', '<?= $turno_js ?>', '<?= $sala_js ?>', '<?= $capacidade_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            
                            <!-- EXCLUIR -->
                            <form action="index.php?pagina=turmas" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir esta turma?');">
                                <input type="hidden" name="acao" value="excluir_turma">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;" title="Excluir">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- MODAL DE CADASTRO -->
<!-- ========================================== -->
<div id="modalNovaTurma" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Cadastrar Nova Turma</h2>
            <button type="button" onclick="fecharModalTurma()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=turmas" method="POST">
            <input type="hidden" name="acao" value="cadastrar_turma">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome da Turma</label>
                <input type="text" name="nome" placeholder="Ex: 1º Ano A - Ensino Médio" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turno</label>
                    <select name="turno" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Manhã">Manhã</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noite">Noite</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Capacidade Máxima</label>
                    <input type="number" name="capacidade" value="40" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Sala</label>
                <input type="text" name="sala" placeholder="Ex: Sala 102 (Bloco A)" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativa">Ativa</option>
                    <option value="Lotada">Lotada</option>
                    <option value="Inativa">Inativa</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalTurma()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar Turma</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarTurma" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Turma</h2>
            <button type="button" onclick="fecharModalEditTurma()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=turmas" method="POST">
            <input type="hidden" name="acao" value="editar_turma">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome da Turma</label>
                <input type="text" name="nome" id="edit_nome" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turno</label>
                    <select name="turno" id="edit_turno" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Manhã">Manhã</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noite">Noite</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Capacidade Máxima</label>
                    <input type="number" name="capacidade" id="edit_capacidade" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Sala</label>
                <input type="text" name="sala" id="edit_sala" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" id="edit_status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativa">Ativa</option>
                    <option value="Lotada">Lotada</option>
                    <option value="Inativa">Inativa</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditTurma()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalViewTurma" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Detalhes da Turma</h2>
            <button type="button" onclick="fecharModalViewTurma()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Nome da Turma</p>
            <p id="view_nome" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="display: flex; gap: 24px; margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Turno</p>
                <p id="view_turno" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Ocupação</p>
                <p id="view_ocupacao" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Sala</p>
            <p id="view_sala" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="margin-bottom: 24px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Status</p>
            <p id="view_status" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalViewTurma()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS -->
<!-- ========================================== -->
<script>
    // Cadastrar
    function abrirModalTurma() { document.getElementById('modalNovaTurma').style.display = 'flex'; }
    function fecharModalTurma() { document.getElementById('modalNovaTurma').style.display = 'none'; }

    // Editar
    function abrirModalEditTurma(id, nome, turno, sala, capacidade, status) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_turno').value = turno;
        document.getElementById('edit_sala').value = sala;
        document.getElementById('edit_capacidade').value = capacidade;
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditarTurma').style.display = 'flex';
    }
    function fecharModalEditTurma() { document.getElementById('modalEditarTurma').style.display = 'none'; }

    // Visualizar
    function abrirModalViewTurma(nome, turno, sala, matriculados, capacidade, status) {
        document.getElementById('view_nome').textContent = nome;
        document.getElementById('view_turno').textContent = turno;
        document.getElementById('view_sala').textContent = sala;
        document.getElementById('view_ocupacao').textContent = matriculados + " / " + capacidade + " alunos";
        document.getElementById('view_status').textContent = status;
        document.getElementById('modalViewTurma').style.display = 'flex';
    }
    function fecharModalViewTurma() { document.getElementById('modalViewTurma').style.display = 'none'; }
</script>