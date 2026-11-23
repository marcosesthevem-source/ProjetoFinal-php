<?php
require_once 'config.php'
function importarFilmesDaAPI($api_key){  
    $url = "http://api.themoviedb.org/3/movie/popular?api_key=$api_key&language=pt-BR";
 
    $response = file_get_contents($url); // Json
    $data = json_decode($response, true); // Converte Json para Array Associativo
    
    global $pdo;
    
    foreach ($data['results'] as $filme){
        
        $titulo = $filme['title'];
        $descricao = $filme['overview'];
        $poster = "http://image.tmdb.org/t/p/w500" . $filme['poster_path'];
        
        salvarFilme($titulo, $descricao, $poster);
    }
}
function salvarFilme($titulo, $descricao, $poster){
    
    global $pdo;
    
    try{
        $titulo = $pdo->quote($titulo);
        $descricao = $pdo->quote($descricao);
        $poster = $pdo->quote($poster);
        
        $verificacao = "SELECT * FROM filmes WHERE titulo = $titulo";
        $resultado = $pdo->query($verificacao);
        
        if($resultado->rowCount() > 0){
            return;
        }
        $sql = "INSERT INTO filmes (titulo, descricao, poster)
        VALUES ($titulo, $descricao, $poster)";
        
        $pdo->query($sql);
        
    }catch(PDOException $e){ 
        echo "Erro ao salvar '$titulo': "
        . $e->getMessage() . "<br>";
    }
}
function listarFilmes(){
    
    global $pdo;
    
    $sql = "SELECT * FROM filmes";
    $result = $pdo->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

?>