<?php
/**
 * Header Reutilizável
 * CineTrack - Sistema de Catálogo de Filmes e Séries
 */

// Se não foi incluído o config, inclui
if (!defined('SITE_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

$pageTitle = $pageTitle ?? 'CineTrack - Seu Catálogo de Filmes e Séries';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.ico">
</head>
<body>

<?php
// Inclui o navbar
include __DIR__ . '/navbar.php';
?>

<!-- Main Content -->
<main>