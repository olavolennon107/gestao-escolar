<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/DisciplinaModel.php';

class DisciplinaController {
    private $model;

    public function __construct($conexao) {
        $this->model = new DisciplinaModel($conexao);
    }

    public function listarDisciplinas() {
        return $this->model->buscarTodas();
    }

    public function cadastrarDisciplina($codigo, $nome, $area_estudo, $carga_horaria_anual, $status = 'Obrigatória') {
        return $this->model->salvar($codigo, $nome, $area_estudo, $carga_horaria_anual, $status);
    }

    public function editarDisciplina($id, $codigo, $nome, $area_estudo, $carga_horaria_anual, $status) {
        return $this->model->atualizar($id, $codigo, $nome, $area_estudo, $carga_horaria_anual, $status);
    }

    public function excluirDisciplina($id) {
        return $this->model->excluir($id);
    }
}
?>