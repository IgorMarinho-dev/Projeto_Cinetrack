# 🎬 CineTrack - Sistema de Catálogo de Filmes e Séries

## 📖 Descrição do Projeto

**CineTrack** é um sistema web desenvolvido para gerenciamento de catálogos pessoais de filmes e séries. A aplicação permite que usuários organizem, avaliem e compartilhem suas opiniões sobre títulos assistidos ou que desejam assistir, criando uma experiência personalizada de acompanhamento cinematográfico.

O sistema oferece uma interface intuitiva e moderna, permitindo que cinéfilos e entusiastas de séries mantenham um registro completo de suas experiências audiovisuais, incluindo avaliações, comentários e estatísticas personalizadas.

---

## 👨‍💻 Equipe de Desenvolvimento

- **Igor Marinho dos Santos Silva** - Desenvolvedor Full-Stack

---

## ⚙️ Funcionalidades da Aplicação

### 🌐 Área Pública

#### **Página Inicial (index.php)**
- Apresentação do sistema com hero section animado
- Exibição de estatísticas gerais (total de títulos, usuários, avaliações)
- Destaque de filmes e séries recentemente adicionados
- Ranking dos títulos mais bem avaliados pela comunidade
- Call-to-action para cadastro de novos usuários

#### **Catálogo Público (catalogo.php)**
- Listagem completa de todos os filmes e séries disponíveis
- Sistema de busca em tempo real por título, diretor ou gênero
- Filtros por tipo (filme/série) e gênero
- Visualização de cards com poster, informações básicas e avaliações
- Sistema de paginação para navegação eficiente

#### **Detalhes do Título (detalhes.php)**
- Informações completas: sinopse, diretor, ano, duração, gênero
- Exibição de poster em alta qualidade
- Média de avaliações dos usuários
- Lista de comentários e reviews da comunidade
- Opção de adicionar ao catálogo pessoal (usuários logados)

---

### 🔐 Área de Autenticação

#### **Login e Cadastro (login.php)**
- Sistema de login com e-mail e senha
- Cadastro de novos usuários com validação de dados
- Opção "Lembrar de mim"
- Validação de formulários no front-end e back-end
- Senha com visualização toggle (mostrar/ocultar)
- Design split-screen para login e cadastro simultâneos

#### **Logout (logout.php)**
- Encerramento seguro de sessão
- Limpeza de cookies e dados temporários
- Redirecionamento para página inicial

---

### 👤 Área do Usuário (Logado)

#### **Dashboard (dashboard.php)**
- Painel personalizado com estatísticas do usuário
- Cards com métricas: filmes assistidos, quero assistir, média de notas
- Últimos filmes adicionados ao catálogo
- Histórico de avaliações recentes
- Gêneros favoritos com gráfico de distribuição
- Barra de progresso do catálogo
- Ações rápidas (adicionar filme, ver catálogo)

#### **Meu Catálogo (meu-catalogo.php)**
- Visualização completa do catálogo pessoal do usuário
- Filtros por status: "Assistido" ou "Quero Assistir"
- Cards com poster, informações e avaliações pessoais
- Opções de gerenciamento:
  - Visualizar detalhes
  - Alterar status (assistido ↔ quero assistir)
  - Remover do catálogo
- Estatísticas do catálogo pessoal
- Interface organizada em grid responsivo

#### **Adicionar ao Catálogo (adicionar-catalogo.php)**
- Formulário para adicionar título ao catálogo pessoal
- Seleção de status (Assistido ou Quero Assistir)
- Campo para nota (0 a 10) com validação
- Área de comentário/review pessoal
- Preview das informações do filme antes de adicionar
- Validação para evitar duplicatas

#### **Avaliação de Títulos**
- Sistema de notas de 0 a 10 (com decimais)
- Campo de comentário/review
- Atualização de avaliações existentes
- Histórico de modificações

---

### 🛠️ Área Administrativa

#### **Dashboard Admin (admin/index.php)**
- Visão geral do sistema
- Estatísticas completas: usuários, filmes, avaliações
- Gráficos e métricas de uso
- Acesso rápido às funcionalidades administrativas
- Logs de atividades recentes

#### **Gerenciamento de Filmes e Séries (admin/filmes.php)**
- Listagem completa de todos os títulos cadastrados
- Busca e filtros avançados
- Tabela com informações detalhadas
- Opções de edição e exclusão
- Botão para adicionar novos títulos

#### **Adicionar Novo Título (admin/adicionar_filme.php)**
- Formulário completo para cadastro:
  - Título
  - Tipo (Filme ou Série)
  - Ano de lançamento
  - Gênero
  - Diretor
  - Sinopse
  - Duração (em minutos)
  - Upload de poster
- Validação de campos obrigatórios
- Preview de imagem antes do upload

#### **Editar Título (admin/editar_filme.php)**
- Edição de informações de títulos existentes
- Manutenção de dados históricos
- Atualização de poster
- Validação de alterações

#### **Gerenciamento de Usuários (admin/usuarios.php)**
- Listagem de todos os usuários cadastrados
- Visualização de perfis e estatísticas
- Opções de gerenciamento (ativar/desativar)
- Controle de permissões (usuário/admin)

---

## 🎨 Recursos e Diferenciais

### **Interface e Usabilidade**
- Design moderno e responsivo (Bootstrap 5)
- Animações suaves e transições
- Feedback visual para todas as ações
- Sistema de mensagens flash (sucesso, erro, aviso)
- Cards com hover effects
- Loading states e empty states

### **Sistema de Navegação**
- Navbar responsivo com menu hamburger
- Busca rápida integrada
- Dropdown de notificações (preparado para expansão)
- Menu do usuário com foto e informações
- Indicadores de página ativa
- Breadcrumbs para navegação contextual

### **Segurança**
- Senhas criptografadas com hash
- Proteção contra SQL Injection (PDO Prepared Statements)
- Sanitização de inputs
- Validação de formulários (front-end e back-end)
- Sistema de sessões seguro
- Controle de acesso por roles (usuário/admin)

### **Performance**
- Queries otimizadas com índices
- Views no banco de dados para estatísticas
- Lazy loading de imagens
- Cache de consultas frequentes
- Código modular e reutilizável

---

## 🗄️ Estrutura do Banco de Dados

### **Tabelas Principais**

**usuarios**
- Armazena dados dos usuários (nome, email, senha, tipo)
- Diferenciação entre usuário comum e administrador

**filmes_series**
- Catálogo geral de títulos disponíveis
- Informações completas (título, tipo, ano, gênero, diretor, sinopse, poster, duração)

**catalogo_usuario**
- Relacionamento entre usuários e filmes
- Status (assistido/quero_assistir)
- Avaliações (nota e comentário)
- Datas de adição e atualização

### **Views para Estatísticas**
- `vw_estatisticas_usuario` - Métricas por usuário
- `vw_filmes_top_rated` - Rankings de avaliações

---

## 🛠️ Tecnologias Utilizadas

### **Front-end**
- HTML5
- CSS3 (com variáveis CSS customizadas)
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript (ES6+)
- Design Responsivo Mobile-First

### **Back-end**
- PHP 7.4+
- PDO (PHP Data Objects)
- Arquitetura MVC simplificada
- Padrão Singleton para conexão de banco

### **Banco de Dados**
- MySQL / MariaDB
- Views e Índices otimizados
- Foreign Keys com CASCADE

### **Ferramentas de Desenvolvimento**
- Git/GitHub (controle de versão)
- XAMPP/WAMP (ambiente local)
- VS Code (editor)

---

## 📂 Estrutura de Arquivos

```
cinetrack/
├── config/                 # Configurações do sistema
│   ├── database.php       # Conexão com BD
│   └── config.php         # Configurações gerais
├── includes/              # Arquivos reutilizáveis
│   ├── header.php         # Cabeçalho HTML
│   ├── footer.php         # Rodapé HTML
│   ├── navbar.php         # Menu de navegação
│   └── functions.php      # Funções auxiliares
├── auth/                  # Autenticação
│   ├── login_process.php
│   └── register_process.php
├── actions/               # Ações do usuário
│   ├── adicionar_filme.php
│   ├── avaliar_filme.php
│   ├── remover_filme.php
│   └── atualizar_status.php
├── admin/                 # Área administrativa
│   ├── index.php
│   ├── filmes.php
│   ├── adicionar_filme.php
│   ├── editar_filme.php
│   └── usuarios.php
├── assets/                # Recursos estáticos
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       └── posters/
├── database/              # Scripts SQL
│   └── cinetrack.sql
├── index.php              # Página inicial
├── login.php              # Login/Cadastro
├── logout.php             # Logout
├── catalogo.php           # Catálogo público
├── detalhes.php           # Detalhes do filme
├── dashboard.php          # Dashboard do usuário
├── meu-catalogo.php       # Catálogo pessoal
└── adicionar-catalogo.php # Adicionar ao catálogo
```

---

## 🚀 Como Executar o Projeto

### **Requisitos**
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor Apache (XAMPP/WAMP)
- Navegador web moderno

### **Instalação**

1. **Clone o repositório**
```bash
git clone https://github.com/seu-usuario/cinetrack.git
```

2. **Configure o banco de dados**
- Importe o arquivo `database/cinetrack.sql` no phpMyAdmin
- Ajuste as credenciais em `config/database.php`

3. **Configure o servidor**
- Coloque os arquivos na pasta `htdocs` (XAMPP) ou `www` (WAMP)
- Inicie Apache e MySQL

4. **Acesse a aplicação**
```
http://localhost/cinetrack
```

### **Usuário Padrão**
- **Admin**: admin@cinetrack.com / admin123

---

## 📝 Observações

- Projeto desenvolvido como trabalho final da disciplina de Programação Web
- Implementa todos os requisitos solicitados (front-end, back-end, banco de dados)
- Código organizado seguindo boas práticas de desenvolvimento
- Interface responsiva e otimizada para diferentes dispositivos
- Sistema preparado para expansão futura (API, PWA, etc)

---

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos.

---

## 📧 Contato

**Igor Marinho dos Santos Silva**
- GitHub: [https://github.com/IgorMarinho-dev]
- Email: [contato.igormarinho083@gmail.com]

---

**Desenvolvido com ☕ e 🎬 por Igor Marinho**
