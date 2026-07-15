<?php
$niveaux = getNiveaux();
$editNiveau = null;

if (isset($_GET['edit'])) {
    foreach ($niveaux as $n) {
        if ($n['id_niveau'] == $_GET['edit']) {
            $editNiveau = $n;
            break;
        }
    }
}
?>

<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary">
        <i class="fas fa-layer-group me-2"></i>
        
        Gestion des Niveaux
    </h1>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-layer-group me-2"></i>
        <?= $editNiveau ? "Modifier le niveau" : "Ajouter un niveau" ?>
    </div>

    <div class="card-body">
        <form method="post" action="traitements/action.php">

            <?php if ($editNiveau): ?>
                <input type="hidden" name="id_niveau"
                       value="<?= $editNiveau['id_niveau'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="fw-semibold">Libellé</label>
                <input type="text"
                       name="libelle"
                       class="form-control"
                       value="<?= $editNiveau ? htmlspecialchars($editNiveau['libelle_niveau']) : '' ?>"
                       
                       required>
            </div>

            <?php if ($editNiveau): ?>
                <button type="submit"
                        name="updateNiveau"
                        class="btn btn-secondary">
                    Modifier
                </button>

                <a href="index.php?page=niveaux"
                   class="btn btn-outline-secondary ms-2">
                    Annuler
                </a>
            <?php else: ?>
                <button type="submit"
                        name="addNiveau"
                        class="btn btn-secondary">
                    Ajouter
                </button>
            <?php endif; ?>

        </form>
    </div>
</div>



<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-list me-2"></i>
        Liste des niveaux
    </div>

    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Libellé</th>
                    <th width="200">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($niveaux as $n) { ?>
                <tr>
                    <td><?= htmlspecialchars($n['libelle_niveau']) ?></td>

                    <td class="d-flex gap-2">

                        <a href="index.php?page=niveaux&edit=<?= $n['id_niveau'] ?>"
                           class="btn btn-outline-secondary btn-sm">
                            Modifier
                        </a>

                        <a href="traitements/action.php?deleteNiveau=<?= $n['id_niveau'] ?>"
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Supprimer ce niveau ?')">
                            Supprimer
                        </a>

                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>



<div class="card shadow-sm border-0">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-layer-group me-2"></i>
        Liste des niveaux vides
    </div>

    <div class="card-body">

        <?php
        $niveauxSansClasse = getNiveauxSansClasse();

        if (count($niveauxSansClasse) === 0) {
            echo "<p class='text-muted mb-0'>Tous les niveaux ont au moins une classe.</p>";
        } else {
            echo "<ul class='mb-0'>";
            foreach ($niveauxSansClasse as $n) {
                echo "<li>".htmlspecialchars($n['libelle_niveau'])."</li>";
            }
            echo "</ul>";
        }
        ?>

    </div>
</div>