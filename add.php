<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Dev</title>
</head>

<body>
    <form action="" method="POST">
        <?php
        if (isset($id)) {
        ?>
        <input type="hidden" name="id" value='<?= $id ?>'>
        <?php } ?>
        <input type="text" name="nom" id="">
        <input type="text" name="prenom" id="">
        <div>
            <input type="radio" name="type" value="Frontend">
            <input type="radio" name="type" value="Backend">
            <input type="radio" name="type" value="FullStack">
            <button type="button">Ajouter un Type</button>
        </div>
        <input type="number" min="0" name="anneeDexperience" id="">
        <select name="niveau" id="">
            <option value="" selected disabled>Choisir votre niveau</option>
            <option value="Junior">Junior</option>
            <option value="Medium">Medium</option>
            <option value="Senior">Senior</option>
            <option value="Lead">Lead</option>
        </select>
        <div>
            <input type="checkbox" name="technos" value="PHP">
            <input type="checkbox" name="technos" value="Laravel">
            <input type="checkbox" name="technos" value="Python">
            <input type="checkbox" name="technos" value="Pandas">
            <input type="checkbox" name="technos" value="SQL">
            <input type="checkbox" name="technos" value="React">
            <button type="button">Ajouter une Techno</button>
        </div>


        <input type="submit" value='<?= $value = isset($id) ? 'Modifier' : 'Ajouter' ?>'>
        <a href="index.php?action='home'">
            <button>
                Retour l'acceuil
            </button>
        </a>
    </form>
</body>

</html>