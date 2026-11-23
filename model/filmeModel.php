
<?php

require_once 'config.php';

function importarFilmesDaAPI($api_key){

    $url = "https://api.themoviedb.org/3/movie/popular?api_key=" . urlencode($api_key) . "&language=pt-BR";

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($curl);

    if($response === false){
        $erro = curl_error($curl);
        curl_close($curl);
        throw new Exception("Erro na conexão com a TMDB: " . $erro);
    }

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if($status != 200){
        throw new Exception("Erro na API TMDB. Código HTTP: " . $status);
    }

    $data = json_decode($response, true);

    if(!isset($data['results'])){
        throw new Exception("Não foi possível obter os filmes da TMDB.");
    }

    global $pdo;

    foreach ($data['results'] as $filme){

        $titulo = $filme['title'] ?? '';
        $descricao = $filme['overview'] ?? '';

        $poster = !empty($filme['poster_path'])
            ? "https://image.tmdb.org/t/p/w500" . $filme['poster_path']
            : '';

        salvarFilme($titulo, $descricao, $poster);
    }
}

function salvarFilme($titulo, $descricao, $poster){

    global $pdo;

    try{

        $verificacao = "SELECT * FROM filmes WHERE titulo = :titulo";

        $resultado = $pdo->prepare($verificacao);
        $resultado->execute([
            ':titulo' => $titulo
        ]);

        if($resultado->rowCount() > 0){
            return;
        }

        $sql = "INSERT INTO filmes (titulo, descricao, poster)
                VALUES (:titulo, :descricao, :poster)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':poster' => $poster
        ]);

    }catch(PDOException $e){

        echo "Erro ao salvar '$titulo': " . $e->getMessage() . "<br>";

    }
}

function listarFilmes(){

    global $pdo;

    $sql = "SELECT * FROM filmes";

    $result = $pdo->query($sql);

    return $result->fetchAll(PDO::FETCH_ASSOC);
}

?>
