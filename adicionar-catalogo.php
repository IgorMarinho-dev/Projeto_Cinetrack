<?php
/**
 * Adicionar ao Catálogo - CineTrack
 */
require_once 'config/config.php';

// Verifica se está logado
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Você precisa fazer login para adicionar filmes ao catálogo.');
    redirect('login.php');
}

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

// Verifica se já está no catálogo
if (filmeNoCatalogo($_SESSION['user_id'], $filmeId)) {
    setFlashMessage('info', 'Este filme já está no seu catálogo!');
    redirect('detalhes.php?id=' . $filmeId);
}

$pageTitle = "Adicionar ao Catálogo - " . htmlspecialchars($filme['titulo']);

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status']);
    $nota = !empty($_POST['nota']) ? (float)$_POST['nota'] : null;
    $comentario = !empty($_POST['comentario']) ? sanitize($_POST['comentario']) : null;
    
    // Valida nota
    if ($nota !== null && ($nota < 0 || $nota > 10)) {
        setFlashMessage('error', 'A nota deve estar entre 0 e 10.');
    } else {
        // Adiciona ao catálogo
        $resultado = adicionarAoCatalogo($_SESSION['user_id'], $filmeId, $status, $nota, $comentario);
        
        if ($resultado['success']) {
            setFlashMessage('success', $resultado['message']);
            redirect('meu-catalogo.php');
        } else {
            setFlashMessage('error', $resultado['message']);
        }
    }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
            <li class="breadcrumb-item"><a href="catalogo.php">Catálogo</a></li>
            <li class="breadcrumb-item"><a href="detalhes.php?id=<?php echo $filmeId; ?>"><?php echo htmlspecialchars($filme['titulo']); ?></a></li>
            <li class="breadcrumb-item active">Adicionar ao Catálogo</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Card do Filme -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Adicionar ao Meu Catálogo
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Informações do Filme -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <?php if (!empty($filme['poster_url'])): ?>
                                <img src="<?php echo htmlspecialchars($filme['poster_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($filme['titulo']); ?>"
                                     class="img-fluid rounded shadow"
                                     onerror="this.src='https://via.placeholder.com/200x300?text=Sem+Poster'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/200x300?text=<?php echo urlencode($filme['titulo']); ?>" 
                                     alt="<?php echo htmlspecialchars($filme['titulo']); ?>"
                                     class="img-fluid rounded shadow">
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-9">
                            <h3 class="fw-bold mb-3"><?php echo htmlspecialchars($filme['titulo']); ?></h3>
                            
                            <div class="mb-2">
                                <span class="badge bg-<?php echo $filme['tipo'] === 'filme' ? 'primary' : 'purple'; ?> fs-6">
                                    <?php echo $filme['tipo'] === 'filme' ? 'Filme' : 'Série'; ?>
                                </span>
                                <span class="badge bg-secondary fs-6"><?php echo $filme['ano']; ?></span>
                            </div>
                            
                            <p class="text-muted mb-2">
                                <i class="bi bi-tag"></i> <strong>Gênero:</strong> <?php echo htmlspecialchars($filme['genero']); ?>
                            </p>
                            
                            <p class="text-muted mb-2">
                                <i class="bi bi-person"></i> <strong>Diretor:</strong> <?php echo htmlspecialchars($filme['diretor']); ?>
                            </p>
                            
                            <?php if ($filme['duracao']): ?>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-clock"></i> <strong>Duração:</strong> 
                                    <?php 
                                    $horas = floor($filme['duracao'] / 60);
                                    $minutos = $filme['duracao'] % 60;
                                    if ($horas > 0) {
                                        echo $horas . 'h ' . $minutos . 'min';
                                    } else {
                                        echo $minutos . ' minutos';
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($filme['sinopse']): ?>
                                <div class="mt-3">
                                    <p class="text-muted small">
                                        <?php echo nl2br(htmlspecialchars($filme['sinopse'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Formulário -->
                    <form method="POST" class="needs-validation" novalidate>
                        <h5 class="mb-4">
                            <i class="bi bi-pencil-square"></i> Informações do Catálogo
                        </h5>
                        
                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-bookmark-check"></i> Status *
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline p-3 border rounded w-100">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="statusAssistido" 
                                               value="assistido" 
                                               required>
                                        <label class="form-check-label w-100" for="statusAssistido">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <strong>Já Assistí</strong>
                                            <p class="small text-muted mb-0">Você já assistiu este título</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline p-3 border rounded w-100">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="statusQuero" 
                                               value="quero_assistir"
                                               checked 
                                               required>
                                        <label class="form-check-label w-100" for="statusQuero">
                                            <i class="bi bi-clock-history text-warning"></i>
                                            <strong>Quero Assistir</strong>
                                            <p class="small text-muted mb-0">Adicionar à lista de desejos</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Por favor, selecione um status.
                            </div>
                        </div>
                        
                        <!-- Nota -->
                        <div class="mb-4">
                            <label for="nota" class="form-label fw-bold">
                                <i class="bi bi-star"></i> Sua Nota (opcional)
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" 
                                       class="form-control" 
                                       id="nota" 
                                       name="nota" 
                                       min="0" 
                                       max="10" 
                                       step="0.1"
                                       placeholder="0.0">
                                <span class="input-group-text">/10</span>
                            </div>
                            <div class="form-text">
                                Dê uma nota de 0 a 10 para este título (ex: 8.5)
                            </div>
                        </div>
                        
                        <!-- Comentário -->
                        <div class="mb-4">
                            <label for="comentario" class="form-label fw-bold">
                                <i class="bi bi-chat-dots"></i> Seu Comentário (opcional)
                            </label>
                            <textarea class="form-control" 
                                      id="comentario" 
                                      name="comentario" 
                                      rows="5"
                                      placeholder="Compartilhe sua opinião sobre este título..."></textarea>
                            <div class="form-text">
                                Escreva o que achou deste filme/série
                            </div>
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="detalhes.php?id=<?php echo $filmeId; ?>" 
                               class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle"></i> Adicionar ao Catálogo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Dica -->
            <div class="alert alert-info">
                <i class="bi bi-lightbulb"></i>
                <strong>Dica:</strong> Você pode editar suas avaliações e comentários a qualquer momento 
                acessando a página de detalhes do filme no seu catálogo.
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
}

.form-check-inline {
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-check-inline:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

.form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
}
</style>

<?php include 'includes/footer.php'; ?>