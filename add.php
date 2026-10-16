<?php
$isEditing = $isEditing ?? false;
$formErrors = $formErrors ?? [];
$formData = $formData ?? [];
$pageTitle = $isEditing ? 'Modifier un profil' : 'Ajouter un profil';
$technosSelection = $formData['technos'] ?? [];
$knownTypes = ['Frontend', 'Backend', 'FullStack'];
$currentType = (string) ($formData['type'] ?? '');
if ($currentType !== '' && !in_array($currentType, $knownTypes, true)) {
    $knownTypes[] = $currentType;
}
$knownTechnos = ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'Python', 'SQL', 'React', 'MongoDB'];
$allTechnos = array_values(array_unique(array_merge($knownTechnos, $technosSelection)));
$formAction = $isEditing ? 'update' : 'add';
require __DIR__ . '/partials/header.php';
?>
<section class="form-page">
    <a class="back-link" href="<?= $isEditing ? 'index.php?action=dev&amp;id=' . e($id) : 'index.php?action=home' ?>"><span aria-hidden="true">←</span> <?= $isEditing ? 'Retour au profil' : 'Retour à l’annuaire' ?></a>
    <div class="form-layout">
        <aside class="form-intro">
            <p class="eyebrow eyebrow-dark"><?= $isEditing ? 'Mettre à jour' : 'Nouveau talent' ?></p>
            <h1><?= $isEditing ? 'Un profil à jour, de nouvelles opportunités.' : 'Faites découvrir votre talent.' ?></h1>
            <p><?= $isEditing ? 'Actualisez les informations pour que l’équipe puisse compter sur un annuaire fiable.' : 'Quelques informations suffisent pour rendre votre expertise visible à toute l’équipe.' ?></p>
            <div class="form-tip"><span aria-hidden="true">✳</span><p><strong>Un bon profil fait la différence.</strong><br>Ajoutez vos expertises et vos technologies pour faciliter les rencontres et les collaborations.</p></div>
        </aside>

        <div class="form-card">
            <div class="form-card-heading">
                <span class="step-label">PROFIL DÉVELOPPEUR</span>
                <h2><?= $isEditing ? 'Modifier les informations' : 'Vos informations' ?></h2>
                <p>Les champs marqués d’un astérisque sont obligatoires.</p>
            </div>

            <?php if ($formErrors !== []): ?>
                <div class="notice notice-error" role="alert">
                    <strong>Quelques informations sont à vérifier :</strong>
                    <ul><?php foreach ($formErrors as $message): ?><li><?= e($message) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <form action="index.php?action=<?= e($formAction) ?>" method="post" class="profile-form">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <?php if ($isEditing): ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php endif; ?>
                <div class="form-row">
                    <div class="field">
                        <label for="prenom">Prénom <span>*</span></label>
                        <input id="prenom" name="prenom" type="text" maxlength="80" autocomplete="given-name" value="<?= e($formData['prenom'] ?? '') ?>" placeholder="Ex. Alex" required>
                    </div>
                    <div class="field">
                        <label for="nom">Nom <span>*</span></label>
                        <input id="nom" name="nom" type="text" maxlength="80" autocomplete="family-name" value="<?= e($formData['nom'] ?? '') ?>" placeholder="Ex. Martin" required>
                    </div>
                </div>

                <fieldset class="field fieldset">
                    <legend>Votre spécialité <span>*</span></legend>
                    <div class="choice-list" id="type-options">
                        <?php foreach ($knownTypes as $index => $type): ?>
                            <label class="choice-pill">
                                <input type="radio" name="type" value="<?= e($type) ?>" <?= $currentType === $type || ($currentType === '' && $index === 0) ? 'checked' : '' ?> required>
                                <span><?= e($type) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="add-option-row">
                        <input type="text" id="custom-type-input" maxlength="40" placeholder="Autre spécialité">
                        <button class="text-button" type="button" data-add-option="type" data-input="custom-type-input" data-container="type-options">＋ Ajouter</button>
                    </div>
                    <?php if (isset($formErrors['type'])): ?><small class="field-error"><?= e($formErrors['type']) ?></small><?php endif; ?>
                </fieldset>

                <div class="form-row">
                    <div class="field">
                        <label for="anneeDexperience">Années d’expérience <span>*</span></label>
                        <input id="anneeDexperience" name="anneeDexperience" type="number" min="0" step="1" value="<?= e($formData['anneeDexperience'] ?? '') ?>" placeholder="Ex. 4" required>
                        <?php if (isset($formErrors['anneeDexperience'])): ?><small class="field-error"><?= e($formErrors['anneeDexperience']) ?></small><?php endif; ?>
                    </div>
                    <div class="field">
                        <label for="niveau">Niveau <span>*</span></label>
                        <select id="niveau" name="niveau" required>
                            <option value="" disabled <?= empty($formData['niveau']) ? 'selected' : '' ?>>Choisir un niveau</option>
                            <?php foreach (['Junior', 'Medium', 'Senior', 'Lead'] as $niveau): ?>
                                <option value="<?= e($niveau) ?>" <?= ($formData['niveau'] ?? '') === $niveau ? 'selected' : '' ?>><?= e($niveau) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($formErrors['niveau'])): ?><small class="field-error"><?= e($formErrors['niveau']) ?></small><?php endif; ?>
                    </div>
                </div>

                <div class="field">
                    <label for="pays">Pays <span class="optional-label">Facultatif</span></label>
                    <input id="pays" name="pays" type="text" maxlength="80" autocomplete="country-name" value="<?= e($formData['pays'] ?? '') ?>" placeholder="Ex. France">
                    <?php if (isset($formErrors['pays'])): ?><small class="field-error"><?= e($formErrors['pays']) ?></small><?php endif; ?>
                </div>

                <fieldset class="field fieldset">
                    <legend>Technologies maîtrisées <span class="optional-label">Facultatif</span></legend>
                    <div class="technology-list" id="technology-options">
                        <?php foreach ($allTechnos as $techno): ?>
                            <label class="technology-option">
                                <input type="checkbox" name="technos[]" value="<?= e($techno) ?>" <?= in_array($techno, $technosSelection, true) ? 'checked' : '' ?>>
                                <span><?= e($techno) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="add-option-row">
                        <input type="text" id="custom-tech-input" maxlength="40" placeholder="Ajouter une technologie">
                        <button class="text-button" type="button" data-add-option="technology" data-input="custom-tech-input" data-container="technology-options">＋ Ajouter</button>
                    </div>
                    <?php if (isset($formErrors['technos'])): ?><small class="field-error"><?= e($formErrors['technos']) ?></small><?php endif; ?>
                </fieldset>

                <div class="form-actions">
                    <a class="button button-secondary" href="<?= $isEditing ? 'index.php?action=dev&amp;id=' . e($id) : 'index.php?action=home' ?>">Annuler</a>
                    <button class="button button-primary" type="submit"><?= $isEditing ? 'Enregistrer les modifications' : 'Ajouter le profil' ?> <span aria-hidden="true">↗</span></button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
