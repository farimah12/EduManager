<?php
$action  = $_GET['action'] ?? 'liste';
$classes = getClasses();
$modules = getModules();
$editModule = null;

if ($action === 'edit' && isset($_GET['id'])) {
    foreach ($modules as $m) {
        if ($m['id_module'] == $_GET['id']) {
            $editModule = $m;
            break;
        }
    }
}

$modulesParClasse = [];
if ($action === 'classe' && !empty($_GET['id_classe'])) {
    $modulesParClasse = getModulesParClasse($_GET['id_classe']);
}
?>

<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary">
        <i class="fas fa-book me-2"></i>
        
        Gestion des Modules
    </h1>
</div>

<div class="container mt-4">

    
    <ul class="nav nav-pills mb-4 shadow-sm rounded-4 p-3 bg-light">
        <li class="nav-item">
            <a class="nav-link text-dark <?= ($action === 'add' || $action === 'edit') ? 'active fw-bold bg-secondary text-white' : '' ?>"
               href="index.php?page=modules&action=add">
               <i class="fas fa-book me-2"></i>
                 Ajouter module
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-dark <?= ($action === 'associer') ? 'active fw-bold bg-secondary text-white' : '' ?>"
               href="index.php?page=modules&action=associer">
               <i class="fas fa-school me-2"></i>
                Associer à une classe
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-dark <?= ($action === 'classe') ? 'active fw-bold bg-secondary text-white' : '' ?>"
               href="index.php?page=modules&action=classe">
               <i class="fas fa-book me-2"></i>
                Modules par classe
            </a>
        </li>
    </ul>



   
    <?php if ($action === 'add' || $action === 'edit'): ?>

    <div class="card shadow-sm border-0 rounded-4 col-md-6">
        <div class="card-header bg-light text-dark fw-bold border-bottom">
            <i class="fas fa-book me-2"></i>
            <?= $editModule ? 'Modifier le module' : 'Ajouter un module' ?>
        </div>

        <div class="card-body p-4">
            <form method="post" action="traitements/action.php">

                <?php if ($editModule): ?>
                    <input type="hidden" name="id_module" value="<?= $editModule['id_module'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="fw-semibold">Code module</label>
                    <input type="text"
                           name="code_module"
                           class="form-control"
                           value="<?= $editModule ? htmlspecialchars($editModule['code_module']) : '' ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold">Libellé module</label>
                    <input type="text"
                           name="libelle_module"
                           class="form-control"
                           value="<?= $editModule ? htmlspecialchars($editModule['libelle_module']) : '' ?>"
                           required>
                </div>

                <?php if ($editModule): ?>
                    <button class="btn btn-secondary fw-semibold" name="updateModule">
                        <i class="bi bi-pencil"></i> Modifier
                    </button>
                    <a href="index.php?page=modules" class="btn btn-outline-secondary">Annuler</a>
                <?php else: ?>
                    <button class="btn btn-secondary fw-semibold" name="addModule">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </button>
                <?php endif; ?>

            </form>
        </div>
    </div>

    <?php endif; ?>



    
    <?php if ($action === 'liste'): ?>

    <div class="card shadow-sm border-0 rounded-4 col-md-9">
        <div class="card-header bg-light text-dark fw-bold border-bottom">
           <i class="fas fa-list me-2"></i>
            Liste des modules
        </div>

        <div class="card-body">

            <table class="table table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>Code</th>
                        <th>Libellé</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($modules as $m): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($m['code_module']) ?></td>
                            <td><?= htmlspecialchars($m['libelle_module']) ?></td>
                            <td class="d-flex justify-content-center gap-2">

                                <a href="index.php?page=modules&action=edit&id=<?= $m['id_module'] ?>"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                    Modifier
                                </a>

                                <a href="traitements/action.php?deleteModule=<?= $m['id_module'] ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Supprimer ce module ?')">
                                    <i class="bi bi-trash"></i>
                                    Supprimer
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

    <?php endif; ?>



    
    <?php if ($action === 'associer'): ?>

    <div class="card shadow-sm border-0 rounded-4 col-md-5">
        <div class="card-header bg-light text-dark fw-bold border-bottom">
            <i class="fas fa-school me-2"></i>
            Associer un module à une classe
        </div>

        <div class="card-body p-4">

            <form method="post" action="traitements/action.php">

                <div class="mb-3">
                    <label class="fw-semibold">Classe</label>
                    <select name="id_classe" class="form-control" required>
                        <option value="">-- Choisir une classe --</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id_classe'] ?>">
                                <?= htmlspecialchars($c['libelle_classe']) ?>
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
                                <?= htmlspecialchars($m['code_module']) ?> — <?= htmlspecialchars($m['libelle_module']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-secondary fw-semibold" name="linkModuleClasse">
                    <i class="bi bi-link"></i> Associer
                </button>

            </form>
        </div>
    </div>

    <?php endif; ?>



    
    <?php if ($action === 'classe'): ?>

    <div class="card shadow-sm border-0 rounded-4 col-md-9">
        <div class="card-header bg-light text-dark fw-bold border-bottom">
            <i class="fas fa-book me-2"></i>
            Modules par classe
        </div>

        <div class="card-body">

            <form method="get" class="col-md-5 mb-4">
                <input type="hidden" name="page" value="modules">
                <input type="hidden" name="action" value="classe">

                <label class="fw-semibold">Classe</label>
                <select name="id_classe" class="form-control" required>
                    <option value="">-- Choisir une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id_classe'] ?>"
                            <?= (isset($_GET['id_classe']) && $_GET['id_classe'] == $c['id_classe']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['libelle_classe']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class="btn btn-secondary mt-3 fw-semibold">
                    <i class="bi bi-search"></i> Afficher
                </button>
            </form>

            <?php if (!empty($modulesParClasse)): ?>
                <table class="table table-hover align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th>Code</th>
                            <th>Libellé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modulesParClasse as $m): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($m['code_module']) ?></td>
                                <td><?= htmlspecialchars($m['libelle_module']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (isset($_GET['id_classe'])): ?>
                <div class="alert alert-secondary mt-3">
                    Aucun module pour cette classe.
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php endif; ?>

</div>