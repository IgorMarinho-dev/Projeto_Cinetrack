<?php
/**
 * Funções Auxiliares do Sistema
 * CineTrack - Sistema de Catálogo de Filmes e Séries
 */

/**
 * Busca todos os filmes e séries
 */
function getAllFilmesSeries($tipo = null, $limit = null) {
    $db = getDB();
    
    $sql = "SELECT * FROM filmes_series WHERE 1=1";
    $params = [];
    
    if ($tipo) {
        $sql .= " AND tipo = :tipo";
        $params[':tipo'] = $tipo;
    }
    
    $sql .= " ORDER BY titulo ASC";
    
    if ($limit) {
        $sql .= " LIMIT :limit";
    }
    
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    if ($limit) {
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Busca filme/série por ID
 */
function getFilmeSerieById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM filmes_series WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

/**
 * Busca catálogo do usuário
 */
function getCatalogoUsuario($userId, $status = null) {
    $db = getDB();
    
    $sql = "SELECT cu.*, fs.* 
            FROM catalogo_usuario cu
            INNER JOIN filmes_series fs ON cu.filme_serie_id = fs.id
            WHERE cu.usuario_id = :user_id";
    
    $params = [':user_id' => $userId];
    
    if ($status) {
        $sql .= " AND cu.status = :status";
        $params[':status'] = $status;
    }
    
    $sql .= " ORDER BY cu.data_adicao DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Verifica se filme já está no catálogo do usuário
 */
function filmeNoCatalogo($userId, $filmeId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM catalogo_usuario WHERE usuario_id = :user_id AND filme_serie_id = :filme_id");
    $stmt->execute([':user_id' => $userId, ':filme_id' => $filmeId]);
    return $stmt->fetch() ? true : false;
}

/**
 * Adiciona filme ao catálogo do usuário
 */
function adicionarAoCatalogo($userId, $filmeId, $status, $nota = null, $comentario = null) {
    $db = getDB();
    
    // Verifica se já existe
    if (filmeNoCatalogo($userId, $filmeId)) {
        return ['success' => false, 'message' => 'Este filme já está no seu catálogo.'];
    }
    
    try {
        $stmt = $db->prepare("
            INSERT INTO catalogo_usuario (usuario_id, filme_serie_id, status, nota, comentario)
            VALUES (:user_id, :filme_id, :status, :nota, :comentario)
        ");
        
        $stmt->execute([
            ':user_id' => $userId,
            ':filme_id' => $filmeId,
            ':status' => $status,
            ':nota' => $nota,
            ':comentario' => $comentario
        ]);
        
        return ['success' => true, 'message' => 'Adicionado ao catálogo com sucesso!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao adicionar: ' . $e->getMessage()];
    }
}

/**
 * Atualiza item do catálogo
 */
function atualizarCatalogo($catalogoId, $status = null, $nota = null, $comentario = null) {
    $db = getDB();
    
    try {
        $updates = [];
        $params = [':id' => $catalogoId];
        
        if ($status !== null) {
            $updates[] = "status = :status";
            $params[':status'] = $status;
        }
        
        if ($nota !== null) {
            $updates[] = "nota = :nota";
            $params[':nota'] = $nota;
        }
        
        if ($comentario !== null) {
            $updates[] = "comentario = :comentario";
            $params[':comentario'] = $comentario;
        }
        
        if (empty($updates)) {
            return ['success' => false, 'message' => 'Nenhum dado para atualizar.'];
        }
        
        $sql = "UPDATE catalogo_usuario SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return ['success' => true, 'message' => 'Atualizado com sucesso!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()];
    }
}

/**
 * Remove filme do catálogo
 */
function removerDoCatalogo($catalogoId, $userId) {
    $db = getDB();
    
    try {
        $stmt = $db->prepare("DELETE FROM catalogo_usuario WHERE id = :id AND usuario_id = :user_id");
        $stmt->execute([':id' => $catalogoId, ':user_id' => $userId]);
        
        return ['success' => true, 'message' => 'Removido do catálogo!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao remover: ' . $e->getMessage()];
    }
}

/**
 * Busca estatísticas do usuário
 */
function getEstatisticasUsuario($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM vw_estatisticas_usuario WHERE usuario_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch();
}

/**
 * Busca filmes mais bem avaliados
 */
function getTopRatedFilmes($limit = 10) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM vw_filmes_top_rated LIMIT :limit");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Busca filmes por termo
 */
function searchFilmes($termo) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT * FROM filmes_series 
        WHERE titulo LIKE :termo 
        OR diretor LIKE :termo 
        OR genero LIKE :termo
        ORDER BY titulo ASC
    ");
    $stmt->execute([':termo' => '%' . $termo . '%']);
    return $stmt->fetchAll();
}

/**
 * Cria novo filme/série (Admin)
 */
function criarFilmeSerie($dados) {
    $db = getDB();
    
    try {
        $stmt = $db->prepare("
            INSERT INTO filmes_series (titulo, tipo, ano, genero, diretor, sinopse, poster_url, duracao)
            VALUES (:titulo, :tipo, :ano, :genero, :diretor, :sinopse, :poster_url, :duracao)
        ");
        
        $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':tipo' => $dados['tipo'],
            ':ano' => $dados['ano'],
            ':genero' => $dados['genero'],
            ':diretor' => $dados['diretor'],
            ':sinopse' => $dados['sinopse'],
            ':poster_url' => $dados['poster_url'] ?? null,
            ':duracao' => $dados['duracao']
        ]);
        
        return ['success' => true, 'message' => 'Filme/série cadastrado com sucesso!', 'id' => $db->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao cadastrar: ' . $e->getMessage()];
    }
}

/**
 * Atualiza filme/série (Admin)
 */
function atualizarFilmeSerie($id, $dados) {
    $db = getDB();
    
    try {
        $stmt = $db->prepare("
            UPDATE filmes_series 
            SET titulo = :titulo, tipo = :tipo, ano = :ano, genero = :genero, 
                diretor = :diretor, sinopse = :sinopse, poster_url = :poster_url, duracao = :duracao
            WHERE id = :id
        ");
        
        $stmt->execute([
            ':id' => $id,
            ':titulo' => $dados['titulo'],
            ':tipo' => $dados['tipo'],
            ':ano' => $dados['ano'],
            ':genero' => $dados['genero'],
            ':diretor' => $dados['diretor'],
            ':sinopse' => $dados['sinopse'],
            ':poster_url' => $dados['poster_url'],
            ':duracao' => $dados['duracao']
        ]);
        
        return ['success' => true, 'message' => 'Atualizado com sucesso!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()];
    }
}

/**
 * Deleta filme/série (Admin)
 */
function deletarFilmeSerie($id) {
    $db = getDB();
    
    try {
        $stmt = $db->prepare("DELETE FROM filmes_series WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        return ['success' => true, 'message' => 'Deletado com sucesso!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao deletar: ' . $e->getMessage()];
    }
}
?>