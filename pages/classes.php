<?php

$classes = getClasses();
$niveaux = getNiveaux();

$action = $_GET['action'] ?? '';
$editClasse = null;

if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($classes as $c) {
        if ($c['id_classe'] == $_GET['id']) {
            $editClasse = $c;
            break;
        }
    }
}
?>

<div class="text-center mb-5">
        <h1 class="fw-bold text-secondary">
            <i class="fas fa-school me-2"></i> 
             Gestion des Classes
        </h1>
    </div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-school me-2"></i> 
        <?= $editClasse ? "Modifier une classe" : "Ajouter une classe" ?>
    </div>

    <div class="card-body">
        <form method="POST" action="traitements/action.php">

            <?php if ($editClasse): ?>
                <input type="hidden" name="id_classe"
                       value="<?= $editClasse['id_classe'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="fw-semibold">Nom de la classe</label>
                <input type="text"
                       name="libelle"
                       class="form-control"
                       value="<?= $editClasse ? htmlspecialchars($editClasse['libelle_classe']) : '' ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Niveau</label>
                <select name="niveau" class="form-control" required>
                    <?php foreach ($niveaux as $n): ?>
                        <option value="<?= $n['id_niveau'] ?>"
                            <?= ($editClasse && $editClasse['id_niveau'] == $n['id_niveau']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($n['libelle_niveau']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <?php if ($editClasse): ?>
                    <button class="btn btn-secondary" name="updateClasse">
                        Modifier
                    </button>

                    <a href="index.php?page=classes"
                       class="btn btn-outline-secondary ms-2">
                       Annuler
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary" name="addClasse">
                        Ajouter
                    </button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>


<div class="card shadow-sm border-0">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-school me-2"></i> 
        Liste des classes
    </div>

    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Classe</th>
                    <th>Niveau</th>
                    <th width="200">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($classes as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['libelle_classe']) ?></td>
                    <td><?= htmlspecialchars($c['libelle_niveau']) ?></td>
                    <td class="d-flex gap-2">

                        <a href="index.php?page=classes&action=edit&id=<?= $c['id_classe'] ?>"
                           class="btn btn-outline-secondary btn-sm">
                           Modifier
                        </a>

                        <a href="traitements/action.php?deleteClasse=<?= $c['id_classe'] ?>"
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Supprimer cette classe ?')">
                           Supprimer
                        </a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>