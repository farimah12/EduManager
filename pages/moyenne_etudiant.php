<?php
$moyenne = null;
$message = null;
$etudiantSelectionne = null;

$etudiants = getEtudiants(); 

if (isset($_POST['id_etudiant']) && $_POST['id_etudiant'] !== '') {

    $id_etudiant = $_POST['id_etudiant'];

    $etudiantSelectionne = getEtudiantById($id_etudiant);

    $moyenne = getMoyenneEtudiant($id_etudiant);

    if ($moyenne === null) {
        $message = "Cet étudiant n'a pas encore de notes.";
    }
}
?>

<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary">
        <i class="fas fa-calculator me-2"></i>
        
        Calcul de la Moyenne d'un étudiant
    </h1>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold border-bottom">
        <i class="fas fa-user-graduate me-2"></i>
        Calculer la moyenne d’un étudiant
    </div>

    <div class="card-body">

        <form method="post" class="col-md-6">

            <div class="mb-3">
                <label class="fw-semibold">Choisir un étudiant</label>

                <select name="id_etudiant" class="form-control" required>
                    <option value="">-- Sélectionner un étudiant --</option>

                    <?php foreach ($etudiants as $e): ?>
                        <option value="<?= $e['id_etudiant'] ?>"
                            <?= (isset($_POST['id_etudiant']) && $_POST['id_etudiant'] == $e['id_etudiant']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['matricule']) ?> -
                            <?= htmlspecialchars($e['prenom']) ?>
                            <?= htmlspecialchars($e['nom']) ?>
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



<?php if ($etudiantSelectionne): ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-light fw-bold border-bottom">
        Résultat
    </div>

    <div class="card-body">

        <div class="mb-3">
            <h5 class="mb-1">
                <?= htmlspecialchars($etudiantSelectionne['prenom']) ?>
                <?= htmlspecialchars($etudiantSelectionne['nom']) ?>
            </h5>
            <small class="text-muted">
                Matricule : <?= htmlspecialchars($etudiantSelectionne['matricule']) ?>
            </small>
        </div>

        <?php if ($moyenne !== null): ?>
            <div class="alert alert-success mb-0">
                <strong>Moyenne :</strong>
                <?= number_format($moyenne, 2) ?>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-warning mb-0">
                <?= $message ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>