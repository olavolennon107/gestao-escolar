<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/RelatorioModel.php';

class RelatorioController {
    private $model;

    public function __construct($conexao) {
        $this->model = new RelatorioModel($conexao);
    }

    public function listarRelatorios() {
        return $this->model->buscarTodos();
    }

    public function cadastrarRelatorio($titulo, $tipo, $data_geracao, $descricao, $status = 'Gerado') {
        return $this->model->salvar($titulo, $tipo, $data_geracao, $descricao, $status);
    }

    public function editarRelatorio($id, $titulo, $tipo, $data_geracao, $descricao, $status) {
        return $this->model->atualizar($id, $titulo, $tipo, $data_geracao, $descricao, $status);
    }

    public function excluirRelatorio($id) {
        return $this->model->excluir($id);
    }
}
?>