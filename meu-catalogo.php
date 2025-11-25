<?php
/**
 * Meu Catálogo - CineTrack
 */
require_once 'config/config.php';

// Verifica se está logado
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Você precisa fazer login para acessar seu catálogo.');
    redirect('login.php');
}

$pageTitle = "Meu Catálogo - CineTrack";
$userId = $_SESSION['user_id'];

// Filtro de status
$filterStatus = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Busca catálogo do usuário
if (!empty($filterStatus)) {
    $catalogo = getCatalogoUsuario($userId, $filterStatus);
} else {
    $catalogo = getCatalogoUsuario($userId);
}

// Busca estatísticas
$stats = getEstatisticasUsuario($userId);

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Cabeçalho -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">
            <i class="bi bi-bookmark-heart"></i> Meu Catálogo
        </h1>
        <p class="lead text-muted">
            Gerencie seus filmes e séries
        </p>
    </div>
    
    <!-- Estatísticas Rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2"><?php echo $stats['total_assistidos'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">Assistidos</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2"><?php echo $stats['total_quero_assistir'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">Quero Assistir</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2"><?php echo $stats['media_avaliacoes'] ?? '0.0'; ?></h3>
                    <p class="text-muted mb-0">Média de Notas</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <a href="meu-catalogo.php" 
                           class="btn btn-outline-primary <?php echo empty($filterStatus) ? 'active' : ''; ?>">
                            <i class="bi bi-grid"></i> Todos (<?php echo count(getCatalogoUsuario($userId)); ?>)
                        </a>
                        <a href="meu-catalogo.php?status=assistido" 
                           class="btn btn-outline-success <?php echo $filterStatus === 'assistido' ? 'active' : ''; ?>">
                            <i class="bi bi-check-circle"></i> Assistidos (<?php echo $stats['total_assistidos']; ?>)
                        </a>
                        <a href="meu-catalogo.php?status=quero_assistir" 
                           class="btn btn-outline-warning <?php echo $filterStatus === 'quero_assistir' ? 'active' : ''; ?>">
                            <i class="bi bi-clock-history"></i> Quero Assistir (<?php echo $stats['total_quero_assistir']; ?>)
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4 text-end">
                    <a href="catalogo.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Adicionar Mais
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Filmes -->
    <?php if (!empty($catalogo)): ?>
        <div class="row g-4">
            <?php foreach ($catalogo as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <?php if (!empty($item['poster_url'])): ?>
                                <img src="<?php echo htmlspecialchars($item['poster_url']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($item['titulo']); ?>"
                                     style="height: 400px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/300x400?text=Sem+Poster'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x400?text=<?php echo urlencode($item['titulo']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($item['titulo']); ?>"
                                     style="height: 400px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <!-- Badge de Status -->
                            <span class="position-absolute top-0 end-0 m-2 badge bg-<?php echo $item['status'] === 'assistido' ? 'success' : 'warning'; ?>">
                                <?php echo $item['status'] === 'assistido' ? 'Assistido' : 'Quero Assistir'; ?>
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="detalhes.php?id=<?php echo $item['id']; ?>" 
                                   class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($item['titulo']); ?>
                                </a>
                            </h5>
                            
                            <div class="mb-2">
                                <span class="badge <?php echo $item['tipo'] === 'filme' ? 'bg-primary' : 'bg-purple'; ?>">
                                    <?php echo $item['tipo'] === 'filme' ? 'Filme' : 'Série'; ?>
                                </span>
                                <span class="text-muted small"><?php echo $item['ano']; ?></span>
                            </div>
                            
                            <p class="card-text small text-muted mb-2">
                                <i class="bi bi-tag"></i> <?php echo htmlspecialchars($item['genero']); ?>
                            </p>
                            
                            <!-- Nota -->
                            <?php if ($item['nota']): ?>
                                <div class="mb-2">
                                    <span class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <strong><?php echo $item['nota']; ?>/10</strong>
                                    </span>
                                    <small class="text-muted">- Sua nota</small>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Comentário -->
                            <?php if ($item['comentario']): ?>
                                <div class="mb-3">
                                    <p class="card-text small text-muted fst-italic line-clamp-3">
                                        "<?php echo htmlspecialchars($item['comentario']); ?>"
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Data de Adição -->
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-plus"></i>
                                    Adicionado em <?php echo formatDate($item['data_adicao'], 'd/m/Y'); ?>
                                </small>
                            </p>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex gap-2">
                                <a href="detalhes.php?id=<?php echo $item['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="bi bi-eye"></i> Ver Detalhes
                                </a>
                                
                                <!-- Dropdown de Ações -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if ($item['status'] === 'quero_assistir'): ?>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="#"
                                                   onclick="updateStatus(<?php echo $item['id']; ?>, 'assistido'); return false;">
                                                    <i class="bi bi-check-circle text-success"></i> Marcar como Assistido
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="#"
                                                   onclick="updateStatus(<?php echo $item['id']; ?>, 'quero_assistir'); return false;">
                                                    <i class="bi bi-clock-history text-warning"></i> Marcar como Quero Assistir
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" 
                                               href="#"
                                               onclick="removeFromCatalog(<?php echo $item['id']; ?>); return false;">
                                                <i class="bi bi-trash"></i> Remover do Catálogo
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Estado Vazio -->
        <div class="empty-state py-5">
            <i class="bi bi-inbox"></i>
            <h3>Seu catálogo está vazio</h3>
            <p class="text-muted">
                <?php if (!empty($filterStatus)): ?>
                    Você não tem filmes com este status.
                    <br>
                    <a href="meu-catalogo.php" class="btn btn-link">Ver todos</a>
                <?php else: ?>
                    Comece adicionando filmes e séries que você assistiu ou quer assistir!
                <?php endif; ?>
            </p>
            <a href="catalogo.php" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-plus-circle"></i> Explorar Catálogo
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- CSS adicional para badge roxo -->
<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>

<?php include 'includes/footer.php'; ?>