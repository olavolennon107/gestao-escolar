<?php
class ProfessorModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
    }

    public function buscarTodos() {
        $query = "SELECT * FROM professores ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome, $carga_horaria, $turnos, $status) {
        $query = "INSERT INTO professores (nome, carga_horaria, turnos, status) VALUES (:nome, :carga_horaria, :turnos, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':carga_horaria', $carga_horaria);
        $stmt->bindParam(':turnos', $turnos);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function atualizar($id, $nome, $carga_horaria, $turnos, $status) {
        $query = "UPDATE professores SET nome = :nome, carga_horaria = :carga_horaria, turnos = :turnos, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':carga_horaria', $carga_horaria);
        $stmt->bindParam(':turnos', $turnos);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM professores WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>