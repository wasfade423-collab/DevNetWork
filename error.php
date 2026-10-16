<?php
$pageTitle = $errorTitle ?? 'Une erreur est survenue';
require __DIR__ . '/partials/header.php';
?>
<section class="error-page">
    <span class="error-symbol" aria-hidden="true">!</span>
    <p class="eyebrow eyebrow-dark">Oups, un imprévu</p>
    <h1><?= e($errorTitle ?? 'Une erreur est survenue') ?></h1>
    <p><?= e($errorMessage ?? 'Veuillez réessayer dans quelques instants.') ?></p>
    <a class="button button-primary" href="index.php?action=home"><span aria-hidden="true">←</span> Revenir à l’annuaire</a>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
