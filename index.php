<?php
/**
 * Página Inicial - CineTrack
 */
require_once 'config/config.php';

$pageTitle = "CineTrack - Seu Catálogo de Filmes e Séries";

// Busca filmes em destaque (últimos adicionados)
$filmesDestaque = getAllFilmesSeries(null, 8);

// Busca filmes mais bem avaliados
$topRated = getTopRatedFilmes(6);

// Busca estatísticas gerais
$db = getDB();
$stats = $db->query("
    SELECT 
        (SELECT COUNT(*) FROM filmes_series) as total_titulos,
        (SELECT COUNT(*) FROM usuarios WHERE tipo = 'usuario') as total_usuarios,
        (SELECT COUNT(*) FROM catalogo_usuario) as total_catalogados,
        (SELECT ROUND(AVG(nota), 1) FROM catalogo_usuario WHERE nota IS NOT NULL) as media_geral
")->fetch();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">
                    <i class="bi bi-film"></i> Bem-vindo ao CineTrack
                </h1>
                <p class="lead">
                    Organize seu catálogo pessoal de filmes e séries. 
                    Avalie, comente e descubra novos títulos!
                </p>
                
                <?php if (!isLoggedIn()): ?>
                    <a href="login.php" class="btn btn-light btn-lg me-2">
                        <i class="bi bi-person-plus"></i> Criar Conta
                    </a>
                    <a href="catalogo.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-grid"></i> Ver Catálogo
                    </a>
                <?php else: ?>
                    <a href="meu-catalogo.php" class="btn btn-light btn-lg me-2">
                        <i class="bi bi-bookmark-heart"></i> Meu Catálogo
                    </a>
                    <a href="catalogo.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-grid"></i> Explorar
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-lg-5 text-center">
                <i class="bi bi-camera-reels" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Estatísticas -->
<section class="container my-5">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stats-card stats-primary">
                <i class="bi bi-film"></i>
                <h3><?php echo number_format($stats['total_titulos']); ?></h3>
                <p>Títulos Disponíveis</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-success">
                <i class="bi bi-people"></i>
                <h3><?php echo number_format($stats['total_usuarios']); ?></h3>
                <p>Usuários Ativos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-warning">
                <i class="bi bi-bookmark-check"></i>
                <h3><?php echo number_format($stats['total_catalogados']); ?></h3>
                <p>Filmes Catalogados</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-info">
                <i class="bi bi-star-fill"></i>
                <h3><?php echo $stats['media_geral'] ?? '0.0'; ?></h3>
                <p>Média de Avaliações</p>
            </div>
        </div>
    </div>
</section>

<!-- Filmes em Destaque -->
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-star"></i> Filmes e Séries em Destaque
        </h2>
        <a href="catalogo.php" class="btn btn-outline-primary">
            Ver Todos <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    
    <div class="row g-4">
        <?php if (!empty($filmesDestaque)): ?>
            <?php foreach ($filmesDestaque as $filme): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="movie-card" onclick="location.href='detalhes.php?id=<?php echo $filme['id']; ?>'">
                        <?php if (!empty($filme['poster_url'])): ?>
                            <img src="<?php echo htmlspecialchars($filme['poster_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($filme['titulo']); ?>"
                                 onerror="this.src='https://via.placeholder.com/300x400?text=Sem+Poster'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x400?text=<?php echo urlencode($filme['titulo']); ?>" 
                                 alt="<?php echo htmlspecialchars($filme['titulo']); ?>">
                        <?php endif; ?>
                        
                        <div class="movie-card-body">
                            <h5 class="movie-card-title">
                                <?php echo htmlspecialchars($filme['titulo']); ?>
                            </h5>
                            
                            <div class="mb-2">
                                <span class="movie-badge <?php echo $filme['tipo'] === 'filme' ? 'badge-filme' : 'badge-serie'; ?>" 
                                      data-type="<?php echo $filme['tipo']; ?>">
                                    <?php echo $filme['tipo'] === 'filme' ? 'Filme' : 'Série'; ?>
                                </span>
                                <span class="text-muted small"><?php echo $filme['ano']; ?></span>
                            </div>
                            
                            <p class="movie-card-text" data-genre="<?php echo htmlspecialchars($filme['genero']); ?>">
                                <i class="bi bi-tag"></i> <?php echo htmlspecialchars($filme['genero']); ?>
                            </p>
                            
                            <p class="movie-card-text" data-director="<?php echo htmlspecialchars($filme['diretor']); ?>">
                                <i class="bi bi-person"></i> <?php echo htmlspecialchars($filme['diretor']); ?>
                            </p>
                            
                            <?php
                            // Busca média de avaliações deste filme
                            $stmt = $db->prepare("
                                SELECT ROUND(AVG(nota), 1) as media, COUNT(*) as total 
                                FROM catalogo_usuario 
                                WHERE filme_serie_id = :id AND nota IS NOT NULL
                            ");
                            $stmt->execute([':id' => $filme['id']]);
                            $avaliacao = $stmt->fetch();
                            ?>
                            
                            <?php if ($avaliacao && $avaliacao['total'] > 0): ?>
                                <div class="movie-rating">
                                    <i class="bi bi-star-fill"></i>
                                    <?php echo $avaliacao['media']; ?>/10
                                    <small class="text-muted">(<?php echo $avaliacao['total']; ?>)</small>
                                </div>
                            <?php else: ?>
                                <div class="text-muted small">
                                    <i class="bi bi-star"></i> Sem avaliações
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-film"></i>
                    <h3>Nenhum filme cadastrado ainda</h3>
                    <p>Aguarde novos títulos serem adicionados!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Mais Bem Avaliados -->
<?php if (!empty($topRated)): ?>
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="bi bi-trophy"></i> Mais Bem Avaliados
        </h2>
    </div>
    
    <div class="row g-4">
        <?php foreach ($topRated as $index => $filme): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="display-4 fw-bold text-primary me-3" style="opacity: 0.3;">
                                #<?php echo $index + 1; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title">
                                    <a href="detalhes.php?id=<?php echo $filme['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($filme['titulo']); ?>
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    <?php echo $filme['tipo'] === 'filme' ? 'Filme' : 'Série'; ?> • <?php echo $filme['ano']; ?>
                                </p>
                                <div class="movie-rating mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <strong><?php echo $filme['nota_media']; ?>/10</strong>
                                    <small class="text-muted">(<?php echo $filme['total_avaliacoes']; ?> avaliações)</small>
                                </div>
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-tag"></i> <?php echo htmlspecialchars($filme['genero']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<?php if (!isLoggedIn()): ?>
<section class="container my-5">
    <div class="card text-center bg-primary text-white">
        <div class="card-body py-5">
            <h2 class="card-title fw-bold mb-3">
                <i class="bi bi-bookmark-heart"></i> Comece Agora!
            </h2>
            <p class="card-text lead mb-4">
                Crie sua conta gratuita e organize seu catálogo pessoal de filmes e séries
            </p>
            <a href="login.php" class="btn btn-light btn-lg">
                <i class="bi bi-person-plus"></i> Criar Conta Grátis
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>