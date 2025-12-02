<?php
// actions/adicionar_filme.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../admin/includes/check_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/filmes.php');
}

$titulo  = sanitize($_POST['titulo'] ?? '');
$tipo    = sanitize($_POST['tipo'] ?? '');
$ano     = !empty($_POST['ano']) ? (int)$_POST['ano'] : null;
$genero  = sanitize($_POST['genero'] ?? '');
$diretor = sanitize($_POST['diretor'] ?? '');
$sinopse = !empty($_POST['sinopse']) ? sanitize($_POST['sinopse']) : null;
$duracao = !empty($_POST['duracao']) ? (int)$_POST['duracao'] : null;
$posterUrl = !empty($_POST['poster_url']) ? sanitize($_POST['poster_url']) : null;

if (empty($titulo) || empty($tipo)) {
    setFlashMessage('error', 'Título e tipo são obrigatórios.');
    redirect('admin/adicionar_filmes.php');
}

if (!in_array($tipo, ['filme', 'serie'])) {
    setFlashMessage('error', 'Tipo inválido. Selecione filme ou série.');
    redirect('admin/adicionar_filmes.php');
}

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

$resultado = criarFilmeSerie($dados);

if ($resultado['success']) {
    setFlashMessage('success', $resultado['message']);
    redirect('admin/filmes.php');
} else {
    setFlashMessage('error', $resultado['message']);
    redirect('admin/adicionar_filmes.php');
}
