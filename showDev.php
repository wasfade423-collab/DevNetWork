<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page du Dev
    </title>
</head>

<body>
    <?php
    if (is_string($dev)) {
        echo "<p>" . $dev . "</p>";
    } else { ?>
        <div>

            <?= $dev["nom"] ?>
            <?= $dev["prenom"] ?>
            <?= $dev["type"] ?>
            <?= $dev["anneeDexerience"] ?>
            <?= $dev["niveau"] ?>
            <?php print_r($dev["technos"]) ?>

        </div>

    <?php } ?>


    <a href="index.php?action='home'">
        <button>
            Retour l'acceuil
        </button>
    </a>
</body>

</html>