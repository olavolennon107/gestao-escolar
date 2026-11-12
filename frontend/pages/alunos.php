<?php
// backend/config/conexao.php e controllers
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/aluno.php';

$alunoController = new AlunoController($conn);

// PROCESSAR AÇÕES DO BANCO (Cadastrar, Editar, Excluir)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_aluno') {
        $nome = $_POST['nome'];
        $turma_id = empty($_POST['turma_id']) ? null : $_POST['turma_id'];
        $status = $_POST['status'];
        $alunoController->cadastrarAluno($nome, $turma_id, $status);
    } 
    elseif ($acao == 'editar_aluno') {
        $matricula = $_POST['matricula'];
        $nome = $_POST['nome'];
        $turma_id = empty($_POST['turma_id']) ? null : $_POST['turma_id'];
        $status = $_POST['status'];
        $alunoController->editarAluno($matricula, $nome, $turma_id, $status);
    } 
    elseif ($acao == 'excluir_aluno') {
        $matricula = $_POST['matricula'];
        $alunoController->excluirAluno($matricula);
    }

    // Redireciona para limpar o formulário
    echo "<script>window.location.href='index.php?pagina=alunos';</script>";
    exit();
}

$stmtTurmas = $conn->query("SELECT id, nome FROM turmas WHERE status = 'Ativa' ORDER BY nome ASC");
$listaTurmas = $stmtTurmas->fetchAll(PDO::FETCH_ASSOC);
$listaAlunos = $alunoController->listarAlunos();
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Gestão de Alunos</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Gerencie as matrículas e os dados dos estudantes.</p>
    </div>
    <button type="button" onclick="abrirModalAluno()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Novo Aluno</button>
</div>

<!-- TABELA -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Matrícula</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Nome do Aluno</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Turma</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Status</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaAlunos)): ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhum aluno cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaAlunos as $aluno): 
                    // Tratamento seguro de strings para o JavaScript não quebrar
                    $mat = $aluno['matricula'];
                    $nome_js = htmlspecialchars(addslashes($aluno['aluno_nome']));
                    $turma_id_js = $aluno['turma_id'] ? $aluno['turma_id'] : '';
                    $turma_nome_js = htmlspecialchars(addslashes($aluno['turma_nome'] ?? 'Sem Turma'));
                    $status_js = $aluno['status'];
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $mat ?></td>
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= htmlspecialchars($aluno['aluno_nome']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($aluno['turma_nome'] ?? 'Sem Turma') ?></td>
                        <td style="padding: 16px 12px;">
                            <?php if ($aluno['status'] == 'Ativo'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Ativo</span>
                            <?php elseif ($aluno['status'] == 'Transferido'): ?>
                                <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Transferido</span>
                            <?php else: ?>
                                <span style="background: rgba(100, 116, 139, 0.1); color: var(--color-text-muted); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= $aluno['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            
                            <!-- 1. VISUALIZAR -->
                            <button type="button" onclick="abrirModalVisualizar('<?= $mat ?>', '<?= $nome_js ?>', '<?= $turma_nome_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar Detalhes">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>

                            <!-- 2. EDITAR -->
                            <button type="button" onclick="abrirModalEditar('<?= $mat ?>', '<?= $nome_js ?>', '<?= $turma_id_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            
                            <!-- 3. EXCLUIR (Com confirmação direta no botão, sem depender de arquivos externos) -->
                            <form action="index.php?pagina=alunos" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja excluir este aluno? Esta ação NÃO poderá ser desfeita.');">
                                <input type="hidden" name="acao" value="excluir_aluno">
                                <input type="hidden" name="matricula" value="<?= $mat ?>">
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
<div id="modalNovoAluno" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Cadastrar Novo Aluno</h2>
            <button type="button" onclick="fecharModalAluno()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=alunos" method="POST">
            <input type="hidden" name="acao" value="cadastrar_aluno">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome Completo</label>
                <input type="text" name="nome" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turma</label>
                <select name="turma_id" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="">Nenhuma / Sem Turma</option>
                    <?php foreach ($listaTurmas as $turma): ?>
                        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativo">Ativo</option>
                    <option value="Inativo">Inativo</option>
                    <option value="Transferido">Transferido</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalAluno()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarAluno" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Aluno</h2>
            <button type="button" onclick="fecharModalEditar()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=alunos" method="POST">
            <input type="hidden" name="acao" value="editar_aluno">
            <input type="hidden" name="matricula" id="edit_matricula">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome Completo</label>
                <input type="text" name="nome" id="edit_nome" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Turma</label>
                <select name="turma_id" id="edit_turma" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="">Nenhuma / Sem Turma</option>
                    <?php foreach ($listaTurmas as $turma): ?>
                        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" id="edit_status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Ativo">Ativo</option>
                    <option value="Inativo">Inativo</option>
                    <option value="Transferido">Transferido</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditar()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalVisualizarAluno" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Detalhes do Aluno</h2>
            <button type="button" onclick="fecharModalVisualizar()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Matrícula</p>
            <p id="view_matricula" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Nome Completo</p>
            <p id="view_nome" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Turma Atribuída</p>
            <p id="view_turma" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="margin-bottom: 24px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Status Atual</p>
            <p id="view_status" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalVisualizar()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS (Garante que tudo funciona) -->
<!-- ========================================== -->
<script>
    // Funções do Modal Cadastrar
    function abrirModalAluno() { document.getElementById('modalNovoAluno').style.display = 'flex'; }
    function fecharModalAluno() { document.getElementById('modalNovoAluno').style.display = 'none'; }

    // Funções do Modal Editar
    function abrirModalEditar(matricula, nome, turma_id, status) {
        document.getElementById('edit_matricula').value = matricula;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_turma').value = turma_id;
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditarAluno').style.display = 'flex';
    }
    function fecharModalEditar() { document.getElementById('modalEditarAluno').style.display = 'none'; }

    // Funções do Modal Visualizar
    function abrirModalVisualizar(matricula, nome, turma_nome, status) {
        document.getElementById('view_matricula').textContent = matricula;
        document.getElementById('view_nome').textContent = nome;
        document.getElementById('view_turma').textContent = turma_nome;
        document.getElementById('view_status').textContent = status;
        document.getElementById('modalVisualizarAluno').style.display = 'flex';
    }
    function fecharModalVisualizar() { document.getElementById('modalVisualizarAluno').style.display = 'none'; }
</script>