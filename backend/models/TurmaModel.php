<?php
class TurmaModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
    }

    public function buscarTodas() {
        $query = "SELECT * FROM turmas ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome, $turno, $sala, $capacidade, $status) {
        $query = "INSERT INTO turmas (nome, turno, sala, capacidade, status) VALUES (:nome, :turno, :sala, :capacidade, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':sala', $sala);
        $stmt->bindParam(':capacidade', $capacidade);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function atualizar($id, $nome, $turno, $sala, $capacidade, $status) {
        $query = "UPDATE turmas SET nome = :nome, turno = :turno, sala = :sala, capacidade = :capacidade, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':turno', $turno);
        $stmt->bindParam(':sala', $sala);
        $stmt->bindParam(':capacidade', $capacidade);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        // Remove a turma (os alunos vinculados a ela ficarão com turma_id = NULL devido à restrição do banco)
        $query = "DELETE FROM turmas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>