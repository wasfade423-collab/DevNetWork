<?php
require_once __DIR__ . '/../helpers.php';
$pageTitle = $pageTitle ?? 'DevNetwork';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111b31">
    <title><?= e($pageTitle) ?> · DevNetwork</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
    <header class="site-header">
        <div class="nav-wrap">
            <a class="brand" href="index.php?action=home" aria-label="DevNetwork, accueil">
                <span class="brand-mark" aria-hidden="true">D</span>
                <span>dev<span class="brand-accent">network</span></span>
            </a>
            <nav class="main-nav" aria-label="Navigation principale">
                <a href="index.php?action=home">Annuaire</a>
                <a class="nav-cta" href="index.php?action=formAdd"><span aria-hidden="true">＋</span> Ajouter un profil</a>
            </nav>
        </div>
    </header>
    <main class="page-shell">
