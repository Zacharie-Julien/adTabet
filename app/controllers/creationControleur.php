<?php
    function creationControleur($twig, $db) {
        $utilisateurModel = new Utilisateur($db);
        $roleModel = new Role($db);
        $appliModel = new Appli($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_utilisateur = $_POST['nom_utilisateur'] ?? '';
            $nom = $_POST['nom'] ?? '';
            $pNom = $_POST['pNom'] ?? '';
            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); 
            $mail = $_POST['mail'] ?? '';
            $id_role = $_POST['id_role'] ?? null;
            $id_appli = $_POST['id_appli'] ?? null;

            if ($nom_utilisateur && $nom && $pNom && $mdp && $mail && $id_role && $id_appli) {
                $utilisateurModel->insert($nom_utilisateur, $nom, $pNom, $mdp, $mail, $id_role, $id_appli);

                header('Location: index.php?page=liste');
                exit();
            }
        } else {
            $roles = $roleModel->getAllRoles();
            $applis = $appliModel->getAllApplis();

            echo $twig->render('creation.html.twig', ['roles' => $roles, 'applis' => $applis]);
        }
    }

