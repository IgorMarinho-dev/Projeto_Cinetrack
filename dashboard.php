<?php
/**
 * Dashboard do Usuário - CineTrack
 */
require_once 'config/config.php';

// Verifica se está logado
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Você precisa fazer login para acessar o dashboard.');
    redirect('login.php');
}

$pageTitle = "Dashboard - CineTrack";
$userId = $_SESSION['user_id'];

// Busca estatísticas do usuário
$stats = getEstatisticasUsuario($userId);

// Busca últimos filmes adicionados pelo usuário
$ultimosAdicionados = getCatalogoUsuario($userId);
$ultimosAdicionados = array_slice($ultimosAdicionados, 0, 4); // Últimos 4

// Busca filmes por status
$assistidos = getCatalogoUsuario($userId, 'assistido');
$queroAssistir = getCatalogoUsuario($userId, 'quero_assistir');

// Busca distribuição por gênero
$db = getDB();
$stmt = $db->prepare("
    SELECT fs.genero, COUNT(*) as total
    FROM catalogo_usuario cu
    INNER JOIN filmes_series fs ON cu.filme_serie_id = fs.id
    WHERE cu.usuario_id = :user_id
    GROUP BY fs.genero
    ORDER BY total DESC
    LIMIT 5
");
$stmt->execute([':user_id' => $userId]);
$generosFavoritos = $stmt->fetchAll();

// Busca últimas avaliações
$stmt = $db->prepare("
    SELECT cu.*, fs.titulo, fs.tipo, fs.poster_url
    FROM catalogo_usuario cu
    INNER JOIN filmes_series fs ON cu.filme_serie_id = fs.id
    WHERE cu.usuario_id = :user_id AND cu.nota IS NOT NULL
    ORDER BY cu.data_atualizacao DESC
    LIMIT 5
");
$stmt->execute([':user_id' => $userId]);
$ultimasAvaliacoes = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Cabeçalho -->
    <div class="mb-4">
        <h1 class="display-5 fw-bold">
            <i class="bi bi-speedometer2"></i> Dashboard
        </h1>
        <p class="lead text-muted">
            Bem-vindo de volta, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!
        </p>
    </div>
    
    <!-- Estatísticas -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stats-card stats-primary">
                <i class="bi bi-check-circle"></i>
                <h3><?php echo $stats['total_assistidos'] ?? 0; ?></h3>
                <p>Assistidos</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card stats-warning">
                <i class="bi bi-clock-history"></i>
                <h3><?php echo $stats['total_quero_assistir'] ?? 0; ?></h3>
                <p>Quero Assistir</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card stats-success">
                <i class="bi bi-star-fill"></i>
                <h3><?php echo $stats['media_avaliacoes'] ?? '0.0'; ?></h3>
                <p>Média das Notas</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card stats-info">
                <i class="bi bi-chat-dots"></i>
                <h3><?php echo $stats['total_reviews'] ?? 0; ?></h3>
                <p>Comentários</p>
            </div>
        </div>
    </div>
    
    <!-- Conteúdo Principal -->
    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-lg-8">
            <!-- Últimos Adicionados -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Últimos Adicionados
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($ultimosAdicionados)): ?>
                        <div class="row g-3">
                            <?php foreach ($ultimosAdicionados as $item): ?>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 align-items-start">
                                        <?php if (!empty($item['poster_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['poster_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['titulo']); ?>"
                                                 style="width: 80px; height: 120px; object-fit: cover; border-radius: 8px;"
                                                 onerror="this.src='https://via.placeholder.com/80x120?text=Poster'">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/80x120?text=Poster" 
                                                 alt="<?php echo htmlspecialchars($item['titulo']); ?>"
                                                 style="width: 80px; height: 120px; object-fit: cover; border-radius: 8px;">
                                        <?php endif; ?>
                                        
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="detalhes.php?id=<?php echo $item['id']; ?>" 
                                                   class="text-decoration-none">
                                                    <?php echo htmlspecialchars($item['titulo']); ?>
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-1">
                                                <?php echo $item['tipo'] === 'filme' ? 'Filme' : 'Série'; ?> • <?php echo $item['ano']; ?>
                                            </p>
                                            <span class="badge bg-<?php echo $item['status'] === 'assistido' ? 'success' : 'warning'; ?>">
                                                <?php echo $item['status'] === 'assistido' ? 'Assistido' : 'Quero Assistir'; ?>
                                            </span>
                                            <?php if ($item['nota']): ?>
                                                <span class="text-warning small ms-2">
                                                    <i class="bi bi-star-fill"></i> <?php echo $item['nota']; ?>/10
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="meu-catalogo.php" class="btn btn-outline-primary">
                                Ver Meu Catálogo Completo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="empty-state py-4">
                            <i class="bi bi-inbox"></i>
                            <h5>Nenhum filme no catálogo</h5>
                            <p class="text-muted">Comece adicionando filmes e séries!</p>
                            <a href="catalogo.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Explorar Catálogo
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Últimas Avaliações -->
            <?php if (!empty($ultimasAvaliacoes)): ?>
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-star"></i> Últimas Avaliações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($ultimasAvaliacoes as $avaliacao): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="detalhes.php?id=<?php echo $avaliacao['filme_serie_id']; ?>" 
                                               class="text-decoration-none">
                                                <?php echo htmlspecialchars($avaliacao['titulo']); ?>
                                            </a>
                                        </h6>
                                        <div class="text-warning mb-2">
                                            <i class="bi bi-star-fill"></i>
                                            <strong><?php echo $avaliacao['nota']; ?>/10</strong>
                                        </div>
                                        <?php if ($avaliacao['comentario']): ?>
                                            <p class="text-muted small mb-0 line-clamp-3">
                                                "<?php echo htmlspecialchars($avaliacao['comentario']); ?>"
                                            </p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <?php echo formatDate($avaliacao['data_atualizacao']); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Coluna Direita -->
        <div class="col-lg-4">
            <!-- Ações Rápidas -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="catalogo.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Adicionar Filme
                        </a>
                        <a href="meu-catalogo.php" class="btn btn-outline-primary">
                            <i class="bi bi-bookmark-heart"></i> Meu Catálogo
                        </a>
                        <?php if (isAdmin()): ?>
                            <a href="admin/index.php" class="btn btn-outline-danger">
                                <i class="bi bi-gear"></i> Painel Admin
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Gêneros Favoritos -->
            <?php if (!empty($generosFavoritos)): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-heart"></i> Seus Gêneros Favoritos
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($generosFavoritos as $genero): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold"><?php echo htmlspecialchars($genero['genero']); ?></span>
                            <span class="badge bg-success"><?php echo $genero['total']; ?> títulos</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <?php 
                            $total = $stats['total_assistidos'] + $stats['total_quero_assistir'];
                            $percentage = $total > 0 ? ($genero['total'] / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" 
                                 style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Progresso -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> Seu Progresso
                    </h5>
                </div>
                <div class="card-body">
                    <?php 
                    $totalCatalogo = $stats['total_assistidos'] + $stats['total_quero_assistir'];
                    $percentualAssistido = $totalCatalogo > 0 ? ($stats['total_assistidos'] / $totalCatalogo) * 100 : 0;
                    ?>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Assistidos</span>
                            <span class="text-muted"><?php echo round($percentualAssistido); ?>%</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?php echo $percentualAssistido; ?>%">
                                <?php echo $stats['total_assistidos']; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Total no Catálogo</p>
                        <h3 class="fw-bold text-primary"><?php echo $totalCatalogo; ?></h3>
                        <p class="text-muted small">
                            Continue adicionando e avaliando!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>