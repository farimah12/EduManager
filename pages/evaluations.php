<?php
$action = $_GET['action'] ?? 'liste';

$evaluations = [];
$evaluation  = null;

$etudiants = getAllEtudiants();
$modules   = getAllModules();

if ($action === 'liste') {
    $evaluations = getEvaluations();
}

if (
    $action === 'edit'
    && isset($_GET['id_etudiant'], $_GET['id_module'])
) {
    foreach (getEvaluations() as $e) {
        if (
            $e['id_etudiant'] == $_GET['id_etudiant']
            && $e['id_module'] == $_GET['id_module']
        ) {
            $evaluation = $e;
            break;
        }
    }
}
?>

<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary">
        <i class="fas fa-file-signature me-2"></i>
        
        Gestion des Évaluations
    </h1>
</div>

<ul class="nav nav-pills mb-4 shadow-sm rounded p-3 bg-light">

    <li class="nav-item">
        <a class="nav-link text-dark <?= $action === 'add' ? 'active bg-secondary border-0' : '' ?>"
           href="index.php?page=evaluations&action=add">
           <i class="fas fa-file-signature me-2"></i>
            Ajouter évaluation
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-dark <?= $action === 'liste' ? 'active bg-secondary border-0' : '' ?>"
           href="index.php?page=evaluations&action=liste">
           <i class="fas fa-list me-2"></i>
             Liste évaluations
        </a>
    </li>

</ul>


<?php if ($action === 'add'): ?>
<div class="card shadow-sm border-0 col-md-6 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        Ajouter une évaluation
    </div>
    <div class="card-body">

        <form method="post" action="traitements/action.php">

            <div class="mb-3">
                <label class="fw-semibold">Étudiant</label>
                <select name="id_etudiant" class="form-control" required>
                    <option value="">-- Choisir un étudiant --</option>
                    <?php foreach ($etudiants as $et): ?>
                        <option value="<?= $et['id_etudiant'] ?>">
                            <?= htmlspecialchars($et['matricule'].' - '.$et['prenom'].' '.$et['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Module</label>
                <select name="id_module" class="form-control" required>
                    <option value="">-- Choisir un module --</option>
                    <?php foreach ($modules as $m): ?>
                        <option value="<?= $m['id_module'] ?>">
                            <?= htmlspecialchars($m['code_module']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Type</label>
                <select name="type" class="form-control">
                    <option value="devoir">Devoir</option>
                    <option value="examen">Examen</option>
                    <option value="tp">TP</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Note</label>
                <input type="number" name="note" min="0" max="20" step="0.01"
                       class="form-control" required>
            </div>

            <button class="btn btn-secondary" name="addEvaluation">
                Enregistrer
            </button>

        </form>
    </div>
</div>
<?php endif; ?>


<?php if ($action === 'edit' && $evaluation): ?>
<div class="card shadow-sm border-0 col-md-6 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-file-signature me-2"></i>
        Modifier évaluation
    </div>
    <div class="card-body">

        <form method="post" action="traitements/action.php">

            <input type="hidden" name="id_etudiant"
                   value="<?= $evaluation['id_etudiant'] ?>">
            <input type="hidden" name="id_module"
                   value="<?= $evaluation['id_module'] ?>">

            <div class="mb-3">
                <label class="fw-semibold">Note</label>
                <input type="number" name="note" min="0" max="20" step="0.01"
                       value="<?= $evaluation['note'] ?>"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Type</label>
                <select name="type" class="form-control">
                    <option value="devoir" <?= $evaluation['type']=='devoir'?'selected':'' ?>>Devoir</option>
                    <option value="examen" <?= $evaluation['type']=='examen'?'selected':'' ?>>Examen</option>
                    <option value="tp" <?= $evaluation['type']=='tp'?'selected':'' ?>>TP</option>
                </select>
            </div>

            <button class="btn btn-secondary" name="updateEvaluation">
                Modifier
            </button>

            <a href="index.php?page=evaluations&action=liste"
               class="btn btn-outline-secondary">
               Annuler
            </a>

        </form>
    </div>
</div>
<?php endif; ?>


<?php if ($action === 'liste'): ?>
<div class="card shadow-sm border-0 col-md-10 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-file-signature me-2"></i>
        Liste des évaluations
    </div>
    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead class="table-secondary">
            <tr>
                <th>Matricule</th>
                <th>Module</th>
                <th>Type</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <?php if (!empty($evaluations)): ?>
            <?php foreach ($evaluations as $e): ?>
            <tr>
                <td><?= getMatriculeByIdEtudiant($e['id_etudiant']) ?></td>
                <td><?= getCodeModuleByIdModule($e['id_module']) ?></td>
                <td><?= $e['type'] ?></td>
                <td><?= $e['note'] ?></td>
                <td class="d-flex gap-2">

                    <a class="btn btn-outline-secondary btn-sm"
                       href="index.php?page=evaluations&action=edit&id_etudiant=<?= $e['id_etudiant'] ?>&id_module=<?= $e['id_module'] ?>">
                        <i class="bi bi-pencil"></i>
                        Modifier
                    </a>

                    <form method="post" action="traitements/action.php"
                          style="display:inline"
                          onsubmit="return confirm('Supprimer cette évaluation ?');">
                        <input type="hidden" name="id_etudiant" value="<?= $e['id_etudiant'] ?>">
                        <input type="hidden" name="id_module" value="<?= $e['id_module'] ?>">
                        <button class="btn btn-outline-danger btn-sm" name="deleteEvaluation">
                            <i class="bi bi-trash"></i>
                            Supprimer
                        </button>
                    </form>

                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Aucune évaluation enregistrée
                </td>
            </tr>
            <?php endif; ?>

            </tbody>
        </table>

    </div>
</div>
<?php endif; ?>