<?php
// admin/usuarios.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/check_admin.php';

$pageTitle = 'Gerenciar Usuários - ' . SITE_NAME;

$db = getDB();
$stmt = $db->query("SELECT id, nome, email, tipo, data_cadastro FROM usuarios ORDER BY data_cadastro DESC");
$usuarios = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Usuários</h1>

    <?php if (empty($usuarios)): ?>
        <div class="alert alert-info">
            Nenhum usuário encontrado.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th>Data de cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?php echo (int)$u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['nome']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $u['tipo'] === 'admin' ? 'danger' : 'secondary'; ?>">
                                    <?php echo ucfirst($u['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo formatDate($u['data_cadastro']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-3">
        <a href="index.php" class="btn btn-link">
            <i class="bi bi-arrow-left"></i> Voltar ao painel
        </a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
