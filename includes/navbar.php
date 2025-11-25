<?php
/**
 * Navbar - Menu de Navegação
 * CineTrack - Sistema de Catálogo de Filmes e Séries
 */

// Se não foi incluído o config, inclui
if (!defined('SITE_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <!-- Logo/Brand -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo SITE_URL; ?>/index.php">
            <i class="bi bi-film me-2" style="font-size: 1.5rem;"></i>
            <span class="fw-bold"><?php echo SITE_NAME; ?></span>
        </a>
        
        <!-- Toggle para Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu Principal (Esquerda) -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Início -->
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>" 
                       href="<?php echo SITE_URL; ?>/index.php">
                        <i class="bi bi-house-door"></i> Início
                    </a>
                </li>
                
                <!-- Catálogo -->
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'catalogo' ? 'active' : ''; ?>" 
                       href="<?php echo SITE_URL; ?>/catalogo.php">
                        <i class="bi bi-grid-3x3-gap"></i> Catálogo
                    </a>
                </li>
                
                <?php if (isLoggedIn()): ?>
                    <!-- Meu Catálogo (apenas logado) -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'meu-catalogo' ? 'active' : ''; ?>" 
                           href="<?php echo SITE_URL; ?>/meu-catalogo.php">
                            <i class="bi bi-bookmark-heart"></i> Meu Catálogo
                        </a>
                    </li>
                    
                    <!-- Dashboard (apenas logado) -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>" 
                           href="<?php echo SITE_URL; ?>/dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- Menu do Usuário (Direita) -->
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <!-- Busca Rápida (apenas logado) -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-search"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                            <form action="<?php echo SITE_URL; ?>/catalogo.php" method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="busca" 
                                           placeholder="Buscar filmes..."
                                           autocomplete="off">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </li>
                    
                    <!-- Notificações (placeholder) -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <!-- Badge de notificações não lidas -->
                            <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span> -->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                            <li class="dropdown-header">
                                <i class="bi bi-bell"></i> Notificações
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-muted small text-center py-3" href="#">
                                    <i class="bi bi-inbox"></i>
                                    <br>Nenhuma notificação no momento
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Admin (apenas se for admin) -->
                    <?php if (isAdmin()): ?>
                        <li class="nav-item me-2">
                            <a class="nav-link text-danger" href="<?php echo SITE_URL; ?>/admin/index.php" title="Painel Admin">
                                <i class="bi bi-gear-fill"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Dropdown do Usuário -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" 
                           href="#" 
                           id="userDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false">
                            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                            <span class="d-none d-lg-inline"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2" style="font-size: 2rem;"></i>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/dashboard.php">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/meu-catalogo.php">
                                    <i class="bi bi-bookmark-heart"></i> Meu Catálogo
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/perfil.php">
                                    <i class="bi bi-person"></i> Meu Perfil
                                </a>
                            </li>
                            
                            <?php if (isAdmin()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>/admin/index.php">
                                        <i class="bi bi-gear"></i> Painel Admin
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>/logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                <?php else: ?>
                    <!-- Botão de Login (não logado) -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="<?php echo SITE_URL; ?>/login.php">
                            <i class="bi bi-person-plus"></i> Cadastrar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages (se houver) -->
<?php
$flashMessage = getFlashMessage();
if ($flashMessage):
    $alertClass = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ][$flashMessage['type']] ?? 'alert-info';
    
    $alertIcon = [
        'success' => 'check-circle-fill',
        'error' => 'exclamation-triangle-fill',
        'warning' => 'exclamation-triangle-fill',
        'info' => 'info-circle-fill'
    ][$flashMessage['type']] ?? 'info-circle-fill';
?>
<div class="container mt-3">
    <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-<?php echo $alertIcon; ?> me-2"></i>
        <?php echo htmlspecialchars($flashMessage['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<!-- CSS Adicional para o Navbar -->
<style>
/* Navbar customizado */
.navbar-dark .navbar-brand {
    transition: all 0.3s ease;
}

.navbar-dark .navbar-brand:hover {
    transform: scale(1.05);
}

.nav-link {
    position: relative;
    transition: all 0.3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: #0d6efd;
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 80%;
}

.nav-link.active {
    color: #0d6efd !important;
    font-weight: 600;
}

/* Dropdown customizado */
.dropdown-menu {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-item {
    padding: 0.7rem 1.5rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.8rem;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
    margin-right: 8px;
}

.dropdown-header {
    padding: 1rem 1.5rem;
    font-weight: 600;
    color: #333;
}

/* Badge de notificação */
.badge.rounded-pill {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
}

/* Responsividade */
@media (max-width: 991px) {
    .navbar-collapse {
        background-color: #212529;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 8px;
    }
    
    .nav-link::after {
        display: none;
    }
}
</style>