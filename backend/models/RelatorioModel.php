<?php
class RelatorioModel {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        
        // TRUQUE MÁGICO: Cria a tabela automaticamente no banco se ela não existir
        $this->conn->exec("CREATE TABLE IF NOT EXISTS relatorios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(150) NOT NULL,
            tipo VARCHAR(50) NOT NULL,
            data_geracao DATE NOT NULL,
            descricao TEXT,
            status VARCHAR(50) DEFAULT 'Gerado'
        )");
    }

    public function buscarTodos() {
        $query = "SELECT * FROM relatorios ORDER BY data_geracao DESC, id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($titulo, $tipo, $data_geracao, $descricao, $status) {
        $query = "INSERT INTO relatorios (titulo, tipo, data_geracao, descricao, status) 
                  VALUES (:titulo, :tipo, :data_geracao, :descricao, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':data_geracao', $data_geracao);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function atualizar($id, $titulo, $tipo, $data_geracao, $descricao, $status) {
        $query = "UPDATE relatorios SET titulo = :titulo, tipo = :tipo, data_geracao = :data_geracao, 
                  descricao = :descricao, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':data_geracao', $data_geracao);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM relatorios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>