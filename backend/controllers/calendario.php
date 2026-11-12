<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../models/CalendarioModel.php';

class CalendarioController {
    private $model;

    public function __construct($conexao) {
        $this->model = new CalendarioModel($conexao);
    }

    public function listarEventos() {
        return $this->model->buscarTodos();
    }

    public function cadastrarEvento($data_evento, $titulo, $tipo, $publico_alvo) {
        return $this->model->salvar($data_evento, $titulo, $tipo, $publico_alvo);
    }

    public function editarEvento($id, $data_evento, $titulo, $tipo, $publico_alvo) {
        return $this->model->atualizar($id, $data_evento, $titulo, $tipo, $publico_alvo);
    }

    public function excluirEvento($id) {
        return $this->model->excluir($id);
    }
}
?>