<?php

    require 'config.php';

    require 'model/filmeModel.php';

    importarFilmesDaAPI('$1b54d23fda41186da563b688f956a125');

    $filmes = listarFilmes();

    require 'view/filmeView.php';

?>