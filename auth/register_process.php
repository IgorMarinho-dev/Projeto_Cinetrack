<?php
// auth/register_process.php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$nome     = sanitize($_POST['nome'] ?? '');
$email    = sanitize($_POST['email'] ?? '');
$senha    = $_POST['senha'] ?? '';
$confirma = $_POST['confirmar_senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha) || empty($confirma)) {
    setFlashMessage('error', 'Preencha todos os campos.');
    redirect('login.php');
}

if ($senha !== $confirma) {
    setFlashMessage('error', 'As senhas não coincidem.');
    redirect('login.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlashMessage('error', 'E-mail inválido.');
    redirect('login.php');
}

try {
    $db = getDB();

    // Verifica se já existe usuário com esse e-mail
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        setFlashMessage('warning', 'Já existe uma conta com esse e-mail.');
        redirect('login.php');
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $db->prepare("
        INSERT INTO usuarios (nome, email, senha, tipo)
        VALUES (:nome, :email, :senha, :tipo)
    ");
    $stmt->execute([
        ':nome'  => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':tipo'  => 'usuario'
    ]);

    setFlashMessage('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    redirect('login.php');

} catch (PDOException $e) {
    if (DEBUG_MODE) {
        setFlashMessage('error', 'Erro ao cadastrar: ' . $e->getMessage());
    } else {
        setFlashMessage('error', 'Erro ao processar cadastro.');
    }
    redirect('login.php');
}
