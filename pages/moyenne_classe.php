<?php
$classes = getClasses();
$moyenne = null;
$message = null;

if (isset($_POST['id_classe'])) {

    $id_classe = $_POST['id_classe'];

    $etudiants = getEtudiantsParClasse($id_classe);

    if (empty($etudiants)) {
        $message = "Cette classe n'a pas encore d'étudiants.";
    } else {
        $moyenne = getMoyenneClasse($id_classe);

        if ($moyenne === null) {
            $message = "Aucune note enregistrée pour cette classe.";
        }
    }
}
?>

<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary">
        <i class="fas fa-chart-bar me-2"></i>
        
        Calcul de la Moyenne d'une classe
    </h1>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-school me-2"></i>
        Calculer la moyenne d’une classe
    </div>

    <div class="card-body">

        <form method="post" class="col-md-6">

            <div class="mb-3">
                <label class="fw-semibold">Classe</label>

                <select name="id_classe" class="form-control" required>
                    <option value="">-- Sélectionner une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id_classe'] ?>"
                            <?= (isset($_POST['id_classe']) && $_POST['id_classe'] == $c['id_classe']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['libelle_classe']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-secondary">
                Calculer
            </button>

        </form>

    </div>
</div>



<?php if ($message): ?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="alert alert-warning mb-0">
            <?= $message ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if ($moyenne !== null): ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-light fw-bold border-bottom">
        Résultat
    </div>
    <div class="card-body">
        <div class="alert alert-success mb-0">
            Moyenne générale de la classe :
            <strong><?= number_format($moyenne, 2) ?></strong>
        </div>
    </div>
</div>
<?php endif; ?>