<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/ProfessorModel.php';

class ProfessorController {
    private $model;

    public function __construct($conexao) {
        $this->model = new ProfessorModel($conexao);
    }

    public function listarProfessores() {
        return $this->model->buscarTodos();
    }

    public function cadastrarProfessor($nome, $carga_horaria, $turnos, $status = 'Ativo') {
        return $this->model->salvar($nome, $carga_horaria, $turnos, $status);
    }

    public function editarProfessor($id, $nome, $carga_horaria, $turnos, $status) {
        return $this->model->atualizar($id, $nome, $carga_horaria, $turnos, $status);
    }

    public function excluirProfessor($id) {
        return $this->model->excluir($id);
    }
}
?>