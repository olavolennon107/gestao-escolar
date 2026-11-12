<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/TurmaModel.php';

class TurmaController {
    private $model;

    public function __construct($conexao) {
        $this->model = new TurmaModel($conexao);
    }

    public function listarTurmas() {
        return $this->model->buscarTodas();
    }

    public function cadastrarTurma($nome, $turno, $sala, $capacidade, $status = 'Ativa') {
        return $this->model->salvar($nome, $turno, $sala, $capacidade, $status);
    }

    public function editarTurma($id, $nome, $turno, $sala, $capacidade, $status) {
        return $this->model->atualizar($id, $nome, $turno, $sala, $capacidade, $status);
    }

    public function excluirTurma($id) {
        return $this->model->excluir($id);
    }
}
?>