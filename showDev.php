<?php
$pageTitle = trim(($dev['prenom'] ?? '') . ' ' . ($dev['nom'] ?? ''));
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/partials/header.php';
$fullName = trim(($dev['prenom'] ?? '') . ' ' . ($dev['nom'] ?? ''));
$initials = mb_strtoupper(mb_substr($dev['prenom'] ?? '', 0, 1) . mb_substr($dev['nom'] ?? '', 0, 1));
$technos = $dev['technos'] ?? [];
?>
<section class="profile-page">
    <a class="back-link" href="index.php?action=home"><span aria-hidden="true">←</span> Retour à l’annuaire</a>
    <?php if (!empty($flash)): ?><div class="notice notice-<?= e($flash['type'] ?? 'success') ?>" role="status"><?= e($flash['message'] ?? '') ?></div><?php endif; ?>
    <article class="profile-detail">
        <div class="profile-cover">
            <span class="cover-orb cover-orb-one"></span>
            <span class="cover-orb cover-orb-two"></span>
            <span class="profile-avatar avatar-<?= abs(crc32($dev['_id'])) % 5 ?>"><?= e($initials) ?></span>
            <span class="profile-type"><?= e($dev['type'] ?? 'Développeur') ?></span>
        </div>
        <div class="profile-content">
            <div class="profile-heading">
                <div>
                    <p class="eyebrow eyebrow-dark">Profil développeur</p>
                    <h1><?= e($fullName !== '' ? $fullName : 'Profil sans nom') ?></h1>
                    <p class="profile-subtitle"><?= e($dev['niveau'] ?? 'Niveau non renseigné') ?><?php if (!empty($dev['pays'])): ?> <span aria-hidden="true">·</span> <?= e($dev['pays']) ?><?php endif; ?></p>
                </div>
                <a class="button button-secondary" href="index.php?action=formUpdate&amp;id=<?= e($dev['_id']) ?>">Modifier le profil <span aria-hidden="true">↗</span></a>
            </div>

            <div class="profile-stats">
                <div class="profile-stat"><span class="stat-icon stat-icon-purple">◷</span><span><strong><?= e($dev['anneeDexperience'] ?? 0) ?> an<?= (int) ($dev['anneeDexperience'] ?? 0) === 1 ? '' : 's' ?></strong><small>d’expérience</small></span></div>
                <div class="profile-stat"><span class="stat-icon stat-icon-green">⌘</span><span><strong><?= count($technos) ?> technologie<?= count($technos) === 1 ? '' : 's' ?></strong><small>dans la boîte à outils</small></span></div>
                <div class="profile-stat"><span class="stat-icon stat-icon-orange">✦</span><span><strong><?= e($dev['type'] ?? 'Développeur') ?></strong><small>spécialité</small></span></div>
            </div>

            <section class="profile-tech">
                <p class="eyebrow eyebrow-dark">Boîte à outils</p>
                <h2>Technologies &amp; savoir-faire</h2>
                <?php if ($technos !== []): ?>
                    <div class="tag-list tag-list-large">
                        <?php foreach ($technos as $techno): ?><span class="tag"><?= e($techno) ?></span><?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="muted-copy">Aucune technologie n’a encore été renseignée.</p>
                <?php endif; ?>
            </section>

            <div class="profile-footer">
                <span>Profil DevNetwork <span class="profile-id">#<?= e(substr($dev['_id'], -6)) ?></span></span>
                <form action="index.php?action=delete" method="post" data-confirm="Supprimer définitivement le profil de <?= e($fullName) ?> ?">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= e($dev['_id']) ?>">
                    <button class="text-button danger-button" type="submit">Supprimer ce profil</button>
                </form>
            </div>
        </div>
    </article>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>