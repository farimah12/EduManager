<?php

session_start();

require_once "traitements/db.php";
require_once "traitements/requetes.php";

// Bloque l'accès si personne n'est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

$page = $_GET['page'] ?? 'dashboard';


if ($page === 'bulletin' && isset($_GET['id_etudiant'])) {
    genererBulletinPDF((int) $_GET['id_etudiant']);
    exit;
}

$file = "pages/" . $page . ".php";


include "includes/header.php";
include "includes/navbar.php";
include "includes/sidebar.php";


if (file_exists($file)) {
    include $file;
} else {
    include "pages/erreur404.php";
}


include "includes/footer.php";