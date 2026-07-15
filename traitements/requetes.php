<?php
include_once "db.php";


function getNiveaux() {
    global $pdo;
    return $pdo->query("SELECT * FROM niveau")->fetchAll();
}

function addNiveau($libelle_niveau) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO niveau(libelle_niveau) VALUES (?)");
    return $stmt->execute([$libelle_niveau]);
}

function updateNiveau($id_niveau, $libelle_niveau) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE niveau SET libelle_niveau = ? WHERE id_niveau = ?");
    return $stmt->execute([$libelle_niveau, $id_niveau]);
}

function deleteNiveau($id_niveau) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM niveau WHERE id_niveau=?");
    return $stmt->execute([$id_niveau]);
}



function getClasses() {
    global $pdo;
    return $pdo->query("SELECT  classe.id_classe,  classe.libelle_classe, classe.id_niveau,  niveau.libelle_niveau FROM classe
            JOIN niveau ON classe.id_niveau = niveau.id_niveau")->fetchAll(PDO::FETCH_ASSOC);
}

function addClasse($libelle, $id_niveau) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO classe (libelle_classe, id_niveau) VALUES (?, ?)");
    return $stmt->execute([$libelle, $id_niveau]);
}

function updateClasse($id_classe, $libelle_classe, $id_niveau) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE classe SET libelle_classe = ?, id_niveau = ? WHERE id_classe = ?");
    return $stmt->execute([$libelle_classe, $id_niveau, $id_classe]);
}

function deleteClasse($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM classe WHERE id_classe = ?");
    return $stmt->execute([$id]);
}

function getNiveauxSansClasse() {
    global $pdo;

    $sql = " SELECT n.id_niveau, n.libelle_niveau FROM niveau n LEFT JOIN classe c ON n.id_niveau = c.id_niveau
        WHERE c.id_classe IS NULL";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}




function getEtudiants()
{
    global $pdo;
    return $pdo->query("SELECT id_etudiant, matricule, prenom, nom, id_classe FROM etudiant")->fetchAll(PDO::FETCH_ASSOC);
}

function addEtudiant($prenom, $nom, $id_classe) {
    global $pdo;
    $annee = date("Y");
    do {
       $numero = random_int(1000, 9999);
       $matricule = "ETU-" . $annee . "-" . $numero;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiant WHERE matricule = ?");
        $stmt->execute([$matricule]);

        $exists = $stmt->fetchColumn();

    } while ($exists > 0); 
   
    $stmt = $pdo->prepare("INSERT INTO etudiant (matricule, prenom, nom, id_classe) VALUES (?, ?, ?, ?)");

    return $stmt->execute([$matricule, $prenom, $nom, $id_classe]);
}

function updateEtudiant($id_etudiant, $prenom, $nom, $id_classe) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE etudiant  SET prenom = ?, nom = ?, id_classe = ?  WHERE id_etudiant = ?");

    return $stmt->execute([$prenom, $nom, $id_classe, $id_etudiant]);
}

function deleteEtudiant($id_etudiant) {
    global $pdo;

    $stmt = $pdo->prepare("DELETE FROM etudiant WHERE id_etudiant = ?");

    return $stmt->execute([$id_etudiant]);
}

function getAllEtudiants() {
    global $pdo;
    return $pdo->query("SELECT id_etudiant, matricule, prenom, nom FROM etudiant ORDER BY prenom")->fetchAll(PDO::FETCH_ASSOC);
}

function getAllModules() {
    global $pdo;
    return $pdo->query("SELECT id_module, code_module FROM module ORDER BY code_module")->fetchAll(PDO::FETCH_ASSOC);
}

function getEtudiantsParClasse($id_classe) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_etudiant, matricule, prenom, nom FROM etudiant WHERE id_classe = ?");
    $stmt->execute([$id_classe]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMeilleurEtudiantClasse($id_classe)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT  matricule,prenom,nom, (SELECT AVG(note) FROM evaluation WHERE evaluation.id_etudiant = etudiant.id_etudiant
            ) AS moyenne FROM etudiant WHERE id_classe = ? AND id_etudiant IN ( SELECT id_etudiant FROM evaluation  )
        ORDER BY moyenne DESC
        LIMIT 1 ");
    $stmt->execute([$id_classe]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getMeilleurEtudiantNiveau($id_niveau)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_classe FROM classe WHERE id_niveau = ?");
    $stmt->execute([$id_niveau]);
    $classes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($classes)) return null;

    $inClasses = implode(',', $classes);
    $stmt = $pdo->query("SELECT id_etudiant FROM etudiant WHERE id_classe IN ($inClasses)" );
    $etudiants = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($etudiants)) return null;

    $inEtudiants = implode(',', $etudiants);

    $best = $pdo->query("SELECT id_etudiant, AVG(note) AS moyenne FROM evaluation WHERE id_etudiant IN ($inEtudiants) GROUP BY id_etudiant
        ORDER BY moyenne DESC LIMIT 1 ")->fetch(PDO::FETCH_ASSOC);

    if (!$best) return null;

    $stmt = $pdo->prepare(" SELECT matricule, prenom, nom FROM etudiant WHERE id_etudiant = ?");
    $stmt->execute([$best['id_etudiant']]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'matricule' => $info['matricule'],
        'prenom'    => $info['prenom'],
        'nom'       => $info['nom'],
        'moyenne'   => $best['moyenne']
    ];
}


function getEtudiantsAuDessusMoyenneClasse($id_classe)
{
    global $pdo;

    $stmtClasse = $pdo->prepare(" SELECT AVG(note)  FROM evaluation  WHERE id_etudiant IN (
            SELECT id_etudiant FROM etudiant WHERE id_classe = ? ) ");
    $stmtClasse->execute([$id_classe]);
    $moyenneClasse = $stmtClasse->fetchColumn();

    if ($moyenneClasse === null) {
        return [];
    }

    $stmt = $pdo->prepare(" SELECT  id_etudiant,  AVG(note) as moyenne FROM evaluation WHERE id_etudiant IN (
            SELECT id_etudiant FROM etudiant WHERE id_classe = ?
        ) GROUP BY id_etudiant HAVING moyenne > ? ");
   
   $stmt->execute([$id_classe, $moyenneClasse]);
    $resultats = $stmt->fetchAll();

     $etudiants = [];
    foreach ($resultats as $r) {
        $stmtE = $pdo->prepare(" SELECT matricule, prenom, nom FROM etudiant  WHERE id_etudiant = ? ");
        $stmtE->execute([$r['id_etudiant']]);
        $e = $stmtE->fetch();

        if ($e) {
            $e['moyenne'] = $r['moyenne'];
            $etudiants[] = $e;
        }
    }

    return $etudiants;
}




function getModules() {
    global $pdo;
   return $pdo->query("SELECT * FROM module")->fetchAll(PDO::FETCH_ASSOC);
}

function addModule($code_module, $libelle_module) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO module (code_module, libelle_module) VALUES (?, ?)");
    return $stmt->execute([$code_module, $libelle_module]);
}

function updateModule($id_module, $code_module, $libelle_module) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE module SET code_module = ?, libelle_module = ?  WHERE id_module = ?");
    return $stmt->execute([$code_module, $libelle_module, $id_module]);
}

function deleteModule($id_module) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM module WHERE id_module = ?");
    return $stmt->execute([$id_module]);
}

function associerModuleClasse($id_module, $id_classe) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO classe_module (id_module, id_classe) VALUES (?, ?)");
    return $stmt->execute([$id_module, $id_classe]);
}

function getModulesParClasse($id_classe) {
    global $pdo;

    $stmt = $pdo->prepare(" SELECT code_module, libelle_module FROM module WHERE id_module IN (
            SELECT id_module FROM classe_module  WHERE id_classe = ? ) ");
    $stmt->execute([$id_classe]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function linkModuleClasse($id_classe, $id_module) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO classe_module (id_classe, id_module) VALUES (?, ?)");
    return $stmt->execute([$id_classe, $id_module]);
}



function getIdEtudiantByMatricule($matricule) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_etudiant FROM etudiant WHERE matricule = ?");
    $stmt->execute([$matricule]);
    return $stmt->fetchColumn();
}


function getIdModuleByCode($code_module) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_module FROM module WHERE code_module = ?");
    $stmt->execute([$code_module]);
    return $stmt->fetchColumn();
}


function addEvaluation($id_etudiant, $id_module, $type, $note) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO evaluation (id_etudiant, id_module, type, note)  VALUES (?, ?, ?, ?)");
    return $stmt->execute([$id_etudiant, $id_module, $type, $note]);
}


function updateEvaluation($note, $type, $id_etudiant, $id_module) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE evaluation SET note = ?, type = ?  WHERE id_etudiant = ? AND id_module = ?");
    return $stmt->execute([$note, $type, $id_etudiant, $id_module]);
}


function deleteEvaluation($id_etudiant, $id_module) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM evaluation WHERE id_etudiant = ? AND id_module = ?");
    return $stmt->execute([$id_etudiant, $id_module]);
}

function getEvaluations() {
    global $pdo;
    return $pdo->query("SELECT * FROM evaluation")->fetchAll(PDO::FETCH_ASSOC);
}

function getMatriculeByIdEtudiant($id_etudiant) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT matricule FROM etudiant WHERE id_etudiant = ?");
    $stmt->execute([$id_etudiant]);
    return $stmt->fetchColumn();
}

function getCodeModuleByIdModule($id_module) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT code_module FROM module WHERE id_module = ?");
    $stmt->execute([$id_module]);
    return $stmt->fetchColumn();
}



function getMoyenneEtudiant($id_etudiant) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(note)  FROM evaluation WHERE id_etudiant = ?  AND type IN ('devoir', 'examen')");
    $stmt->execute([$id_etudiant]);
    return $stmt->fetchColumn();
}

function getMoyenneClasse($id_classe) {
    global $pdo;

    $sql = "SELECT AVG(note) FROM evaluation WHERE type IN ('devoir','examen') AND id_etudiant IN (
                  SELECT id_etudiant FROM etudiant WHERE id_classe = ? )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_classe]);
    return $stmt->fetchColumn();
}

function getEvaluationsParEtudiant($id_etudiant) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM evaluation WHERE id_etudiant = ?");
    $stmt->execute([$id_etudiant]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEtudiantById($id_etudiant)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id_etudiant = ? ");
    $stmt->execute([$id_etudiant]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getClasseById($id_classe)
{
    global $pdo;

    $stmt = $pdo->prepare(" SELECT * FROM classe WHERE id_classe = ?");
    $stmt->execute([$id_classe]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getStatutEtudiants()
{
    global $pdo;

    $sql = "  SELECT  e.id_etudiant, e.matricule, e.prenom, e.nom, (
                SELECT AVG(note) FROM evaluation WHERE id_etudiant = e.id_etudiant AND type != 'tp'
            ) AS moyenne FROM etudiant ";

    $etudiants = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($etudiants as &$e) {
        if ($e['moyenne'] === null) {
            $e['statut'] = 'Aucune note';
        } elseif ($e['moyenne'] >= 10) {
            $e['statut'] = 'Admis';
        } elseif ($e['moyenne'] >= 5) {
            $e['statut'] = 'Ajourné';
        } else {
            $e['statut'] = 'Exclus';
        }
    }

    return $etudiants;
}

function countClasses() {
    global $pdo;
    return $pdo->query("SELECT COUNT(*) FROM classe")->fetchColumn();
}

function countEtudiants() {
    global $pdo;
    return $pdo->query("SELECT COUNT(*) FROM etudiant")->fetchColumn();
}

function getNombreEtudiantsParNiveau() {
    global $pdo;

    $sql = " SELECT  n.libelle_niveau, (
                SELECT COUNT(*) FROM etudiant e WHERE e.id_classe IN (
                    SELECT c.id_classe FROM classe c WHERE c.id_niveau = n.id_niveau
                )
            ) AS total_etudiants FROM niveau n ";

    return $pdo->query($sql)->fetchAll();
}

function getEtudiantsAdmis()
{
    global $pdo;

    $sql = " SELECT id_etudiant, matricule, prenom, nom FROM etudiant WHERE (
            SELECT AVG(note) FROM evaluation WHERE evaluation.id_etudiant = etudiant.id_etudiant AND type != 'tp'
        ) >= 10 ";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getEtudiantsAjournes()
{
    global $pdo;

    $sql = " SELECT id_etudiant, matricule, prenom, nom FROM etudiant WHERE (
            SELECT AVG(note) FROM evaluation WHERE evaluation.id_etudiant = etudiant.id_etudiant AND type != 'tp'
        ) >= 5 AND (
            SELECT AVG(note) FROM evaluation WHERE evaluation.id_etudiant = etudiant.id_etudiant  AND type != 'tp'
        ) < 10 ";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getEtudiantsExclus()
{
    global $pdo;

    $sql = " SELECT id_etudiant, matricule, prenom, nom FROM etudiant WHERE (
            SELECT AVG(note)  FROM evaluation WHERE evaluation.id_etudiant = etudiant.id_etudiant AND type != 'tp'
        ) < 5 ";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


function getMoyennesToutesClasses() {
    global $pdo;

    $sql = "SELECT c.id_classe, c.libelle_classe, ( 
    SELECT AVG(note) FROM evaluation WHERE type IN ('devoir','examen') AND id_etudiant IN (
                SELECT id_etudiant  FROM etudiant   WHERE id_classe = c.id_classe
            )
        ) AS moyenne
        FROM classe c ";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

 
function genererBulletinPDF($id_etudiant)
{
    require_once __DIR__ . '/../fpdf/fpdf.php';

    $etudiant = getEtudiantById($id_etudiant);
    if (!$etudiant) {
        exit('Étudiant introuvable');
    }

    $classe = getClasseById($etudiant['id_classe']);
    $evaluations = getEvaluationsParEtudiant($id_etudiant);
    $moyenne = getMoyenneEtudiant($id_etudiant);

    $pdf = new FPDF();
    $pdf->AddPage();

   
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'BULLETIN DE NOTES',0,1,'C');
    $pdf->Ln(5);

    
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Nom : '.$etudiant['nom'],0,1);
    $pdf->Cell(0,8,'Prenom : '.$etudiant['prenom'],0,1);
    $pdf->Cell(0,8,'Matricule : '.$etudiant['matricule'],0,1);
    $pdf->Cell(0,8,'Classe : '.$classe['libelle_classe'],0,1);
    $pdf->Ln(5);

    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(60,8,'Module',1);
    $pdf->Cell(60,8,'Type',1);
    $pdf->Cell(40,8,'Note',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',12);

    foreach ($evaluations as $e) {
        if ($e['type'] !== 'tp') {
            $codeModule = getCodeModuleByIdModule($e['id_module']);

            $pdf->Cell(60,8,$codeModule,1);
            $pdf->Cell(60,8,ucfirst($e['type']),1);
            $pdf->Cell(40,8,$e['note'],1);
            $pdf->Ln();
        }
    }

    $pdf->Ln(5);

    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Moyenne generale : '.number_format($moyenne,2),0,1);

    if ($moyenne >= 10) {
        $decision = "ADMIS";
    } elseif ($moyenne >= 5) {
        $decision = "AJOURNE";
    } else {
        $decision = "EXCLU";
    }

    $pdf->Cell(0,10,'Decision : '.$decision,0,1);
    $pdf->Ln(10);
    $pdf->Cell(0,10,'Fait le : '.date('d/m/Y'),0,1);

    $pdf->Output('I', 'bulletin.pdf');
    exit;
}



function predireRisqueEtudiant($id_etudiant)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT AVG(note) as moyenne
        FROM evaluation
        WHERE id_etudiant = ?
        AND type != 'tp'
    ");

    $stmt->execute([$id_etudiant]);

    $moyenne = $stmt->fetchColumn();

    if ($moyenne === null) {

        return [

            'niveau' => 'Données insuffisantes',

            'couleur' => 'secondary',

            'message' => 'Impossible de faire une prédiction'

        ];
    }

    if ($moyenne >= 12) {

        return [

            'niveau' => 'Faible risque',

            'couleur' => 'success',

            'message' => 'Performance académique stable'

        ];
    }

    elseif ($moyenne >= 8) {

        return [

            'niveau' => 'Risque modéré',

            'couleur' => 'warning',

            'message' => 'L’étudiant nécessite un suivi'

        ];
    }

    else {

        return [

            'niveau' => 'Risque élevé de décrochage',

            'couleur' => 'danger',

            'message' => 'Intervention pédagogique recommandée'

        ];
    }
}


function getModuleFaible($id_etudiant)
{
    global $pdo;

    $stmt = $pdo->prepare("

        SELECT
            module.libelle_module,
            AVG(evaluation.note) as moyenne

        FROM evaluation

        JOIN module
        ON evaluation.id_module = module.id_module

        WHERE evaluation.id_etudiant = ?

        GROUP BY module.id_module

        ORDER BY moyenne ASC

        LIMIT 1

    ");

    $stmt->execute([$id_etudiant]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}