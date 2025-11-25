<?php
/**
 * Logout - CineTrack
 */
require_once 'config/config.php';

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Destrói o cookie de sessão se existir
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destrói a sessão
session_destroy();

// Inicia nova sessão para mensagem flash
session_start();
setFlashMessage('success', 'Você saiu com sucesso! Até logo! 👋');

// Redireciona para página inicial
redirect('index.php');
?>