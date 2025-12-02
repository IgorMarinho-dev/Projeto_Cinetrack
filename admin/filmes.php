<?php
// admin/filmes.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/check_admin.php';

$pageTitle = 'Gerenciar Filmes e Séries - ' . SITE_NAME;

$db = getDB();
$stmt = $db->query("SELECT * FROM filmes_series ORDER BY data_cadastro DESC, titulo ASC");
$filmes = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Filmes e Séries</h1>
        <a href="adicionar_filmes.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Adicionar Novo
        </a>
    </div>

    <?php if (empty($filmes)): ?>
        <div class="alert alert-info">
            Nenhum título cadastrado ainda.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Ano</th>
                        <th>Gênero</th>
                        <th>Duração</th>
                        <th>Cadastro</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filmes as $f): ?>
                        <tr>
                            <td><?php echo (int)$f['id']; ?></td>
                            <td><?php echo htmlspecialchars($f['titulo']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $f['tipo'] === 'filme' ? 'primary' : 'purple'; ?>">
                                    <?php echo $f['tipo'] === 'filme' ? 'Filme' : 'Série'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($f['ano']); ?></td>
                            <td><?php echo htmlspecialchars($f['genero']); ?></td>
                            <td>
                                <?php
                                if ($f['duracao']) {
                                    $h = floor($f['duracao'] / 60);
                                    $m = $f['duracao'] % 60;
                                    echo $h > 0 ? "{$h}h {$m}min" : "{$m} min";
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td><?php echo formatDate($f['data_cadastro']); ?></td>
                            <td class="text-end">
                                <a href="editar_filme.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="../actions/remover_filme.php?id=<?php echo $f['id']; ?>&redirect_to=admin/filmes.php"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Tem certeza que deseja remover este título do catálogo dos usuários?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
