<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Filmes Populares</title>
</head>
<body>
    <h1>Filmes Populares</h1>
    <hr>
    <div id="filmes-container">
        <?php 
            foreach ($filmes as $f){

                $titulo = htmlspecialchars($f['titulo'], ENT_QUOTES, 'UTF-8');
                $descricao = htmlspecialchars($f['descricao'], ENT_QUOTES, 'UTF-8');
                $poster = htmlspecialchars($f['poster'], ENT_QUOTES, 'UTF-8');

                echo '<div class="filme">';
                echo '<h2>' . $titulo . '</h2>';
                echo '<img src="' . $poster . '" alt="' . $titulo . '">';
                echo '<p>' . $descricao . '</p>';
                echo '</div>';
            }
        ?>
    </div>
</body>
</html>