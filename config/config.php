<?php
/**
 * Configurações Gerais do Sistema
 * CineTrack - Sistema de Catálogo de Filmes e Séries
 */

// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do sistema
define('SITE_NAME', 'CineTrack');
define('SITE_URL', 'http://localhost/cinetrack');
define('BASE_PATH', __DIR__ . '/..');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro (desenvolvimento)
// IMPORTANTE: Mudar para false em produção
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Inclui a conexão com o banco de dados
require_once __DIR__ . '/database.php';

// Inclui funções auxiliares
require_once BASE_PATH . '/includes/functions.php';

/**
 * Função para redirecionar
 */
function redirect($page) {
    header("Location: " . SITE_URL . "/" . $page);
    exit();
}

/**
 * Função para verificar se o usuário está logado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Função para verificar se o usuário é admin
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Função para pegar dados do usuário logado
 */
function getUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'nome' => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'tipo' => $_SESSION['user_type'] ?? 'usuario'
    ];
}

/**
 * Função para sanitizar dados de entrada
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Função para definir mensagens flash
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, error, warning, info
        'message' => $message
    ];
}

/**
 * Função para obter e limpar mensagem flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Função para formatar data
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

/**
 * Função para upload de imagem
 */
function uploadPoster($file) {
    $uploadDir = BASE_PATH . '/assets/images/posters/';
    
    // Cria o diretório se não existir
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Validações
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use JPG, PNG ou GIF.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Arquivo muito grande. Máximo 5MB.'];
    }
    
    // Gera nome único para o arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('poster_') . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move o arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => '/assets/images/posters/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Erro ao fazer upload do arquivo.'];
}
?>