<?php
    
    require 'config.php';
    require 'model/filmeModel.php';

    importarFilmesDaAPI('');

    $filmes = listarFilmes();

    require 'view/filmeView.php'
?>