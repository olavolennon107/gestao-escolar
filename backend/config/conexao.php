<?php
// backend/config/conexao.php

$host = 'localhost';
$dbname = 'sistema_escolar'; // Altere se o nome da sua base de dados for diferente
$user = 'root';              // Utilizador padrão do XAMPP/WAMP
$pass = '';                  // Palavra-passe padrão do XAMPP (geralmente vazia)

try {
    // Cria a ligação utilizando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Configura o PDO para lançar exceções em caso de erros (facilita a depuração)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configura o PDO para retornar os dados como arrays associativos por padrão
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de falha, exibe o erro e interrompe a execução
    die("Erro de ligação à base de dados: " . $e->getMessage());
}
?>