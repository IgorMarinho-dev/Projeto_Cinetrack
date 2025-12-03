<?php
// admin/editar_filme.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/check_admin.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    setFlashMessage('error', 'Filme não encontrado.');
    redirect('admin/filmes.php');
}

$filme = getFilmeSerieById($id);

if (!$filme) {
    setFlashMessage('error', 'Filme não encontrado.');
    redirect('admin/filmes.php');
}

$pageTitle = 'Editar Filme/Série - ' . SITE_NAME;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = sanitize($_POST['titulo'] ?? '');
    $tipo     = sanitize($_POST['tipo'] ?? '');
    $ano      = !empty($_POST['ano']) ? (int)$_POST['ano'] : null;
    $genero   = sanitize($_POST['genero'] ?? '');
    $diretor  = sanitize($_POST['diretor'] ?? '');
    $sinopse  = !empty($_POST['sinopse']) ? sanitize($_POST['sinopse']) : null;
    $duracao  = !empty($_POST['duracao']) ? (int)$_POST['duracao'] : null;
    $posterUrl = !empty($_POST['poster_url']) ? sanitize($_POST['poster_url']) : null;

    if (empty($titulo) || empty($tipo)) {
        setFlashMessage('error', 'Título e tipo são obrigatórios.');
    } elseif (!in_array($tipo, ['filme', 'serie'])) {
        setFlashMessage('error', 'Tipo inválido.');
    } else {
        $dados = [
            'titulo'     => $titulo,
            'tipo'       => $tipo,
            'ano'        => $ano,
            'genero'     => $genero,
            'diretor'    => $diretor,
            'sinopse'    => $sinopse,
            'poster_url' => $posterUrl,
            'duracao'    => $duracao
        ];

        $resultado = atualizarFilmeSerie($id, $dados);

        if ($resultado['success']) {
            setFlashMessage('success', $resultado['message']);
            redirect('admin/filmes.php');
        } else {
            setFlashMessage('error', $resultado['message']);
        }
    }

    // Recarrega o filme com valores atualizados do POST para manter no formulário
    $filme = array_merge($filme, [
        'titulo'     => $titulo,
        'tipo'       => $tipo,
        'ano'        => $ano,
        'genero'     => $genero,
        'diretor'    => $diretor,
        'sinopse'    => $sinopse,
        'poster_url' => $posterUrl,
        'duracao'    => $duracao
    ]);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Editar Filme/Série</h1>

    <form method="POST" class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control"
                   value="<?php echo htmlspecialchars($filme['titulo']); ?>" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipo *</label>
            <select name="tipo" class="form-select" required>
                <option value="filme" <?php echo $filme['tipo'] === 'filme' ? 'selected' : ''; ?>>Filme</option>
                <option value="serie" <?php echo $filme['tipo'] === 'serie' ? 'selected' : ''; ?>>Série</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Ano</label>
            <input type="number" name="ano" class="form-control"
                   value="<?php echo htmlspecialchars($filme['ano']); ?>">
        </div>

        <div class="col-md-5">
            <label class="form-label">Gênero</label>
            <input type="text
