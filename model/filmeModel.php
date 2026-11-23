
<?php

<<<<<<< HEAD
require_once 'config.php';

function importarFilmesDaAPI($api_key){

    $url = "https://api.themoviedb.org/3/movie/popular?api_key=" . urlencode($api_key) . "&language=pt-BR";
=======
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config_tmdb.php';

/**
 * Consulta a API do TMDB usando o Read Access Token (v4).
 */
function consultarTMDB($endpoint, $parametros = [])
{
    $parametros['language'] = TMDB_LANGUAGE;

    $url = TMDB_URL . $endpoint . '?' . http_build_query($parametros);
>>>>>>> 7abf885537aa7a23e83c60d7b12e736099bf94c0

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
<<<<<<< HEAD
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($curl);

    if($response === false){
        $erro = curl_error($curl);
        curl_close($curl);
        throw new Exception("Erro na conexão com a TMDB: " . $erro);
    }

=======
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . TMDB_TOKEN,
            'accept: application/json'
        ],
        CURLOPT_TIMEOUT => 20
    ]);

    $resposta = curl_exec($curl);
    $erro = curl_error($curl);
>>>>>>> 7abf885537aa7a23e83c60d7b12e736099bf94c0
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

<<<<<<< HEAD
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
=======
    if ($resposta === false) {
        throw new Exception('Falha ao conectar ao TMDB: ' . $erro);
    }

    if ($status !== 200) {
        throw new Exception('TMDB retornou HTTP ' . $status . '. Verifique o token e a conexão.');
    }

    $dados = json_decode($resposta, true);

    if (!is_array($dados)) {
        throw new Exception('A resposta do TMDB não é um JSON válido.');
    }

    return $dados;
}

/**
 * Importa os filmes populares do TMDB para a tabela filmes.
 */
function importarFilmesDaAPI()
{
    global $pdo;

    $dados = consultarTMDB('/movie/popular');

    if (empty($dados['results'])) {
        return;
    }

    $sql = "INSERT INTO filmes (titulo, descricao, poster)
            SELECT :titulo, :descricao, :poster
            WHERE NOT EXISTS (
                SELECT 1 FROM filmes WHERE titulo = :titulo_verificar
            )";

    $stmt = $pdo->prepare($sql);

    foreach ($dados['results'] as $filme) {
        $titulo = $filme['title'] ?? 'Título não disponível';
        $descricao = $filme['overview'] ?? '';
        $poster = !empty($filme['poster_path'])
            ? TMDB_IMAGE_URL . $filme['poster_path']
            : '';

        $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':poster' => $poster,
            ':titulo_verificar' => $titulo
        ]);
    }
}

function listarFilmes()
{
    global $pdo;

    $sql = "SELECT * FROM filmes ORDER BY id DESC";
    $resultado = $pdo->query($sql);

    return $resultado->fetchAll(PDO::FETCH_ASSOC);
}
>>>>>>> 7abf885537aa7a23e83c60d7b12e736099bf94c0
