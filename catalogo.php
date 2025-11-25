<?php
/**
 * Catálogo Público - CineTrack
 */
require_once 'config/config.php';

$pageTitle = "Catálogo - CineTrack";

// Busca e filtros
$searchTerm = isset($_GET['busca']) ? sanitize($_GET['busca']) : '';
$filterType = isset($_GET['tipo']) ? sanitize($_GET['tipo']) : '';
$filterGenre = isset($_GET['genero']) ? sanitize($_GET['genero']) : '';

// Busca filmes
if (!empty($searchTerm)) {
    $filmes = searchFilmes($searchTerm);
} else {
    $filmes = getAllFilmesSeries($filterType);
}

// Aplica filtro de gênero se necessário
if (!empty($filterGenre) && !empty($filmes)) {
    $filmes = array_filter($filmes, function($filme) use ($filterGenre) {
        return stripos($filme['genero'], $filterGenre) !== false;
    });
}

// Busca todos os gêneros disponíveis
$db = getDB();
$generos = $db->query("SELECT DISTINCT genero FROM filmes_series ORDER BY genero")->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Cabeçalho -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">
            <i class="bi bi-film"></i> Catálogo Completo
        </h1>
        <p class="lead text-muted">
            Explore nossa coleção de <?php echo count($filmes); ?> filmes e séries
        </p>
    </div>
    
    <!-- Busca e Filtros -->
    <div class="search-filter-section mb-4">
        <form method="GET" action="catalogo.php" class="row g-3">
            <!-- Campo de Busca -->
            <div class="col-md-5">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="searchInput"
                           name="busca" 
                           placeholder="Buscar por título, diretor ou gênero..."
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
            </div>
            
            <!-- Filtro de Tipo -->
            <div class="col-md-3">
                <select class="form-select form-select-lg" id="typeFilter" name="tipo">
                    <option value="">Todos os Tipos</option>
                    <option value="filme" <?php echo $filterType === 'filme' ? 'selected' : ''; ?>>
                        Filmes
                    </option>
                    <option value="serie" <?php echo $filterType === 'serie' ? 'selected' : ''; ?>>
                        Séries
                    </option>
                </select>
            </div>
            
            <!-- Filtro de Gênero -->
            <div class="col-md-3">
                <select class="form-select form-select-lg" name="genero">
                    <option value="">Todos os Gêneros</option>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo htmlspecialchars($genero); ?>"
                                <?php echo $filterGenre === $genero ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genero); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Botão Buscar -->
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
        
        <!-- Limpar Filtros -->
        <?php if (!empty($searchTerm) || !empty($filterType) || !empty($filterGenre)): ?>
            <div class="text-center mt-3">
                <a href="catalogo.php" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpar Filtros
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Contagem de Resultados -->
    <div class="mb-3">
        <p class="text-muted">
            <i class="bi bi-info-circle"></i> 
            Exibindo <strong><?php echo count($filmes); ?></strong> resultado(s)
            <?php if (!empty($searchTerm)): ?>
                para "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"
            <?php endif; ?>
        </p>
    </div>
    
    <!-- Grid de Filmes -->
    <div class="row g-4" id="moviesGrid">
        <?php if (!empty($filmes)): ?>
            <?php foreach ($filmes as $filme): ?>
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
                            // Busca média de avaliações
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
                            
                            <!-- Botão Adicionar ao Catálogo -->
                            <?php if (isLoggedIn()): ?>
                                <?php
                                $jaNoCatalogo = filmeNoCatalogo($_SESSION['user_id'], $filme['id']);
                                ?>
                                <div class="mt-3">
                                    <?php if ($jaNoCatalogo): ?>
                                        <button class="btn btn-sm btn-success w-100" disabled>
                                            <i class="bi bi-check-circle"></i> No Catálogo
                                        </button>
                                    <?php else: ?>
                                        <a href="adicionar-catalogo.php?id=<?php echo $filme['id']; ?>" 
                                           class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-plus-circle"></i> Adicionar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Estado Vazio -->
            <div class="col-12">
                <div class="empty-state" id="emptyState">
                    <i class="bi bi-film"></i>
                    <h3>Nenhum filme encontrado</h3>
                    <p class="text-muted">
                        Tente ajustar os filtros ou fazer uma nova busca
                    </p>
                    <a href="catalogo.php" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-clockwise"></i> Ver Todos
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>