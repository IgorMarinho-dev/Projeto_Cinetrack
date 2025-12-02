<?php
// admin/adicionar_filmes.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/check_admin.php';

$pageTitle = 'Adicionar Filme/Série - ' . SITE_NAME;

include __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Adicionar Filme/Série</h1>

    <form action="../actions/adicionar_filme.php" method="POST" class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipo *</label>
            <select name="tipo" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="filme">Filme</option>
                <option value="serie">Série</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Ano</label>
            <input type="number" name="ano" class="form-control" min="1900" max="<?php echo date('Y') + 1; ?>">
        </div>

        <div class="col-md-5">
            <label class="form-label">Gênero</label>
            <input type="text" name="genero" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Diretor</label>
            <input type="text" name="diretor" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Duração (minutos)</label>
            <input type="number" name="duracao" class="form-control" min="1">
        </div>

        <div class="col-md-8">
            <label class="form-label">URL do Poster (opcional)</label>
            <input type="text" name="poster_url" class="form-control" placeholder="https://...">
        </div>

        <div class="col-12">
            <label class="form-label">Sinopse</label>
            <textarea name="sinopse" rows="4" class="form-control"></textarea>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="filmes.php" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Salvar
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
