<?php
/**
 * Detalhes do Filme/Série - CineTrack
 */
require_once 'config/config.php';

// Pega o ID do filme
$filmeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($filmeId === 0) {
    setFlashMessage('error', 'Filme não encontrado!');
    redirect('catalogo.php');
}

// Busca dados do filme
$filme = getFilmeSerieById($filmeId);

if (!$filme) {
    setFlashMessage('error', 'Filme não encontrado!');
    redirect('catalogo.php');
}

$pageTitle = htmlspecialchars($filme['titulo']) . " - CineTrack";

// Busca avaliações
$db = getDB();
$stmt = $db->prepare("
    SELECT ROUND(AVG(nota), 1) as media, COUNT(*) as total 
    FROM catalogo_usuario 
    WHERE filme_serie_id = :id AND nota IS NOT NULL
");
$stmt->execute([':id' => $filmeId]);
$avaliacao = $stmt->fetch();

// Busca reviews dos usuários
$stmt = $db->prepare("
    SELECT cu.*, u.nome as usuario_nome
    FROM catalogo_usuario cu
    INNER JOIN usuarios u ON cu.usuario_id = u.id
    WHERE cu.filme_serie_id = :id 
    AND (cu.comentario IS NOT NULL AND cu.comentario != '')
    ORDER BY cu.data_adicao DESC
    LIMIT 10
");
$stmt->execute([':id' => $filmeId]);
$reviews = $stmt->fetchAll();

// Verifica se está no catálogo do usuário
$noCatalogo = false;
$catalogoItem = null;
if (isLoggedIn()) {
    $stmt = $db->prepare("
        SELECT * FROM catalogo_usuario 
        WHERE usuario_id = :user_id AND filme_serie_id = :filme_id
    ");
    $stmt->execute([':user_id' => $_SESSION['user_id'], ':filme_id' => $filmeId]);
    $catalogoItem = $stmt->fetch();
    $noCatalogo = $catalogoItem ? true : false;
}

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
            <li class="breadcrumb-item"><a href="catalogo.php">Catálogo</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($filme['titulo']); ?></li>
        </ol>
    </nav>
    
    <!-- Detalhes do Filme -->
    <div class="movie-details">
        <div class="row">
            <!-- Poster -->
            <div class="col-md-4">
                <?php if (!empty($filme['poster_url'])): ?>
                    <img src="<?php echo htmlspecialchars($filme['poster_url']); ?>" 
                         alt="<?php echo htmlspecialchars($filme['titulo']); ?>"
                         class="movie-poster-large"
                         onerror="this.src='https://via.placeholder.com/400x600?text=Sem+Poster'">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x600?text=<?php echo urlencode($filme['titulo']); ?>" 
                         alt="<?php echo htmlspecialchars($filme['titulo']); ?>"
                         class="movie-poster-large">
                <?php endif; ?>
                
                <!-- Ações -->
                <?php if (isLoggedIn()): ?>
                    <div class="mt-3 d-grid gap-2">
                        <?php if ($noCatalogo): ?>
                            <a href="meu-catalogo.php" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> No Seu Catálogo
                            </a>
                            <button onclick="removeFromCatalog(<?php echo $catalogoItem['id']; ?>)" 
                                    class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Remover do Catálogo
                            </button>
                        <?php else: ?>
                            <a href="adicionar-catalogo.php?id=<?php echo $filmeId; ?>" 
                               class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle"></i> Adicionar ao Catálogo
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <a href="login.php" class="alert-link">Faça login</a> para adicionar ao seu catálogo
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Informações -->
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <?php echo htmlspecialchars($filme['titulo']); ?>
                </h1>
                
                <!-- Badges -->
                <div class="mb-3">
                    <span class="badge <?php echo $filme['tipo'] === 'filme' ? 'bg-primary' : 'bg-purple'; ?> fs-6 me-2">
                        <?php echo $filme['tipo'] === 'filme' ? 'Filme' : 'Série'; ?>
                    </span>
                    <span class="badge bg-secondary fs-6">
                        <?php echo $filme['ano']; ?>
                    </span>
                </div>
                
                <!-- Avaliação -->
                <?php if ($avaliacao && $avaliacao['total'] > 0): ?>
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="display-6 text-warning">
                                <i class="bi bi-star-fill"></i>
                                <strong><?php echo $avaliacao['media']; ?></strong>/10
                            </div>
                            <div class="text-muted">
                                <?php echo $avaliacao['total']; ?> avaliação(ões)
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mb-4 text-muted">
                        <i class="bi bi-star"></i> Ainda sem avaliações
                    </div>
                <?php endif; ?>
                
                <!-- Informações Detalhadas -->
                <div class="movie-info-item">
                    <div class="movie-info-label">
                        <i class="bi bi-tag"></i> Gênero
                    </div>
                    <div class="movie-info-value">
                        <?php echo htmlspecialchars($filme['genero']); ?>
                    </div>
                </div>
                
                <div class="movie-info-item">
                    <div class="movie-info-label">
                        <i class="bi bi-person"></i> Diretor
                    </div>
                    <div class="movie-info-value">
                        <?php echo htmlspecialchars($filme['diretor']); ?>
                    </div>
                </div>
                
                <?php if ($filme['duracao']): ?>
                    <div class="movie-info-item">
                        <div class="movie-info-label">
                            <i class="bi bi-clock"></i> Duração
                        </div>
                        <div class="movie-info-value">
                            <?php 
                            $horas = floor($filme['duracao'] / 60);
                            $minutos = $filme['duracao'] % 60;
                            if ($horas > 0) {
                                echo $horas . 'h ' . $minutos . 'min';
                            } else {
                                echo $minutos . ' minutos';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="movie-info-item">
                    <div class="movie-info-label">
                        <i class="bi bi-calendar"></i> Adicionado em
                    </div>
                    <div class="movie-info-value">
                        <?php echo formatDate($filme['data_cadastro'], 'd/m/Y'); ?>
                    </div>
                </div>
                
                <!-- Sinopse -->
                <?php if ($filme['sinopse']): ?>
                    <div class="mt-4">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-align-left"></i> Sinopse
                        </h4>
                        <p class="lead text-muted">
                            <?php echo nl2br(htmlspecialchars($filme['sinopse'])); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Reviews -->
    <?php if (!empty($reviews)): ?>
        <div class="mt-5">
            <h3 class="fw-bold mb-4">
                <i class="bi bi-chat-dots"></i> Comentários e Avaliações
            </h3>
            
            <div class="row">
                <?php foreach ($reviews as $review): ?>
                    <div class="col-md-6 mb-3">
                        <div class="review-card">
                            <div class="review-header">
                                <div>
                                    <div class="review-author">
                                        <i class="bi bi-person-circle"></i>
                                        <?php echo htmlspecialchars($review['usuario_nome']); ?>
                                    </div>
                                    <div class="review-date">
                                        <?php echo formatDate($review['data_adicao'], 'd/m/Y H:i'); ?>
                                    </div>
                                </div>
                                <?php if ($review['nota']): ?>
                                    <div class="review-rating">
                                        <i class="bi bi-star-fill"></i>
                                        <?php echo $review['nota']; ?>/10
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="review-text">
                                <?php echo nl2br(htmlspecialchars($review['comentario'])); ?>
                            </p>
                            <div class="text-muted small">
                                <span class="badge bg-light text-dark">
                                    <?php echo $review['status'] === 'assistido' ? 'Assistido' : 'Quero Assistir'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Se o usuário tem o filme no catálogo, pode avaliar -->
    <?php if ($noCatalogo && $catalogoItem): ?>
        <div class="mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="bi bi-star"></i> 
                        <?php echo $catalogoItem['nota'] ? 'Atualizar Avaliação' : 'Avaliar Este Título'; ?>
                    </h4>
                    
                    <form action="actions/avaliar_filme.php" method="POST">
                        <input type="hidden" name="catalogo_id" value="<?php echo $catalogoItem['id']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="quero_assistir" <?php echo $catalogoItem['status'] === 'quero_assistir' ? 'selected' : ''; ?>>
                                    Quero Assistir
                                </option>
                                <option value="assistido" <?php echo $catalogoItem['status'] === 'assistido' ? 'selected' : ''; ?>>
                                    Assistido
                                </option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nota (0-10)</label>
                            <input type="number" 
                                   name="nota" 
                                   class="form-control" 
                                   min="0" 
                                   max="10" 
                                   step="0.1"
                                   value="<?php echo $catalogoItem['nota'] ?? ''; ?>"
                                   placeholder="Ex: 8.5">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Comentário</label>
                            <textarea name="comentario" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Compartilhe sua opinião sobre este título..."><?php echo htmlspecialchars($catalogoItem['comentario'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salvar Avaliação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>