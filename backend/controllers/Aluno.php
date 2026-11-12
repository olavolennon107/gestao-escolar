<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/AlunoModel.php';

class AlunoController {
    private $model;

    public function __construct($conexao) {
        $this->model = new AlunoModel($conexao);
    }

    public function listarAlunos() {
        return $this->model->buscarTodos();
    }

    public function cadastrarAluno($nome, $turma_id, $status = 'Ativo') {
        return $this->model->salvar($nome, $turma_id, $status);
    }

    // NOVA FUNÇÃO: Encaminha os dados de edição
    public function editarAluno($matricula, $nome, $turma_id, $status) {
        return $this->model->atualizar($matricula, $nome, $turma_id, $status);
    }

    // NOVA FUNÇÃO: Encaminha a matrícula para exclusão
    public function excluirAluno($matricula) {
        return $this->model->excluir($matricula);
    }
}
?>