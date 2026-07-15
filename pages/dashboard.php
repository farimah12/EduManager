<?php




$nbNiveaux  = count(getNiveaux());
$nbClasses  = count(getClasses());
$nbEtudiants = count(getEtudiants());
$nbModules=count(getModules());

$etudiantsParNiveau = getNombreEtudiantsParNiveau();

$moyennes = getMoyennesToutesClasses();

$admis    = getEtudiantsAdmis();     
$ajournes = getEtudiantsAjournes();  
$exclus   = getEtudiantsExclus();    
?>

<div class="container-fluid px-4">

    <div class="text-center mb-5">

        <h1 class="fw-bold text-secondary display-5">
            <i class="fas fa-chart-line me-2"></i>
            
            Tableau de bord
        </h1>

        <p class="text-muted fs-5">
            Vue générale de la gestion académique
        </p>

        <hr class="w-25 mx-auto opacity-25">

    
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5><i class="fas fa-layer-group me-2"></i>Niveaux</h5>
                    <h2><?= $nbNiveaux ?></h2>
                </div>
                <div class="card-footer">
                    <a class="small text-white" href="index.php?page=niveaux">Voir détails</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5><i class="fas fa-school me-2"></i>Classes</h5>
                    <h2><?= $nbClasses ?></h2>
                </div>
                <div class="card-footer">
                    <a class="small text-white" href="index.php?page=classes">Voir détails</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h5><i class="fas fa-user-graduate me-2"></i>Étudiants</h5>
                    <h2><?= $nbEtudiants ?></h2>
                </div>
                <div class="card-footer">
                    <a class="small text-white" href="index.php?page=etudiants">Voir détails</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <h5><i class="fas fa-book me-2"></i>Modules</h5>
                    <h2><?= $nbModules ?></h2>
                </div>
                <div class="card-footer">
                    <a class="small text-white" href="index.php?page=modules">Voir détails</a>
                </div>
            </div>
        </div>

    </div>

    
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Nombre d’étudiants par niveau
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Niveau</th>
                        <th>Nombre d’étudiants</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiantsParNiveau as $n): ?>
                        <tr>
                            <td><?= htmlspecialchars($n['libelle_niveau']) ?></td>
                            <td><?= $n['total_etudiants'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">Admis</div>
                <div class="card-body text-center">
                    <h2><?= count($admis) ?></h2>
                    <p>Moyenne ≥ 10</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">Ajournés</div>
                <div class="card-body text-center">
                    <h2><?= count($ajournes) ?></h2>
                    <p>5 ≤ Moyenne &lt; 10</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">Exclus</div>
                <div class="card-body text-center">
                    <h2><?= count($exclus) ?></h2>
                    <p>Moyenne &lt; 5</p>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="card mt-4">
    <div class="card-header">
        Moyenne par classe
    </div>
    <div class="card-body">

        <table class="table table-bordered">
            <tr>
                <th>Classe</th>
                <th>Moyenne</th>
            </tr>

            <?php foreach ($moyennes as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['libelle_classe']) ?></td>
                    <td>
                        <?= $c['moyenne'] 
                            ? number_format($c['moyenne'],2) 
                            : 'Aucune note' ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>
</div>