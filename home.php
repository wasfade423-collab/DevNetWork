<?php
$pageTitle = 'Annuaire des développeurs';
require __DIR__ . '/partials/header.php';
$developers = is_array($devs) ? $devs : [];
$totalTechnologies = count(array_unique(array_merge([], ...array_map(
    static fn (array $developer): array => $developer['technos'] ?? [],
    $developers
))));
$fullStackCount = count(array_filter(
    $developers,
    static fn (array $developer): bool => stripos((string) ($developer['type'] ?? ''), 'full') !== false
));
?>
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow"><span class="status-dot"></span> La communauté tech en un seul endroit</p>
        <h1>Les bonnes personnes.<br><span>Les bons projets.</span></h1>
        <p class="hero-lead">Découvrez les talents de l’équipe, leurs expertises et les technologies qu’ils aiment utiliser.</p>
        <a class="button button-light" href="index.php?action=formAdd">Présenter un développeur <span aria-hidden="true">↗</span></a>
    </div>
    <div class="hero-visual" aria-hidden="true">
        <div class="orbit orbit-one"></div>
        <div class="orbit orbit-two"></div>
        <div class="visual-card visual-card-main"><span class="visual-spark">✳</span><strong>La tech<br>est collective.</strong><small>Une équipe, mille idées.</small></div>
        <div class="visual-card visual-card-small"><span class="mini-avatar">+</span><span><strong><?= count($developers) ?> talents</strong><small>et ça grandit</small></span></div>
        <span class="visual-star star-one">✳</span>
        <span class="visual-star star-two">✦</span>
    </div>
</section>

<?php if (!empty($flash)): ?>
    <div class="notice notice-<?= e($flash['type'] ?? 'success') ?>" role="status"><?= e($flash['message'] ?? '') ?></div>
<?php endif; ?>

<section class="directory-section" aria-labelledby="directory-title">
    <div class="section-heading">
        <div>
            <p class="eyebrow eyebrow-dark">L’annuaire</p>
            <h2 id="directory-title">Une équipe qui fait <span>la différence.</span></h2>
            <p class="section-lead">Explorez les profils et trouvez la bonne expertise pour votre prochain projet.</p>
        </div>
        <a class="button button-primary desktop-add" href="index.php?action=formAdd"><span aria-hidden="true">＋</span> Ajouter un profil</a>
    </div>

    <div class="stats-row" aria-label="Statistiques de l’annuaire">
        <div class="stat-item"><span class="stat-icon stat-icon-purple">◉</span><span><strong><?= count($developers) ?></strong><small>développeurs</small></span></div>
        <div class="stat-item"><span class="stat-icon stat-icon-green">⌘</span><span><strong><?= $totalTechnologies ?></strong><small>technologies</small></span></div>
        <div class="stat-item"><span class="stat-icon stat-icon-orange">✳</span><span><strong><?= $fullStackCount ?></strong><small>profils full stack</small></span></div>
    </div>

    <div class="directory-toolbar">
        <label class="search-box">
            <span class="search-icon" aria-hidden="true">⌕</span>
            <span class="sr-only">Rechercher un développeur</span>
            <input type="search" id="developer-search" placeholder="Rechercher un nom, une techno, un rôle…" autocomplete="off">
            <kbd>/</kbd>
        </label>
        <span class="result-count" id="result-count" aria-live="polite"><?= count($developers) ?> profil<?= count($developers) === 1 ? '' : 's' ?></span>
    </div>

    <?php if ($developers === []): ?>
        <div class="empty-state">
            <span class="empty-icon" aria-hidden="true">✳</span>
            <h3>L’annuaire commence avec vous.</h3>
            <p>Ajoutez le premier profil pour présenter les talents de votre équipe.</p>
            <a class="button button-primary" href="index.php?action=formAdd">Ajouter un premier profil <span aria-hidden="true">↗</span></a>
        </div>
    <?php else: ?>
        <div class="developer-grid" id="developer-grid">
            <?php foreach ($developers as $developer):
                $fullName = trim(($developer['prenom'] ?? '') . ' ' . ($developer['nom'] ?? ''));
                $technos = $developer['technos'] ?? [];
                $searchText = implode(' ', [
                    $fullName,
                    $developer['type'] ?? '',
                    $developer['niveau'] ?? '',
                    $developer['pays'] ?? '',
                    implode(' ', $technos),
                ]);
                $initials = mb_strtoupper(mb_substr($developer['prenom'] ?? '', 0, 1) . mb_substr($developer['nom'] ?? '', 0, 1));
            ?>
                <article class="developer-card" data-developer-card data-search="<?= e(mb_strtolower($searchText)) ?>">
                    <div class="card-topline">
                        <span class="avatar avatar-<?= abs(crc32($developer['_id'])) % 5 ?>"><?= e($initials) ?></span>
                        <span class="profile-type"><?= e($developer['type'] ?? 'Développeur') ?></span>
                        <a class="icon-link" href="index.php?action=dev&amp;id=<?= e($developer['_id']) ?>" aria-label="Voir le profil de <?= e($fullName) ?>">↗</a>
                    </div>
                    <a class="card-main-link" href="index.php?action=dev&amp;id=<?= e($developer['_id']) ?>">
                        <h3><?= e($fullName !== '' ? $fullName : 'Profil sans nom') ?></h3>
                        <p class="card-subtitle"><?= e($developer['niveau'] ?? 'Niveau non renseigné') ?><?php if (!empty($developer['pays'])): ?> <span aria-hidden="true">·</span> <?= e($developer['pays']) ?><?php endif; ?></p>
                    </a>
                    <div class="card-facts">
                        <span><strong><?= e($developer['anneeDexperience'] ?? 0) ?></strong> an<?= (int) ($developer['anneeDexperience'] ?? 0) === 1 ? '' : 's' ?> d’expérience</span>
                    </div>
                    <div class="tag-list" aria-label="Technologies">
                        <?php foreach (array_slice($technos, 0, 3) as $techno): ?>
                            <span class="tag"><?= e($techno) ?></span>
                        <?php endforeach; ?>
                        <?php if (count($technos) > 3): ?><span class="tag tag-more">+<?= count($technos) - 3 ?></span><?php endif; ?>
                        <?php if ($technos === []): ?><span class="tag tag-muted">Technologies à renseigner</span><?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <a href="index.php?action=dev&amp;id=<?= e($developer['_id']) ?>">Voir le profil <span aria-hidden="true">→</span></a>
                        <a class="subtle-action" href="index.php?action=formUpdate&amp;id=<?= e($developer['_id']) ?>">Modifier</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="empty-state search-empty" id="search-empty" hidden>
            <span class="empty-icon" aria-hidden="true">⌕</span>
            <h3>Aucun profil trouvé.</h3>
            <p>Essayez avec un autre nom, un rôle ou une technologie.</p>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
