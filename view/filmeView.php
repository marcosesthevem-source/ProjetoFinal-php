<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Filmes Populares</title>
</head>
<body>
    <h1>Filmes Populares</h1>
    <hr>

    <?php if (!empty($erro)): ?>
        <p role="alert">
            Não foi possível atualizar os filmes pelo TMDB:
            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <div id="filmes-container">
        <?php if (empty($filmes)): ?>
            <p>Nenhum filme encontrado. Verifique a configuração do TMDB e tente novamente.</p>
        <?php else: ?>
            <?php foreach ($filmes as $f): ?>
                <?php
                    $titulo = htmlspecialchars($f['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
                    $descricao = htmlspecialchars($f['descricao'] ?? '', ENT_QUOTES, 'UTF-8');
                    $poster = htmlspecialchars($f['poster'] ?? '', ENT_QUOTES, 'UTF-8');
                ?>

                <div class="filme">
                    <h2><?= $titulo ?></h2>

                    <?php if (!empty($poster)): ?>
                        <img src="<?= $poster ?>" alt="Pôster de <?= $titulo ?>">
                    <?php endif; ?>

                    <p><?= $descricao !== '' ? $descricao : 'Sinopse não disponível.' ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
