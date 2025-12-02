<?php
// admin/includes/check_admin.php
require_once __DIR__ . '/../../config/config.php';

// Garante que o usuário está logado
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Faça login para continuar.');
    redirect('login.php');
    exit();
}

// Verifica se é admin
if (!isAdmin()) {
    setFlashMessage('error', 'Acesso negado. Área restrita a administradores.');
    redirect('index.php');
    exit();
}
