<?php
class CalendarioModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
    }

    public function buscarTodos() {
        // Traz os eventos ordenados pela data mais próxima primeiro
        $query = "SELECT * FROM calendario_eventos ORDER BY data_evento ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($data_evento, $titulo, $tipo, $publico_alvo) {
        $query = "INSERT INTO calendario_eventos (data_evento, titulo, tipo, publico_alvo) 
                  VALUES (:data_evento, :titulo, :tipo, :publico_alvo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':data_evento', $data_evento);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':publico_alvo', $publico_alvo);
        return $stmt->execute();
    }

    public function atualizar($id, $data_evento, $titulo, $tipo, $publico_alvo) {
        $query = "UPDATE calendario_eventos SET data_evento = :data_evento, titulo = :titulo, 
                  tipo = :tipo, publico_alvo = :publico_alvo WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':data_evento', $data_evento);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':publico_alvo', $publico_alvo);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM calendario_eventos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>