<?php
// auth/login_process.php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$email = sanitize($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    setFlashMessage('error', 'Preencha e-mail e senha.');
    redirect('login.php');
}

try {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT id, nome, email, senha, tipo 
        FROM usuarios 
        WHERE email = :email 
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        setFlashMessage('error', 'E-mail ou senha inválidos.');
        redirect('login.php');
    }

    // Seta sessão
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['nome'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_type']  = $user['tipo'] ?? 'usuario';

    setFlashMessage('success', 'Login realizado com sucesso!');

    if (isAdmin()) {
        redirect('admin/index.php');
    } else {
        redirect('dashboard.php');
    }

} catch (PDOException $e) {
    if (DEBUG_MODE) {
        setFlashMessage('error', 'Erro no login: ' . $e->getMessage());
    } else {
        setFlashMessage('error', 'Erro ao processar login.');
    }
    redirect('login.php');
}
