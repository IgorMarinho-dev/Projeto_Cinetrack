<?php
// auth/check_auth.php
require_once __DIR__ . '/../config/config.php';

// Se não estiver logado, manda para login
if (!isLoggedIn()) {
    setFlashMessage('warning', 'Você precisa estar logado para acessar esta página.');
    redirect('login.php');
    exit();
}
