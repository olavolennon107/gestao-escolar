<?php
require_once '../backend/config/conexao.php';
require_once '../backend/controllers/calendario.php';

$calendarioController = new CalendarioController($conn);

// PROCESSAR AÇÕES DO BANCO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao == 'cadastrar_evento') {
        $calendarioController->cadastrarEvento($_POST['data_evento'], $_POST['titulo'], $_POST['tipo'], $_POST['publico_alvo']);
    } 
    elseif ($acao == 'editar_evento') {
        $calendarioController->editarEvento($_POST['id'], $_POST['data_evento'], $_POST['titulo'], $_POST['tipo'], $_POST['publico_alvo']);
    } 
    elseif ($acao == 'excluir_evento') {
        $calendarioController->excluirEvento($_POST['id']);
    }

    // Recarrega a página após a ação
    echo "<script>window.location.href='index.php?pagina=calendario';</script>";
    exit();
}

// Buscar a lista de eventos
$listaEventos = $calendarioController->listarEventos();

// Função auxiliar para formatar a data (Ex: 2026-03-15 para 15/03/2026)
function formatarDataBr($data) {
    return date('d/m/Y', strtotime($data));
}
?>

<!-- CABEÇALHO -->
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 1.8rem; color: var(--color-blue-navy); margin-bottom: 4px;">Calendário Escolar</h1>
        <p style="color: var(--color-text-muted); font-size: 0.95rem;">Consulte e agende eventos, avaliações e feriados do ano letivo.</p>
    </div>
    <button type="button" onclick="abrirModalEvento()" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">+ Novo Evento</button>
</div>

<!-- TABELA DE EVENTOS -->
<div class="table-responsive" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid var(--color-border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--color-border);">
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Data</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Evento</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Tipo</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600;">Público-Alvo</th>
                <th style="padding: 16px 12px; color: var(--color-text-muted); font-weight: 600; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaEventos)): ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: var(--color-text-muted);">Nenhum evento agendado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaEventos as $evento): 
                    $id = $evento['id'];
                    $data_js = $evento['data_evento'];
                    $data_formatada = formatarDataBr($evento['data_evento']);
                    $titulo_js = htmlspecialchars(addslashes($evento['titulo']));
                    $tipo_js = htmlspecialchars(addslashes($evento['tipo']));
                    $publico_js = htmlspecialchars(addslashes($evento['publico_alvo']));
                ?>
                    <tr style="border-bottom: 1px solid var(--color-hover);">
                        <td style="padding: 16px 12px; font-weight: 500; color: var(--color-blue-navy);"><?= $data_formatada ?></td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($evento['titulo']) ?></td>
                        <td style="padding: 16px 12px;">
                            <?php if ($evento['tipo'] == 'Avaliação'): ?>
                                <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Avaliação</span>
                            <?php elseif ($evento['tipo'] == 'Feriado'): ?>
                                <span style="background: rgba(11, 107, 64, 0.1); color: var(--color-green-light); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Feriado</span>
                            <?php elseif ($evento['tipo'] == 'Institucional'): ?>
                                <span style="background: rgba(30, 58, 138, 0.1); color: var(--color-blue-accent); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Institucional</span>
                            <?php else: ?>
                                <span style="background: rgba(100, 116, 139, 0.1); color: var(--color-text-muted); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($evento['tipo']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px 12px; color: var(--color-text-muted);"><?= htmlspecialchars($evento['publico_alvo']) ?></td>
                        
                        <!-- BOTÕES DE AÇÃO -->
                        <td style="padding: 16px 12px; text-align: center; display: flex; justify-content: center; gap: 12px;">
                            <!-- VISUALIZAR -->
                            <button type="button" onclick="abrirModalViewEvento('<?= $data_formatada ?>', '<?= $titulo_js ?>', '<?= $tipo_js ?>', '<?= $publico_js ?>')" style="background: none; border: none; color: #64748b; cursor: pointer;" title="Visualizar Detalhes">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>

                            <!-- EDITAR -->
                            <button type="button" onclick="abrirModalEditEvento('<?= $id ?>', '<?= $data_js ?>', '<?= $titulo_js ?>', '<?= $tipo_js ?>', '<?= $publico_js ?>')" style="background: none; border: none; color: var(--color-blue-accent); cursor: pointer;" title="Editar">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            
                            <!-- EXCLUIR -->
                            <form action="index.php?pagina=calendario" method="POST" style="margin: 0;" onsubmit="return confirm('ATENÇÃO: Deseja realmente excluir este evento do calendário?');">
                                <input type="hidden" name="acao" value="excluir_evento">
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
<div id="modalNovoEvento" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Agendar Novo Evento</h2>
            <button type="button" onclick="fecharModalEvento()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=calendario" method="POST">
            <input type="hidden" name="acao" value="cadastrar_evento">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Data do Evento</label>
                <input type="date" name="data_evento" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Título do Evento</label>
                <input type="text" name="titulo" placeholder="Ex: Início das Provas (1º Bimestre)" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Tipo</label>
                    <select name="tipo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Avaliação">Avaliação</option>
                        <option value="Feriado">Feriado</option>
                        <option value="Institucional">Institucional</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Público-Alvo</label>
                    <input type="text" name="publico_alvo" placeholder="Ex: Todos os Alunos" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEvento()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-green-dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE EDIÇÃO -->
<!-- ========================================== -->
<div id="modalEditarEvento" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Editar Evento</h2>
            <button type="button" onclick="fecharModalEditEvento()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        <form action="index.php?pagina=calendario" method="POST">
            <input type="hidden" name="acao" value="editar_evento">
            <input type="hidden" name="id" id="edit_id">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Data do Evento</label>
                <input type="date" name="data_evento" id="edit_data" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Título do Evento</label>
                <input type="text" name="titulo" id="edit_titulo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
            </div>
            
            <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Tipo</label>
                    <select name="tipo" id="edit_tipo" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                        <option value="Avaliação">Avaliação</option>
                        <option value="Feriado">Feriado</option>
                        <option value="Institucional">Institucional</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; color: var(--color-blue-navy);">Público-Alvo</label>
                    <input type="text" name="publico_alvo" id="edit_publico" required style="width: 100%; padding: 12px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; font-size: 0.95rem;">
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="fecharModalEditEvento()" style="padding: 12px 24px; background: var(--color-hover); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 500;">Cancelar</button>
                <button type="submit" style="padding: 12px 24px; background: var(--color-blue-navy); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE VISUALIZAÇÃO (Somente Leitura) -->
<!-- ========================================== -->
<div id="modalViewEvento" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
    <div style="background: white; padding: 32px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.5rem; color: var(--color-blue-navy); margin: 0;">Detalhes do Evento</h2>
            <button type="button" onclick="fecharModalViewEvento()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--color-text-muted);">&times;</button>
        </div>
        
        <div style="display: flex; gap: 24px; margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <div>
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Data</p>
                <p id="view_data" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
            <div style="flex: 1;">
                <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Tipo de Evento</p>
                <p id="view_tipo" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
            </div>
        </div>
        <div style="margin-bottom: 16px; border-bottom: 1px solid var(--color-hover); padding-bottom: 12px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Título / Descrição</p>
            <p id="view_titulo" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>
        <div style="margin-bottom: 24px;">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 4px;">Público-Alvo</p>
            <p id="view_publico" style="font-weight: 600; color: var(--color-blue-navy); font-size: 1.1rem; margin: 0;"></p>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="button" onclick="fecharModalViewEvento()" style="padding: 12px 24px; background: var(--color-hover); color: var(--color-blue-navy); border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; font-weight: 600;">Fechar</button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS LOCAIS -->
<!-- ========================================== -->
<script>
    // Cadastrar
    function abrirModalEvento() { document.getElementById('modalNovoEvento').style.display = 'flex'; }
    function fecharModalEvento() { document.getElementById('modalNovoEvento').style.display = 'none'; }

    // Editar (A data precisa de estar no formato AAAA-MM-DD para o campo input type="date")
    function abrirModalEditEvento(id, data, titulo, tipo, publico) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_data').value = data;
        document.getElementById('edit_titulo').value = titulo;
        document.getElementById('edit_tipo').value = tipo;
        document.getElementById('edit_publico').value = publico;
        document.getElementById('modalEditarEvento').style.display = 'flex';
    }
    function fecharModalEditEvento() { document.getElementById('modalEditarEvento').style.display = 'none'; }

    // Visualizar
    function abrirModalViewEvento(data, titulo, tipo, publico) {
        document.getElementById('view_data').textContent = data;
        document.getElementById('view_titulo').textContent = titulo;
        document.getElementById('view_tipo').textContent = tipo;
        document.getElementById('view_publico').textContent = publico;
        document.getElementById('modalViewEvento').style.display = 'flex';
    }
    function fecharModalViewEvento() { document.getElementById('modalViewEvento').style.display = 'none'; }
</script>