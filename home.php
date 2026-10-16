<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Devs</title>
</head>

<body>

    <a href="index.php?action=formAdd">
        <button>
            Ajouter un Dev
        </button>
    </a>
    <?php
    if (is_array($devs)) {
    ?>
    <div>

        <!-- 
            Un grid avec 5 devs par ligne.
         -->
        <?php
            foreach ($dev as $devs) {
            ?>

        <div>
            <?= $dev["nom"] ?>
            <?= $dev["prenom"] ?>
            <?= $dev["type"] ?>
            <?= $dev["anneeDexerience"] ?>
            <?= $dev["niveau"] ?>
            <?php print_r($dev["technos"]) ?>
            <!-- Infos du dev -->

            <a href="index.php?action=formUpdate">
                <button>
                    Modifier
                </button>
            </a>
            <a href="index.php?action=delete">
                <button>
                    Delete
                </button>
            </a>
        </div>
        <?php } ?>
    </div>
    <?php } else {
        echo "<p>Aucun Dev pour le moment";
    } ?>
</body>

</html>