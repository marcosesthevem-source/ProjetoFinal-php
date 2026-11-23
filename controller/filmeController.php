<?php
<<<<<<< HEAD

    require 'config.php';

    require 'model/filmeModel.php';

    importarFilmesDaAPI('$1b54d23fda41186da563b688f956a125');
=======

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/filmeModel.php';
>>>>>>> 7abf885537aa7a23e83c60d7b12e736099bf94c0

$erro = null;

try {
    importarFilmesDaAPI();
    $filmes = listarFilmes();
} catch (Throwable $e) {
    $erro = $e->getMessage();
    $filmes = listarFilmes();
}

<<<<<<< HEAD
    require 'view/filmeView.php';

?>
=======
require __DIR__ . '/../view/filmeView.php';
>>>>>>> 7abf885537aa7a23e83c60d7b12e736099bf94c0
