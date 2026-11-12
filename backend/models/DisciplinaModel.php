<?php
class DisciplinaModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
    }

    public function buscarTodas() {
        $query = "SELECT * FROM disciplinas ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($codigo, $nome, $area_estudo, $carga_horaria_anual, $status) {
        $query = "INSERT INTO disciplinas (codigo, nome, area_estudo, carga_horaria_anual, status) 
                  VALUES (:codigo, :nome, :area_estudo, :carga_horaria_anual, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':area_estudo', $area_estudo);
        $stmt->bindParam(':carga_horaria_anual', $carga_horaria_anual);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function atualizar($id, $codigo, $nome, $area_estudo, $carga_horaria_anual, $status) {
        $query = "UPDATE disciplinas SET codigo = :codigo, nome = :nome, area_estudo = :area_estudo, 
                  carga_horaria_anual = :carga_horaria_anual, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':area_estudo', $area_estudo);
        $stmt->bindParam(':carga_horaria_anual', $carga_horaria_anual);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM disciplinas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>