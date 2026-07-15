<?php

$action = $_GET['action'] ?? '';

$classes = getClasses();
$niveaux = getNiveaux();

$etudiants = [];
$meilleur = null;
$meilleurNiveau = null;
$auDessus = [];

$mode = 'add';

if ($action === 'edit' && isset($_GET['id'])) {

    $mode = 'edit';

    $etudiant = getEtudiantById($_GET['id']);
}

if ($action === 'liste' && isset($_GET['id_classe']) && $_GET['id_classe'] !== '') {

    $etudiants = getEtudiantsParClasse($_GET['id_classe']);
}

if ($action === 'meilleur_classe' && isset($_GET['id_classe']) && $_GET['id_classe'] !== '') {

    $meilleur = getMeilleurEtudiantClasse($_GET['id_classe']);
}

if ($action === 'meilleur_niveau' && isset($_GET['id_niveau']) && $_GET['id_niveau'] !== '') {

    $meilleurNiveau = getMeilleurEtudiantNiveau($_GET['id_niveau']);
}

if ($action === 'au_dessus' && isset($_GET['id_classe']) && $_GET['id_classe'] !== '') {

    $auDessus = getEtudiantsAuDessusMoyenneClasse($_GET['id_classe']);
}

?>

<div class="text-center mb-5">

    <h1 class="fw-bold text-secondary">
        <i class="fa-solid fa-user-graduate me-2"></i>

        Gestion des Étudiants

    </h1>

    <p class="text-muted">
        Administration et suivi académique des étudiants
    </p>

</div>

<ul class="nav nav-tabs mb-4 shadow-sm rounded-4 p-3 bg-light">

    <li class="nav-item">

        <a class="nav-link text-dark <?= ($action === 'add') ? 'active' : '' ?>"
           href="index.php?page=etudiants&action=add">

           <i class="fas fa-user-plus me-2"></i>

            Inscription

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link text-dark <?= ($action === 'liste') ? 'active' : '' ?>"
           href="index.php?page=etudiants&action=liste">

           <i class="fas fa-list me-2"></i>

            Liste

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link text-dark <?= ($action === 'meilleur_classe') ? 'active' : '' ?>"
           href="index.php?page=etudiants&action=meilleur_classe">

           <i class="fas fa-trophy me-2"></i>

            Meilleur classe

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link text-dark <?= ($action === 'meilleur_niveau') ? 'active' : '' ?>"
           href="index.php?page=etudiants&action=meilleur_niveau">

           <i class="fas fa-medal me-2"></i>

            Meilleur niveau

        </a>

    </li>

    <li class="nav-item">

        <a class="nav-link text-dark <?= ($action === 'au_dessus') ? 'active' : '' ?>"
           href="index.php?page=etudiants&action=au_dessus">

           <i class="fas fa-chart-line me-2"></i>

            Au-dessus moyenne

        </a>

    </li>

</ul>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>

<div class="alert alert-success alert-dismissible fade show shadow-sm">

    Étudiant ajouté avec succès.

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>

<div class="alert alert-success alert-dismissible fade show shadow-sm">

    Étudiant modifié avec succès.

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>

<div class="alert alert-success alert-dismissible fade show shadow-sm">

    Étudiant supprimé avec succès.

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>

<div class="alert alert-danger alert-dismissible fade show shadow-sm">

    Une erreur est survenue lors de la suppression.

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>


<!-- 
     AJOUT / MODIFICATION
 -->

<?php if ($action === 'add' || $action === 'edit'): ?>

<div class="card shadow-sm border-0 col-md-6 mb-4">

    <div class="card-header bg-light fw-bold border-bottom">

        <?= ($mode === 'edit')
            ? 'Modifier étudiant'
            : 'Inscrire un étudiant' ?>

    </div>

    <div class="card-body p-4">

        <form method="post"
              action="traitements/action.php">

            <?php if ($mode === 'edit'): ?>

                <input type="hidden"
                       name="id_etudiant"
                       value="<?= $etudiant['id_etudiant'] ?>">

            <?php endif; ?>

            <div class="mb-3">

                <label class="fw-semibold">Prénom</label>

                <input type="text"
                       name="prenom"
                       class="form-control"
                       required
                       value="<?= ($mode === 'edit')
                            ? htmlspecialchars($etudiant['prenom'])
                            : '' ?>">

            </div>

            <div class="mb-3">

                <label class="fw-semibold">Nom</label>

                <input type="text"
                       name="nom"
                       class="form-control"
                       required
                       value="<?= ($mode === 'edit')
                            ? htmlspecialchars($etudiant['nom'])
                            : '' ?>">

            </div>

            <div class="mb-3">

                <label class="fw-semibold">Classe</label>

                <select name="id_classe"
                        class="form-control"
                        required>

                    <option value="">-- Choisir une classe --</option>

                    <?php foreach ($classes as $c): ?>

                        <option value="<?= $c['id_classe'] ?>"

                            <?= ($mode === 'edit'
                                && $c['id_classe'] == $etudiant['id_classe'])
                                ? 'selected'
                                : '' ?>>

                            <?= htmlspecialchars($c['libelle_classe']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <button type="submit"
                    name="<?= ($mode === 'edit')
                        ? 'updateEtudiant'
                        : 'addEtudiant' ?>"
                    class="btn btn-secondary">

                <?= ($mode === 'edit')
                    ? 'Modifier'
                    : 'Ajouter' ?>

            </button>

            <?php if ($mode === 'edit'): ?>

                <a href="index.php?page=etudiants&action=liste"
                   class="btn btn-outline-secondary ms-2">

                    Annuler

                </a>

            <?php endif; ?>

        </form>

    </div>

</div>

<?php endif; ?>


<!-- 
     LISTE ETUDIANTS
 -->

<?php if ($action === 'liste'): ?>

<div class="card shadow-sm border-0 col-md-11 mb-4">

    <div class="card-header bg-light fw-bold border-bottom">

        Étudiants d’une classe

    </div>

    <div class="card-body">

        <form method="get" class="mb-4">

            <input type="hidden" name="page" value="etudiants">

            <input type="hidden" name="action" value="liste">

            <select name="id_classe"
                    class="form-control mb-3"
                    required>

                <option value="">-- Choisir une classe --</option>

                <?php foreach ($classes as $c): ?>

                    <option value="<?= $c['id_classe'] ?>">

                        <?= htmlspecialchars($c['libelle_classe']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button class="btn btn-secondary">

                Afficher

            </button>

        </form>

        <?php if (!empty($etudiants)): ?>

        <table class="table table-hover align-middle">

            <thead class="table-secondary">

                <tr>

                    <th>Matricule</th>

                    <th>Prénom</th>

                    <th>Nom</th>

                    <th>Prédiction IA</th>

                    <th>Bulletin</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($etudiants as $e): ?>

                <?php
                    $prediction = predireRisqueEtudiant($e['id_etudiant']);
                    $faible = getModuleFaible($e['id_etudiant']);
                ?>

                <tr>

                    <td><?= htmlspecialchars($e['matricule']) ?></td>

                    <td><?= htmlspecialchars($e['prenom']) ?></td>

                    <td><?= htmlspecialchars($e['nom']) ?></td>

                    <td>

                        <span class="badge bg-<?= $prediction['couleur'] ?>">

                            <?= $prediction['niveau'] ?>

                        </span>

                        <br>

                        <small class="text-muted">

                            <?= $prediction['message'] ?>

                        </small>

                        <?php if($faible): ?>

                            <br>

                            <small class="text-danger">

                                Module faible :
                                <?= $faible['libelle_module'] ?>

                            </small>

                        <?php endif; ?>

                    </td>

                    <td>

                        <a href="index.php?page=bulletin&id_etudiant=<?= $e['id_etudiant'] ?>"
                           class="btn btn-outline-secondary btn-sm"
                           target="_blank">

                            Bulletin

                        </a>

                    </td>

                    <td class="d-flex gap-2">

                        <a href="index.php?page=etudiants&action=edit&id=<?= $e['id_etudiant'] ?>"
                           class="btn btn-outline-secondary btn-sm">

                            Modifier

                        </a>

                        <a href="traitements/action.php?delete=<?= $e['id_etudiant'] ?>"
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Supprimer cet étudiant ?')">

                            Supprimer

                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>


<!-- 
     MEILLEUR CLASSE
 -->

<?php if ($action === 'meilleur_classe'): ?>

<div class="card shadow-sm border-0 col-md-6 mb-4">

    <div class="card-header bg-light fw-bold border-bottom">

        Meilleur étudiant de la classe

    </div>

    <div class="card-body">

        <form method="get" class="mb-4">

            <input type="hidden" name="page" value="etudiants">

            <input type="hidden" name="action" value="meilleur_classe">

            <select name="id_classe"
                    class="form-control mb-3"
                    required>

                <option value="">-- Choisir une classe --</option>

                <?php foreach ($classes as $c): ?>

                    <option value="<?= $c['id_classe'] ?>">

                        <?= htmlspecialchars($c['libelle_classe']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button class="btn btn-secondary">

                Afficher

            </button>

        </form>

        <?php if ($meilleur): ?>

            <div class="alert alert-success mb-0">

                <strong>

                    <?= htmlspecialchars($meilleur['prenom'].' '.$meilleur['nom']) ?>

                </strong>

                <br>

                Moyenne :
                <?= number_format($meilleur['moyenne'],2) ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>


<!-- 
     MEILLEUR NIVEAU
 -->

<?php if ($action === 'meilleur_niveau'): ?>

<div class="card shadow-sm border-0 col-md-6 mb-4">

    <div class="card-header bg-light fw-bold border-bottom">

        Meilleur étudiant du niveau

    </div>

    <div class="card-body">

        <form method="get" class="mb-4">

            <input type="hidden" name="page" value="etudiants">

            <input type="hidden" name="action" value="meilleur_niveau">

            <select name="id_niveau"
                    class="form-control mb-3"
                    required>

                <option value="">-- Choisir un niveau --</option>

                <?php foreach ($niveaux as $n): ?>

                    <option value="<?= $n['id_niveau'] ?>">

                        <?= htmlspecialchars($n['libelle_niveau']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button class="btn btn-secondary">

                Afficher

            </button>

        </form>

        <?php if ($meilleurNiveau): ?>

            <div class="alert alert-success mb-0">

                <strong>

                    <?= htmlspecialchars($meilleurNiveau['prenom'].' '.$meilleurNiveau['nom']) ?>

                </strong>

                <br>

                Moyenne :
                <?= number_format($meilleurNiveau['moyenne'],2) ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>


<!-- 
     AU DESSUS MOYENNE
 -->

<?php if ($action === 'au_dessus'): ?>

<div class="card shadow-sm border-0 col-md-9 mb-4">

    <div class="card-header bg-light fw-bold border-bottom">

        Étudiants au-dessus de la moyenne

    </div>

    <div class="card-body">

        <form method="get" class="mb-4">

            <input type="hidden" name="page" value="etudiants">

            <input type="hidden" name="action" value="au_dessus">

            <select name="id_classe"
                    class="form-control mb-3"
                    required>

                <option value="">-- Choisir une classe --</option>

                <?php foreach ($classes as $c): ?>

                    <option value="<?= $c['id_classe'] ?>">

                        <?= htmlspecialchars($c['libelle_classe']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button class="btn btn-secondary">

                Afficher

            </button>

        </form>

        <?php if (!empty($auDessus)): ?>

            <table class="table table-hover align-middle">

                <thead class="table-secondary">

                    <tr>

                        <th>Matricule</th>

                        <th>Nom</th>

                        <th>Moyenne</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($auDessus as $e): ?>

                    <tr>

                        <td><?= htmlspecialchars($e['matricule']) ?></td>

                        <td><?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?></td>

                        <td><?= number_format($e['moyenne'],2) ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>