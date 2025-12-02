<?php
// actions/avaliar_filme.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../auth/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('meu-catalogo.php');
}

$catalogoId = isset($_POST['catalogo_id']) ? (int)$_POST['catalogo_id'] : 0;
$nota       = isset($_POST['nota']) && $_POST['nota'] !== '' ? (float)$_POST['nota'] : null;
$comentario = !empty($_POST['comentario']) ? sanitize($_POST['comentario']) : null;

if ($catalogoId <= 0) {
    setFlashMessage('error', 'Item do catálogo não encontrado.');
    redirect('meu-catalogo.php');
}

if ($nota !== null && ($nota < 0 || $nota > 10)) {
    setFlashMessage('error', 'A nota deve estar entre 0 e 10.');
    redirect('meu-catalogo.php');
}

$resultado = atualizarCatalogo($catalogoId, null, $nota, $comentario);

if ($resultado['success']) {
    setFlashMessage('success', $resultado['message']);
} else {
    setFlashMessage('error', $resultado['message']);
}

$redirectUrl = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : 'meu-catalogo.php';
redirect($redirectUrl);
