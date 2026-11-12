-- Criação da Base de Dados
CREATE DATABASE IF NOT EXISTS sistema_escolar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_escolar;

-- ==========================================
-- 1. TABELA TURMAS (Criada primeiro devido às chaves estrangeiras)
-- ==========================================
CREATE TABLE IF NOT EXISTS turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    turno ENUM('Manhã', 'Tarde', 'Noite') NOT NULL,
    sala VARCHAR(50) NOT NULL,
    capacidade INT NOT NULL DEFAULT 40,
    alunos_matriculados INT DEFAULT 0,
    status ENUM('Ativa', 'Inativa', 'Lotada') DEFAULT 'Ativa',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 2. TABELA ALUNOS
-- ==========================================
CREATE TABLE IF NOT EXISTS alunos (
    matricula INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    turma_id INT,
    status ENUM('Ativo', 'Inativo', 'Transferido') DEFAULT 'Ativo',
    data_matricula TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE SET NULL
) AUTO_INCREMENT = 2026001; -- Inicia a matrícula no ano atual

-- ==========================================
-- 3. TABELA PROFESSORES
-- ==========================================
CREATE TABLE IF NOT EXISTS professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    carga_horaria INT NOT NULL,
    turnos VARCHAR(50) NOT NULL,
    status ENUM('Ativo', 'Em Licença', 'Inativo') DEFAULT 'Ativo',
    data_contratacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 4. TABELA DISCIPLINAS
-- ==========================================
CREATE TABLE IF NOT EXISTS disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    area_estudo VARCHAR(100) NOT NULL,
    carga_horaria_anual INT NOT NULL,
    status ENUM('Obrigatória', 'Opcional') DEFAULT 'Obrigatória'
);

-- ==========================================
-- 5. TABELA CALENDÁRIO (Eventos)
-- ==========================================
CREATE TABLE IF NOT EXISTS calendario_eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_evento DATE NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    publico_alvo VARCHAR(100) NOT NULL
);

-- ==========================================
-- INSERÇÃO DE DADOS DE TESTE (MOCK DATA)
-- ==========================================

-- Inserir Turmas
INSERT INTO turmas (nome, turno, sala, capacidade, alunos_matriculados, status) VALUES
('3º Ano A - Ensino Médio', 'Manhã', 'Sala 204 (Bloco B)', 40, 32, 'Ativa'),
('1º Ano B - Ensino Médio', 'Tarde', 'Sala 102 (Bloco A)', 40, 28, 'Ativa'),
('9º Ano C - Ensino Fundamental', 'Manhã', 'Sala 105 (Bloco A)', 40, 40, 'Lotada');

-- Inserir Alunos (O AUTO_INCREMENT fará com que sejam 2026001 e 2026002)
INSERT INTO alunos (nome, turma_id, status) VALUES
('Ana Beatriz Silva', 1, 'Ativo'),
('Carlos Eduardo Souza', 2, 'Ativo');

-- Inserir Professores
INSERT INTO professores (nome, carga_horaria, turnos, status) VALUES
('Roberto Almeida', 40, 'Manhã / Tarde', 'Ativo'),
('Fernanda Costa', 20, 'Manhã', 'Ativo'),
('Sónia Ribeiro', 20, 'Tarde', 'Em Licença');

-- Inserir Disciplinas
INSERT INTO disciplinas (codigo, nome, area_estudo, carga_horaria_anual, status) VALUES
('MAT-101', 'Matemática Aplicada', 'Ciências Exatas', 160, 'Obrigatória'),
('HIS-201', 'História Contemporânea', 'Ciências Humanas', 80, 'Obrigatória'),
('PROG-301', 'Lógica de Programação', 'Tecnologia', 40, 'Opcional');

-- Inserir Calendário
INSERT INTO calendario_eventos (data_evento, titulo, tipo, publico_alvo) VALUES
('2026-03-15', 'Início das Provas (1º Bimestre)', 'Avaliação', 'Todos os Alunos'),
('2026-04-21', 'Feriado Nacional (Tiradentes)', 'Feriado', 'Comunidade Escolar'),
('2026-05-05', 'Reunião de Pais e Encarregados', 'Institucional', 'Pais e Professores');