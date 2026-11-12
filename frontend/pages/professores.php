<?php
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/professor.php';

$professorController = new ProfessorController($conn);

// PROCESSAR AÇÕES DO BANCO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_professor') {
        $professorController->cadastrarProfessor($_POST['nome'], $_POST['carga_horaria'], $_POST['turnos'], $_POST['status']);
    } 
    elseif ($acao == 'editar_professor') {
        $professorController->editarProfessor($_POST['id'], $_POST['nome'], $_POST['carga_horaria'], $_POST['turnos'], $_POST['status']);
    } 
    elseif ($acao == 'excluir_professor') {
        $professorController->excluirProfessor($_POST['id']);
    }

    // Recarrega a página após a ação
    echo "<script>window.location.href='index.php?pagina=professores';</script>";
    exit();
}

// Buscar a lista de professores do banco de dados
$listaProfessores = $professorController->listarProfessores();
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Gestão de Professores</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Faça a gestão do corpo docente e das respetivas cargas horárias.</p>
    </div>
    <button type="button" onclick="abrirModalProfessor()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Novo Professor</button>
</div>

<!-- TABELA DE PROFESSORES -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Nome do Professor</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Carga Horária</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Turnos</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Status</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaProfessores)): ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhum professor cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaProfessores as $prof): 
                    $id = $prof['id'];
                    $nome_js = htmlspecialchars(addslashes($prof['nome']));
                    $carga_js = $prof['carga_horaria'];
                    $turnos_js = htmlspecialchars(addslashes($prof['turnos']));
                    $status_js = $prof['status'];
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= htmlspecialchars($prof['nome']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $prof['carga_horaria'] ?>h</td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($prof['turnos']) ?></td>
                        <td style="padding: 16px 12px;">
                            <?php if ($prof['status'] == 'Ativo'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Ativo</span>
                            <?php elseif ($prof['status'] == 'Em Licença'): ?>
                                <span style="background: rgba(212, 175, 55, 0.1); color: #B48E2D; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Em Licença</span>
                            <?php else: ?>
                                <span style="background: rgba(100, 116, 139, 0.1); color: var(--color-text-muted); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= $prof['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            
                            <!-- VISUALIZAR -->
                            <button type="button" onclick="abrirModalViewProf('<?= $nome_js ?>', '<?= $carga_js ?>', '<?= $turnos_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar Detalhes">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>

                            <!-- EDITAR -->
                            <button type="button" onclick="abrirModalEditProf('<?= $id ?>', '<?= $nome_js ?>', '<?= $carga_js ?>', '<?= $turnos_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            
                            <!-- EXCLUIR -->
                            <form action="index.php?pagina=professores" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir este professor? A ação não pode ser desfeita.');">
                                <input type="hidden" name="acao" value="excluir_professor">
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
<div id="modalNovoProfessor" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Cadastrar Professor</h2>
            <button type="button" onclick="fecharModalProfessor()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=professores" method="POST">
            <input type="hidden" name="acao" value="cadastrar_professor">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome Completo</label>
                <input type="text" name="nome" placeholder="Ex: Roberto Almeida" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Carga Horária (h)</label>
                    <input type="number" name="carga_horaria" placeholder="Ex: 40" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 2;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turnos de Atuação</label>
                    <input type="text" name="turnos" placeholder="Ex: Manhã / Tarde" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativo">Ativo</option>
                    <option value="Em Licença">Em Licença</option>
                    <option value="Inativo">Inativo</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalProfessor()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarProfessor" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Professor</h2>
            <button type="button" onclick="fecharModalEditProf()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=professores" method="POST">
            <input type="hidden" name="acao" value="editar_professor">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome Completo</label>
                <input type="text" name="nome" id="edit_nome" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Carga Horária (h)</label>
                    <input type="number" name="carga_horaria" id="edit_carga" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 2;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turnos de Atuação</label>
                    <input type="text" name="turnos" id="edit_turnos" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" id="edit_status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativo">Ativo</option>
                    <option value="Em Licença">Em Licença</option>
                    <option value="Inativo">Inativo</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditProf()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalViewProfessor" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Detalhes do Professor</h2>
            <button type="button" onclick="fecharModalViewProf()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Nome Completo</p>
            <p id="view_nome" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="display: flex; gap: 24px; margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Carga Horária</p>
                <p id="view_carga" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Turnos</p>
                <p id="view_turnos" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>
        <div style="margin-bottom: 24px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Status Atual</p>
            <p id="view_status" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalViewProf()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS -->
<!-- ========================================== -->
<script>
    // Cadastrar
    function abrirModalProfessor() { document.getElementById('modalNovoProfessor').style.display = 'flex'; }
    function fecharModalProfessor() { document.getElementById('modalNovoProfessor').style.display = 'none'; }

    // Editar
    function abrirModalEditProf(id, nome, carga, turnos, status) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_carga').value = carga;
        document.getElementById('edit_turnos').value = turnos;
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditarProfessor').style.display = 'flex';
    }
    function fecharModalEditProf() { document.getElementById('modalEditarProfessor').style.display = 'none'; }

    // Visualizar
    function abrirModalViewProf(nome, carga, turnos, status) {
        document.getElementById('view_nome').textContent = nome;
        document.getElementById('view_carga').textContent = carga + 'h semanais';
        document.getElementById('view_turnos').textContent = turnos;
        document.getElementById('view_status').textContent = status;
        document.getElementById('modalViewProfessor').style.display = 'flex';
    }
    function fecharModalViewProf() { document.getElementById('modalViewProfessor').style.display = 'none'; }
</script>