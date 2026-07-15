<?php
session_start();

require_once "traitements/db.php";

if (isset($_POST['connexion'])) {

    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateurs
            WHERE email = ?
            AND mot_de_passe = ?";

    $requete = $pdo->prepare($sql);

    $requete->execute([$email, $mot_de_passe]);

    $user = $requete->fetch();

    if ($user) {

        $_SESSION['user'] = $user;

        header("Location: index.php?page=dashboard");
        exit;

    } else {

        $erreur = "Email ou mot de passe incorrect";

    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Connexion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">

                    <h4>
                        <i class="fa fa-user"></i>
                        Connexion
                    </h4>

                </div>

                <div class="card-body">

                    <?php if(isset($erreur)): ?>

                        <div class="alert alert-danger">
                            <?= $erreur ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label>Mot de passe</label>

                            <input type="password"
                                   name="mot_de_passe"
                                   class="form-control"
                                   required>

                        </div>

                        <button type="submit"
                                name="connexion"
                                class="btn btn-primary w-100">

                            <i class="fa fa-right-to-bracket"></i>
                            Se connecter

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>