<?php
// actions/remover_filme.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../auth/check_auth.php';

$catalogoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($catalogoId <= 0) {
    setFlashMessage('error', 'Item do catálogo não encontrado.');
    redirect('meu-catalogo.php');
}

$resultado = removerDoCatalogo($catalogoId, $_SESSION['user_id']);

if ($resultado['success']) {
    setFlashMessage('success', $resultado['message']);
} else {
    setFlashMessage('error', $resultado['message']);
}

$redirectUrl = !empty($_GET['redirect_to']) ? $_GET['redirect_to'] : 'meu-catalogo.php';
redirect($redirectUrl);
