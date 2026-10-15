# 🎬 CineTrack

Sistema web para gerenciar catálogos pessoais de filmes e séries: os usuários organizam, avaliam e compartilham opiniões sobre os títulos que assistiram ou querem assistir, com estatísticas personalizadas e uma área administrativa completa.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

## 🚀 Funcionalidades

- 🌐 **Área pública:** página inicial com estatísticas e ranking, catálogo com busca em tempo real, filtros e paginação, e página de detalhes de cada título
- 🔐 **Autenticação:** login e cadastro com validação no front-end e no back-end, opção "Lembrar de mim" e logout seguro
- 👤 **Área do usuário:** dashboard com métricas pessoais, catálogo próprio (assistido / quero assistir) e avaliações com nota de 0 a 10 e comentário
- 🛠️ **Área administrativa:** gerenciamento de filmes, séries e usuários, com upload de pôster e controle de permissões
- 🎨 **Interface responsiva** com Bootstrap 5, animações suaves e feedback visual para todas as ações

<details>
<summary><strong>📄 Ver todas as páginas e funcionalidades</strong></summary>

### 🌐 Área Pública

**Página Inicial (`index.php`)**
- Hero section animado e call-to-action para cadastro
- Estatísticas gerais (total de títulos, usuários e avaliações)
- Títulos recém-adicionados e ranking dos mais bem avaliados

**Catálogo Público (`catalogo.php`)**
- Listagem completa de filmes e séries
- Busca em tempo real por título, diretor ou gênero
- Filtros por tipo (filme/série) e gênero, com paginação
- Cards com pôster, informações básicas e avaliações

**Detalhes do Título (`detalhes.php`)**
- Sinopse, diretor, ano, duração, gênero e pôster
- Média de avaliações e comentários da comunidade
- Opção de adicionar ao catálogo pessoal (usuários logados)

### 🔐 Autenticação

**Login e Cadastro (`login.php`)**
- Login por e-mail e senha, cadastro com validação de dados
- Opção "Lembrar de mim" e alternância de visibilidade da senha
- Design split-screen para login e cadastro

**Logout (`logout.php`)**
- Encerramento seguro de sessão e limpeza de cookies

### 👤 Área do Usuário

**Dashboard (`dashboard.php`)**
- Métricas: assistidos, quero assistir e média de notas
- Últimos títulos adicionados e avaliações recentes
- Gêneros favoritos com gráfico de distribuição e barra de progresso

**Meu Catálogo (`meu-catalogo.php`)**
- Filtro por status ("Assistido" ou "Quero Assistir")
- Ver detalhes, alterar status ou remover do catálogo
- Estatísticas pessoais em grid responsivo

**Adicionar ao Catálogo (`adicionar-catalogo.php`)**
- Seleção de status, nota (0 a 10) e comentário
- Preview do título antes de adicionar e bloqueio de duplicatas

**Avaliação de Títulos**
- Notas de 0 a 10 (com decimais) e comentários
- Atualização de avaliações existentes, com histórico de modificações

### 🛠️ Área Administrativa

**Dashboard Admin (`admin/index.php`)**
- Estatísticas do sistema, gráficos de uso e logs de atividades recentes

**Filmes e Séries (`admin/filmes.php`, `adicionar_filme.php`, `editar_filme.php`)**
- Listagem com busca e filtros avançados
- Cadastro completo: título, tipo, ano, gênero, diretor, sinopse, duração e upload de pôster com preview
- Edição e exclusão de títulos

**Usuários (`admin/usuarios.php`)**
- Listagem com perfis e estatísticas
- Ativar/desativar contas e controlar permissões (usuário/admin)

</details>

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+** — back-end, com PDO e arquitetura MVC simplificada
- **MySQL / MariaDB** — banco de dados, com views, índices e foreign keys
- **Bootstrap 5.3** e **Bootstrap Icons** — interface e componentes
- **JavaScript (ES6+)** — interações no front-end
- **HTML5** + **CSS3** — com variáveis CSS customizadas e design mobile-first
- **Git/GitHub**, **XAMPP/WAMP** e **VS Code** — ferramentas de desenvolvimento

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior (ou MariaDB)
- Servidor Apache (XAMPP ou WAMP)
- Navegador moderno

## 🔧 Instalação

### 1. Clone o repositório

Dentro da pasta `htdocs` (XAMPP) ou `www` (WAMP):

```bash
git clone https://github.com/IgorMarinho-dev/Projeto_Cinetrack.git
cd Projeto_Cinetrack
```

### 2. Configure o banco de dados

- Abra o phpMyAdmin e importe o arquivo `database/cinetrack.sql`
- Ajuste as credenciais de acesso em `config/database.php`

### 3. Inicie os serviços

Ligue o **Apache** e o **MySQL** pelo painel do XAMPP/WAMP.

## 🌐 Acesso

- **Local:** http://localhost/Projeto_Cinetrack

### 🔑 Usuário administrador (ambiente local)

| E-mail | Senha |
|---|---|
| `admin@cinetrack.com` | `admin123` |

> ⚠️ Credenciais de demonstração, apenas para desenvolvimento local. Altere-as antes de publicar o sistema em qualquer ambiente real.

## 📁 Estrutura do Projeto

```
Projeto_Cinetrack/
├── config/                 # Configurações do sistema
│   ├── database.php        # Conexão com o banco
│   └── config.php          # Configurações gerais
├── includes/               # Arquivos reutilizáveis
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── functions.php
├── auth/                   # Autenticação
│   ├── login_process.php
│   └── register_process.php
├── actions/                # Ações do usuário
│   ├── adicionar_filme.php
│   ├── avaliar_filme.php
│   ├── remover_filme.php
│   └── atualizar_status.php
├── admin/                  # Área administrativa
│   ├── index.php
│   ├── filmes.php
│   ├── adicionar_filme.php
│   ├── editar_filme.php
│   └── usuarios.php
├── assets/                 # Recursos estáticos
│   ├── css/style.css
│   ├── js/main.js
│   └── images/posters/
├── database/
│   └── cinetrack.sql       # Script SQL
├── index.php               # Página inicial
├── login.php               # Login e cadastro
├── logout.php
├── catalogo.php            # Catálogo público
├── detalhes.php            # Detalhes do título
├── dashboard.php           # Dashboard do usuário
├── meu-catalogo.php        # Catálogo pessoal
└── adicionar-catalogo.php
```

## 🗄️ Banco de Dados

| Tabela | Função |
|---|---|
| `usuarios` | Dados dos usuários (nome, e-mail, senha, tipo) e diferenciação entre usuário comum e administrador |
| `filmes_series` | Catálogo geral: título, tipo, ano, gênero, diretor, sinopse, pôster e duração |
| `catalogo_usuario` | Relação entre usuários e títulos: status (assistido / quero assistir), nota, comentário e datas |

**Views de estatísticas:** `vw_estatisticas_usuario` (métricas por usuário) e `vw_filmes_top_rated` (ranking de avaliações).

## 🔒 Segurança

- Senhas armazenadas com hash
- Proteção contra SQL Injection com PDO Prepared Statements
- Sanitização de entradas e validação de formulários no front-end e no back-end
- Sessões seguras e controle de acesso por papéis (usuário/admin)

## 📝 Sobre o Projeto

Desenvolvido como trabalho final da disciplina de Programação Web, implementando front-end, back-end e banco de dados. O código segue uma organização modular e o sistema foi pensado para expansão futura (API, PWA, etc.). Projeto com fins acadêmicos.

## 👨‍💻 Autor

Igor Marinho dos Santos Silva — [GitHub](https://github.com/IgorMarinho-dev) · [LinkedIn](https://www.linkedin.com/in/igor-marinho-842300373/) · [E-mail](mailto:contato.igormarinho083@gmail.com)

---

⭐ Se este projeto te ajudou, deixe uma estrela!