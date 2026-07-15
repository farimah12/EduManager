<?php
require_once "db.php";
require_once "requetes.php";


if (isset($_POST['addNiveau'])) {

    $libelle = trim($_POST['libelle']);

    $niveauxAutorises = ["Licence1", "Licence2", "Licence3", "Master1", "Master2"];

    if (in_array($libelle, $niveauxAutorises)) {

        addNiveau($libelle);
        header("Location: ../index.php?page=niveaux");
        exit;

    } else {
        echo "Niveau invalide !";
    }
}

if (isset($_POST['updateNiveau'])) {
    updateNiveau($_POST['id_niveau'], $_POST['libelle']);
    header("Location: ../index.php?page=niveaux");
    exit;
}

if (isset($_GET['deleteNiveau'])) {
    deleteNiveau($_GET['deleteNiveau']);
    header("Location: ../index.php?page=niveaux");
    exit;
}



if (isset($_POST['addClasse'])) {
    addClasse($_POST['libelle'], $_POST['niveau']);
    header('Location: ../index.php?page=classes');
    exit;
}

if (isset($_GET['deleteClasse'])) {
    deleteClasse($_GET['deleteClasse']);
    header('Location: ../index.php?page=classes');
    exit;
}

if (isset($_POST['updateClasse'])) {
    updateClasse($_POST['id_classe'], $_POST['libelle'], $_POST['niveau']);
    header("Location: ../index.php?page=classes");
    exit;
}

if (isset($_POST['addEtudiant'])) {

    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $id_classe = $_POST['id_classe'];

    addEtudiant($prenom, $nom, $id_classe);

   header("Location: ../index.php?page=etudiants&action=liste&msg=added");
    exit;
}

if (isset($_POST['updateEtudiant'])) {

    $id = $_POST['id_etudiant'];
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $id_classe = $_POST['id_classe'];

    updateEtudiant($id, $prenom, $nom, $id_classe);

    header("Location: ../index.php?page=etudiants&action=liste&msg=updated");
    exit;
}

if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    if (deleteEtudiant($id)) {
        header("Location: ../index.php?page=etudiants&action=liste&msg=deleted");
    } else {
        header("Location: ../index.php?page=etudiants&action=liste&msg=error");
    }

    exit;
}


if (isset($_POST['addEvaluation'])) {

    $id_etudiant = $_POST['id_etudiant'];
    $id_module   = $_POST['id_module'];
    $type        = $_POST['type'];
    $note        = $_POST['note'];

    addEvaluation($id_etudiant, $id_module, $type, $note);

    header("Location: ../index.php?page=evaluations&action=liste");
    exit;
}
if (isset($_POST['updateEvaluation'])) {
    updateEvaluation(
        $_POST['note'],
        $_POST['type'],
        $_POST['id_etudiant'],
        $_POST['id_module']
    );

    header('Location: ../index.php?page=evaluations&action=liste');
    exit;
}


if (isset($_POST['deleteEvaluation'])) {
    deleteEvaluation(
        $_POST['id_etudiant'],
        $_POST['id_module']
    );

    header('Location: ../index.php?page=evaluations&action=liste');
    exit;
}


if (isset($_POST['addModule'])) {

    $code = trim($_POST['code_module']);
    $libelle = trim($_POST['libelle_module']);

    if ($code !== '' && $libelle !== '') {

        addModule($code, $libelle);

        header("Location: ../index.php?page=modules");
        exit();
    }
}


if (isset($_POST['updateModule'])) {
    updateModule($_POST['id_module'], $_POST['code_module'], $_POST['libelle_module']);
    header("Location: ../index.php?page=modules");
    exit;
}


if (isset($_GET['deleteModule'])) {
    deleteModule($_GET['deleteModule']);
    header("Location: ../index.php?page=modules");
    exit;
}

if (isset($_POST['linkModuleClasse'])) {

    $id_classe = $_POST['id_classe'];
    $id_module = $_POST['id_module'];

    if (!empty($id_classe) && !empty($id_module)) {

        linkModuleClasse($id_classe, $id_module);

        header("Location: ../index.php?page=modules&action=associer");
        exit();
    }
}