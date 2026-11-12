<?php
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/disciplina.php';

$disciplinaController = new DisciplinaController($conn);

// PROCESSAR AÇÕES DO BANCO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_disciplina') {
        $disciplinaController->cadastrarDisciplina($_POST['codigo'], $_POST['nome'], $_POST['area_estudo'], $_POST['carga_horaria_anual'], $_POST['status']);
    } 
    elseif ($acao == 'editar_disciplina') {
        $disciplinaController->editarDisciplina($_POST['id'], $_POST['codigo'], $_POST['nome'], $_POST['area_estudo'], $_POST['carga_horaria_anual'], $_POST['status']);
    } 
    elseif ($acao == 'excluir_disciplina') {
        $disciplinaController->excluirDisciplina($_POST['id']);
    }

    // Recarrega a página após a ação
    echo "<script>window.location.href='index.php?pagina=disciplinas';</script>";
    exit();
}

// Buscar a lista de disciplinas do banco de dados
$listaDisciplinas = $disciplinaController->listarDisciplinas();
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Gestão de Disciplinas</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Organize as matrizes curriculares e as áreas de conhecimento.</p>
    </div>
    <button type="button" onclick="abrirModalDisciplina()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Nova Disciplina</button>
</div>

<!-- TABELA DE DISCIPLINAS -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Código</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Disciplina</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Área de Estudo</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Carga Horária (Anual)</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Status</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaDisciplinas)): ?>
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhuma disciplina cadastrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaDisciplinas as $disc): 
                    $id = $disc['id'];
                    $codigo_js = htmlspecialchars(addslashes($disc['codigo']));
                    $nome_js = htmlspecialchars(addslashes($disc['nome']));
                    $area_js = htmlspecialchars(addslashes($disc['area_estudo']));
                    $carga_js = $disc['carga_horaria_anual'];
                    $status_js = $disc['status'];
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($disc['codigo']) ?></td>
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= htmlspecialchars($disc['nome']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($disc['area_estudo']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $disc['carga_horaria_anual'] ?>h</td>
                        <td style="padding: 16px 12px;">
                            <?php if ($disc['status'] == 'Obrigatória'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Obrigatória</span>
                            <?php else: ?>
                                <span style="background: rgba(30, 58, 138, 0.1); color: var(--color-blue-accent); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Opcional</span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            
                            <!-- VISUALIZAR -->
                            <button type="button" onclick="abrirModalViewDisciplina('<?= $codigo_js ?>', '<?= $nome_js ?>', '<?= $area_js ?>', '<?= $carga_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar Detalhes">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>

                            <!-- EDITAR -->
                            <button type="button" onclick="abrirModalEditDisciplina('<?= $id ?>', '<?= $codigo_js ?>', '<?= $nome_js ?>', '<?= $area_js ?>', '<?= $carga_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            
                            <!-- EXCLUIR -->
                            <form action="index.php?pagina=disciplinas" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir esta disciplina?');">
                                <input type="hidden" name="acao" value="excluir_disciplina">
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
<div id="modalNovaDisciplina" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Cadastrar Disciplina</h2>
            <button type="button" onclick="fecharModalDisciplina()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=disciplinas" method="POST">
            <input type="hidden" name="acao" value="cadastrar_disciplina">
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Código</label>
                    <input type="text" name="codigo" placeholder="Ex: MAT-101" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 2;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome da Disciplina</label>
                    <input type="text" name="nome" placeholder="Ex: Matemática Aplicada" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Área de Estudo</label>
                <input type="text" name="area_estudo" placeholder="Ex: Ciências Exatas, Humanas..." required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Carga Horária (Anual)</label>
                    <input type="number" name="carga_horaria_anual" placeholder="Ex: 80" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                    <select name="status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Obrigatória">Obrigatória</option>
                        <option value="Opcional">Opcional</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalDisciplina()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarDisciplina" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Disciplina</h2>
            <button type="button" onclick="fecharModalEditDisciplina()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=disciplinas" method="POST">
            <input type="hidden" name="acao" value="editar_disciplina">
            <input type="hidden" name="id" id="edit_id">
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Código</label>
                    <input type="text" name="codigo" id="edit_codigo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 2;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Nome da Disciplina</label>
                    <input type="text" name="nome" id="edit_nome" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Área de Estudo</label>
                <input type="text" name="area_estudo" id="edit_area" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Carga Horária</label>
                    <input type="number" name="carga_horaria_anual" id="edit_carga" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                    <select name="status" id="edit_status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Obrigatória">Obrigatória</option>
                        <option value="Opcional">Opcional</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditDisciplina()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalViewDisciplina" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Detalhes da Disciplina</h2>
            <button type="button" onclick="fecharModalViewDisciplina()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        
        <div style="display: flex; gap: 24px; margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Código</p>
                <p id="view_codigo" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div style="flex: 1;">
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Nome da Disciplina</p>
                <p id="view_nome" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Área de Estudo</p>
            <p id="view_area" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="display: flex; gap: 24px; margin-bottom: 24px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Carga Horária Anual</p>
                <p id="view_carga" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Status</p>
                <p id="view_status" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalViewDisciplina()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS -->
<!-- ========================================== -->
<script>
    // Cadastrar
    function abrirModalDisciplina() { document.getElementById('modalNovaDisciplina').style.display = 'flex'; }
    function fecharModalDisciplina() { document.getElementById('modalNovaDisciplina').style.display = 'none'; }

    // Editar
    function abrirModalEditDisciplina(id, codigo, nome, area, carga, status) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_codigo').value = codigo;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_area').value = area;
        document.getElementById('edit_carga').value = carga;
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditarDisciplina').style.display = 'flex';
    }
    function fecharModalEditDisciplina() { document.getElementById('modalEditarDisciplina').style.display = 'none'; }

    // Visualizar
    function abrirModalViewDisciplina(codigo, nome, area, carga, status) {
        document.getElementById('view_codigo').textContent = codigo;
        document.getElementById('view_nome').textContent = nome;
        document.getElementById('view_area').textContent = area;
        document.getElementById('view_carga').textContent = carga + 'h';
        document.getElementById('view_status').textContent = status;
        document.getElementById('modalViewDisciplina').style.display = 'flex';
    }
    function fecharModalViewDisciplina() { document.getElementById('modalViewDisciplina').style.display = 'none'; }
</script>