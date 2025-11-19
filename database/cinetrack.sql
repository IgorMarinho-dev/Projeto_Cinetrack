-- Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS cinetrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinetrack;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('usuario', 'admin') DEFAULT 'usuario',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Tabela de Filmes e Séries
CREATE TABLE filmes_series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    tipo ENUM('filme', 'serie') NOT NULL,
    ano INT,
    genero VARCHAR(100),
    diretor VARCHAR(100),
    sinopse TEXT,
    poster_url VARCHAR(255),
    duracao INT COMMENT 'Duração em minutos',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_tipo (tipo),
    INDEX idx_genero (genero)
) ENGINE=InnoDB;

-- Tabela de Catálogo do Usuário (relacionamento)
CREATE TABLE catalogo_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    filme_serie_id INT NOT NULL,
    status ENUM('assistido', 'quero_assistir') DEFAULT 'quero_assistir',
    nota DECIMAL(3,1) CHECK (nota >= 0 AND nota <= 10),
    comentario TEXT,
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (filme_serie_id) REFERENCES filmes_series(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_film (usuario_id, filme_serie_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_filme (filme_serie_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Inserir usuário administrador padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@cinetrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserir alguns filmes de exemplo
INSERT INTO filmes_series (titulo, tipo, ano, genero, diretor, sinopse, duracao) VALUES
('A Origem', 'filme', 2010, 'Ficção Científica', 'Christopher Nolan', 
 'Dom Cobb é um ladrão com a rara habilidade de roubar segredos do inconsciente.', 148),
 
('Breaking Bad', 'serie', 2008, 'Drama', 'Vince Gilligan',
 'Um professor de química se transforma em produtor de metanfetamina.', 47),
 
('Interestelar', 'filme', 2014, 'Ficção Científica', 'Christopher Nolan',
 'Exploradores viajam através de um buraco de minhoca no espaço.', 169),
 
('Stranger Things', 'serie', 2016, 'Ficção Científica', 'Matt Duffer',
 'Crianças de uma pequena cidade enfrentam forças sobrenaturais.', 51),
 
('O Poderoso Chefão', 'filme', 1972, 'Drama', 'Francis Ford Coppola',
 'A saga da família Corleone no mundo do crime organizado.', 175);

-- View para estatísticas do usuário
CREATE VIEW vw_estatisticas_usuario AS
SELECT 
    u.id as usuario_id,
    u.nome,
    COUNT(DISTINCT CASE WHEN cu.status = 'assistido' THEN cu.id END) as total_assistidos,
    COUNT(DISTINCT CASE WHEN cu.status = 'quero_assistir' THEN cu.id END) as total_quero_assistir,
    ROUND(AVG(CASE WHEN cu.nota IS NOT NULL THEN cu.nota END), 1) as media_avaliacoes,
    COUNT(DISTINCT CASE WHEN cu.comentario IS NOT NULL AND cu.comentario != '' THEN cu.id END) as total_reviews
FROM usuarios u
LEFT JOIN catalogo_usuario cu ON u.id = cu.usuario_id
GROUP BY u.id, u.nome;

-- View para filmes mais bem avaliados
CREATE VIEW vw_filmes_top_rated AS
SELECT 
    fs.id,
    fs.titulo,
    fs.tipo,
    fs.ano,
    fs.genero,
    COUNT(cu.id) as total_avaliacoes,
    ROUND(AVG(cu.nota), 1) as nota_media
FROM filmes_series fs
INNER JOIN catalogo_usuario cu ON fs.id = cu.filme_serie_id
WHERE cu.nota IS NOT NULL
GROUP BY fs.id, fs.titulo, fs.tipo, fs.ano, fs.genero
HAVING COUNT(cu.id) >= 1
ORDER BY nota_media DESC, total_avaliacoes DESC;