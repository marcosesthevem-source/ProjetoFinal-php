<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/filmeModel.php';

$erro = null;

try {
    importarFilmesDaAPI();
    $filmes = listarFilmes();
} catch (Throwable $e) {
    $erro = $e->getMessage();
    $filmes = listarFilmes();
}

require __DIR__ . '/../view/filmeView.php';
