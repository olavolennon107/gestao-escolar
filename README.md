# 🏫 SGES - Sistema de Gestão Escolar (Escola Brasil)

Um sistema web completo, responsivo e dinâmico para a gestão de instituições de ensino. Desenvolvido com **PHP, MySQL e JavaScript**, o projeto segue uma arquitetura baseada no padrão **MVC (Model-View-Controller)**, garantindo um código limpo, seguro e fácil de manter.

---

## 🚀 Funcionalidades

O sistema conta com um painel administrativo (Dashboard) que oferece as seguintes funcionalidades através de janelas modais (sem necessidade de recarregar a página inteira):

*   **🔒 Autenticação Segura:** Sistema de Login e Logout utilizando sessões PHP.
*   **📊 Dashboard Dinâmico (Visão Geral):** Contagem em tempo real de alunos, turmas, professores e eventos agendados.
*   **👨‍🎓 Gestão de Alunos (CRUD):** Matrícula, edição, visualização detalhada e exclusão de estudantes.
*   **🏫 Gestão de Turmas (CRUD):** Controle de salas, turnos e acompanhamento da capacidade de alunos por turma.
*   **👨‍🏫 Gestão de Professores (CRUD):** Registo de docentes, cargas horárias, turnos e status (Ativo/Em Licença).
*   **📚 Gestão de Disciplinas (CRUD):** Controle da matriz curricular, áreas de estudo e carga horária anual.
*   **📅 Calendário Escolar (CRUD):** Agendamento de eventos, avaliações institucionais e feriados.
*   **📄 Gestão de Relatórios (CRUD):** Emissão e armazenamento de relatórios administrativos, financeiros e académicos.
*   **📱 Design 100% Responsivo:** Interface adaptada para computadores, tablets e telemóveis, garantindo uma excelente usabilidade em qualquer dispositivo.

---

## 🛠️ Tecnologias Utilizadas

*   **Frontend:** HTML5, CSS3, JavaScript (Vanilla - sem bibliotecas externas).
*   **Backend:** PHP (Orientado a Objetos).
*   **Base de Dados:** MySQL (com abstração PDO para proteção contra SQL Injection).
*   **Servidor Local:** XAMPP (Apache + MariaDB/MySQL).
*   **UI/UX:** Ícones via *Google Material Symbols* e tipografia *Inter*.

---

## 📁 Estrutura do Projeto

O sistema está organizado dividindo estritamente o código visual (Frontend) da lógica de negócios e base de dados (Backend).

```text
/
├── backend/
│   ├── config/
│   │   └── conexao.php          # Conexão com a base de dados via PDO
│   ├── controllers/             # Controladores (Fazem a ponte entre Tela e Banco)
│   │   ├── aluno.php
│   │   ├── turma.php
│   │   ├── professor.php
│   │   ├── disciplina.php
│   │   ├── calendario.php
│   │   └── relatorio.php
│   └── models/                  # Modelos (Consultas diretas ao MySQL: SELECT, INSERT, etc.)
│       ├── AlunoModel.php
│       ├── TurmaModel.php
│       ├── ProfessorModel.php
│       ├── DisciplinaModel.php
│       ├── CalendarioModel.php
│       └── RelatorioModel.php
│
├── frontend/
│   ├── css/
│   │   └── estilo.css           # Folha de estilos principal e responsividade
│   ├── js/
│   │   └── main.js              # Scripts globais
│   ├── includes/
│   │   └── sidebar.php          # Menu lateral de navegação
│   ├── pages/                   # Telas internas (Carregadas dinamicamente)
│   │   ├── visao-geral.php
│   │   ├── alunos.php
│   │   ├── turmas.php
│   │   ├── professores.php
│   │   ├── disciplinas.php
│   │   ├── calendario.php
│   │   └── relatorios.php
│   ├── index.php                # Roteador principal do Dashboard (Protegido)
│   ├── login.php                # Tela de Autenticação
│   └── logout.php               # Destrói a sessão e sai do sistema
│
└── base_dados.sql               # Script para criação automática das tabelas e dados

⚙️ Como Instalar e Executar (Localmente)
Pré-requisitos
Ter o XAMPP (ou WAMP/MAMP) instalado no seu computador.

1. Preparar os ficheiros
Faça o download deste repositório/pasta.

Mova a pasta do projeto para dentro do diretório htdocs do seu XAMPP (Ex: C:\xampp\htdocs\sistema-escolar).

2. Configurar a Base de Dados
Abra o Painel de Controlo do XAMPP e inicie o Apache e o MySQL.

No navegador, aceda a: http://localhost/phpmyadmin/

Crie uma nova base de dados chamada exatamente sistema_escolar.

Selecione a base de dados recém-criada, vá ao separador Importar, escolha o ficheiro base_dados.sql (que está na raiz do projeto) e clique em Executar.

3. Aceder ao Sistema
Abra o seu navegador e aceda ao endereço:
👉 http://localhost/sistema-escolar/frontend/login.php (Adapte o nome da pasta consoante o que escolheu no passo 1).

🔐 Credenciais de Acesso (Login)
Para entrar no painel administrativo, utilize as seguintes credenciais configuradas por padrão:

E-mail: admin@escolabrasil.edu.br

Senha: 123Brasil@

✒️ Autor
Desenvolvido por Olavo Lennon Clemente de Souza

Estudante de Análise e Desenvolvimento de Sistemas (ADS).