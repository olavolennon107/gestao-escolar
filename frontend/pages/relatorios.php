<?php
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/relatorio.php';

$relatorioController = new RelatorioController($conn);

// PROCESSAR AÇÕES DO BANCO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_relatorio') {
        $relatorioController->cadastrarRelatorio($_POST['titulo'], $_POST['tipo'], $_POST['data_geracao'], $_POST['descricao'], $_POST['status']);
    } 
    elseif ($acao == 'editar_relatorio') {
        $relatorioController->editarRelatorio($_POST['id'], $_POST['titulo'], $_POST['tipo'], $_POST['data_geracao'], $_POST['descricao'], $_POST['status']);
    } 
    elseif ($acao == 'excluir_relatorio') {
        $relatorioController->excluirRelatorio($_POST['id']);
    }

    echo "<script>window.location.href='index.php?pagina=relatorios';</script>";
    exit();
}

$listaRelatorios = $relatorioController->listarRelatorios();

function formatarDataBr($data) {
    return date('d/m/Y', strtotime($data));
}
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Gestão de Relatórios</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Crie, visualize e arquive os relatórios da instituição.</p>
    </div>
    <button type="button" onclick="abrirModalRelatorio()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Novo Relatório</button>
</div>

<!-- TABELA DE RELATÓRIOS -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Data</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Título</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Categoria</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Status</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaRelatorios)): ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhum relatório emitido.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaRelatorios as $rel): 
                    $id = $rel['id'];
                    $data_js = $rel['data_geracao'];
                    $data_formatada = formatarDataBr($rel['data_geracao']);
                    $titulo_js = htmlspecialchars(addslashes($rel['titulo']));
                    $tipo_js = htmlspecialchars(addslashes($rel['tipo']));
                    $status_js = $rel['status'];
                    // Limpa quebras de linha para o JavaScript não dar erro
                    $desc_js = htmlspecialchars(str_replace(array("\r", "\n"), array('', '\\n'), addslashes($rel['descricao'])));
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= $data_formatada ?></td>
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= htmlspecialchars($rel['titulo']) ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($rel['tipo']) ?></td>
                        <td style="padding: 16px 12px;">
                            <?php if ($rel['status'] == 'Gerado'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Gerado</span>
                            <?php elseif ($rel['status'] == 'Rascunho'): ?>
                                <span style="background: rgba(212, 175, 55, 0.1); color: #B48E2D; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Rascunho</span>
                            <?php else: ?>
                                <span style="background: rgba(100, 116, 139, 0.1); color: var(--color-text-muted); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= $rel['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            <button type="button" onclick="abrirModalViewRelatorio('<?= $titulo_js ?>', '<?= $data_formatada ?>', '<?= $tipo_js ?>', '<?= $status_js ?>', '<?= $desc_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                            <button type="button" onclick="abrirModalEditRelatorio('<?= $id ?>', '<?= $titulo_js ?>', '<?= $tipo_js ?>', '<?= $data_js ?>', '<?= $desc_js ?>', '<?= $status_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <form action="index.php?pagina=relatorios" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir este relatório?');">
                                <input type="hidden" name="acao" value="excluir_relatorio">
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
<div id="modalNovoRelatorio" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 600px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Novo Relatório</h2>
            <button type="button" onclick="fecharModalRelatorio()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=relatorios" method="POST">
            <input type="hidden" name="acao" value="cadastrar_relatorio">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Título do Relatório</label>
                <input type="text" name="titulo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Categoria</label>
                    <select name="tipo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Acadêmico">Acadêmico</option>
                        <option value="Administrativo">Administrativo</option>
                        <option value="Financeiro">Financeiro</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Data</label>
                    <input type="date" name="data_geracao" required value="<?= date('Y-m-d') ?>" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Conteúdo / Descrição</label>
                <textarea name="descricao" rows="5" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; resize: vertical;"></textarea>
            </div>
            
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Gerado">Gerado</option>
                    <option value="Rascunho">Rascunho</option>
                    <option value="Arquivado">Arquivado</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalRelatorio()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar Relatório</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarRelatorio" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 600px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Relatório</h2>
            <button type="button" onclick="fecharModalEditRelatorio()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=relatorios" method="POST">
            <input type="hidden" name="acao" value="editar_relatorio">
            <input type="hidden" name="id" id="edit_id">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Título</label>
                <input type="text" name="titulo" id="edit_titulo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Categoria</label>
                    <select name="tipo" id="edit_tipo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Acadêmico">Acadêmico</option>
                        <option value="Administrativo">Administrativo</option>
                        <option value="Financeiro">Financeiro</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Data</label>
                    <input type="date" name="data_geracao" id="edit_data" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Conteúdo / Descrição</label>
                <textarea name="descricao" id="edit_desc" rows="5" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; resize: vertical;"></textarea>
            </div>
            
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Status</label>
                <select name="status" id="edit_status" style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                    <option value="Gerado">Gerado</option>
                    <option value="Rascunho">Rascunho</option>
                    <option value="Arquivado">Arquivado</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditRelatorio()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalViewRelatorio" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 600px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--color-hover); padding-bottom: 16px;">
            <h2 id="view_titulo" style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;"></h2>
            <button type="button" onclick="fecharModalViewRelatorio()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        
        <div style="display: flex; gap: 24px; margin-bottom: 24px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Data</p>
                <p id="view_data" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Categoria</p>
                <p id="view_tipo" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Status</p>
                <p id="view_status" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>
        
        <div style="margin-bottom: 24px; background: var(--color-bg); padding: 16px; border-radius: 8px; border: 1px solid var(--color-border);">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 8px; font-weight: 500;">Conteúdo</p>
            <p id="view_desc" style="color: var(--color-blue-navy); font-size: 0.95rem; margin: 0; line-height: 1.6; white-space: pre-wrap;"></p>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalViewRelatorio()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar Documento</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS -->
<!-- ========================================== -->
<script>
    // Cadastrar
    function abrirModalRelatorio() { document.getElementById('modalNovoRelatorio').style.display = 'flex'; }
    function fecharModalRelatorio() { document.getElementById('modalNovoRelatorio').style.display = 'none'; }

    // Editar
    function abrirModalEditRelatorio(id, titulo, tipo, data, desc, status) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_titulo').value = titulo;
        document.getElementById('edit_tipo').value = tipo;
        document.getElementById('edit_data').value = data;
        // Restaura quebras de linha
        document.getElementById('edit_desc').value = desc.replace(/\\n/g, '\n');
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditarRelatorio').style.display = 'flex';
    }
    function fecharModalEditRelatorio() { document.getElementById('modalEditarRelatorio').style.display = 'none'; }

    // Visualizar
    function abrirModalViewRelatorio(titulo, data, tipo, status, desc) {
        document.getElementById('view_titulo').textContent = titulo;
        document.getElementById('view_data').textContent = data;
        document.getElementById('view_tipo').textContent = tipo;
        document.getElementById('view_status').textContent = status;
        document.getElementById('view_desc').textContent = desc.replace(/\\n/g, '\n');
        document.getElementById('modalViewRelatorio').style.display = 'flex';
    }
    function fecharModalViewRelatorio() { document.getElementById('modalViewRelatorio').style.display = 'none'; }
</script>