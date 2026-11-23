<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config_tmdb.php';

/**
 * Consulta a API do TMDB usando o Read Access Token (v4).
 */
function consultarTMDB($endpoint, $parametros = [])
{
    $parametros['language'] = TMDB_LANGUAGE;

    $url = TMDB_URL . $endpoint . '?' . http_build_query($parametros);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . TMDB_TOKEN,
            'accept: application/json'
        ],
        CURLOPT_TIMEOUT => 20
    ]);

    $resposta = curl_exec($curl);
    $erro = curl_error($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

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
