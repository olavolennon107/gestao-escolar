<?php
class AlunoModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
    }

    public function buscarTodos() {
        // Trazemos também o turma_id para conseguirmos preencher o formulário de edição
        $query = "SELECT a.matricula, a.nome AS aluno_nome, a.turma_id, t.nome AS turma_nome, a.status 
                  FROM alunos a
                  LEFT JOIN turmas t ON a.turma_id = t.id
                  ORDER BY a.nome ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome, $turma_id, $status) {
        $query = "INSERT INTO alunos (nome, turma_id, status) VALUES (:nome, :turma_id, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    // NOVA FUNÇÃO: Editar
    public function atualizar($matricula, $nome, $turma_id, $status) {
        $query = "UPDATE alunos SET nome = :nome, turma_id = :turma_id, status = :status WHERE matricula = :matricula";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':matricula', $matricula);
        return $stmt->execute();
    }

    // NOVA FUNÇÃO: Excluir
    public function excluir($matricula) {
        $query = "DELETE FROM alunos WHERE matricula = :matricula";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':matricula', $matricula);
        return $stmt->execute();
    }
}
?>