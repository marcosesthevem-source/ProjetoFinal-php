
<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$db = "tmdb_filmes";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Não foi possível realizar a conexão!");
}
