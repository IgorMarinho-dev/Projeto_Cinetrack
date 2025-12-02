<?php
// admin/index.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/check_admin.php';

$pageTitle = 'Dashboard Administrativo - ' . SITE_NAME;

include __DIR__ . '/../includes/header.php';

// Estatísticas simples
$db = getDB();

// Total de usuários
$totalUsuarios = $db->query("SELECT COUNT(*) AS total FROM usuarios")->fetch()['total'] ?? 0;

// Total de filmes/séries
$totalFilmes = $db->query("SELECT COUNT(*) AS total FROM filmes_series")->fetch()['total'] ?? 0;

// Total de itens em catálogos
$totalCatalogo = $db->query("SELECT COUNT(*) AS total FROM catalogo_usuario")->fetch()['total'] ?? 0;
?>

<div class="container my-5">
    <h1 class="mb-4">Painel Administrativo</h1>
    <p class="text-muted">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>.</p>

    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Usuários</h5>
                    <p class="display-6 mb-0"><?php echo (int)$totalUsuarios; ?></p>
                    <p class="text-muted mb-0">Contas cadastradas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Filmes e Séries</h5>
                    <p class="display-6 mb-0"><?php echo (int)$totalFilmes; ?></p>
                    <p class="text-muted mb-0">Títulos cadastrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Catálogos</h5>
                    <p class="display-6 mb-0"><?php echo (int)$totalCatalogo; ?></p>
                    <p class="text-muted mb-0">Itens em catálogos de usuários</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex gap-2">
        <a href="filmes.php" class="btn btn-primary">
            <i class="bi bi-film"></i> Gerenciar Filmes/Séries
        </a>
        <a href="usuarios.php" class="btn btn-outline-secondary">
            <i class="bi bi-people"></i> Gerenciar Usuários
        </a>
        <a href="../index.php" class="btn btn-link">
            <i class="bi bi-arrow-left"></i> Voltar ao site
        </a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
