<?php
    function ajouterUtilisateurControleur($twig, $db){
        $utilisateurModel = new Utilisateur($db);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $nom_utilisateur = $_GET['nom_utilisateur'] ?? '';
            $nom = $_GET['nom'] ?? '';
            $pNom = $_GET['pNom'] ?? '';
            $mdp = password_hash($_GET['mdp'], PASSWORD_DEFAULT); 
            $mail = $_GET['mail'] ?? '';
            $id_role = $_GET['id_role'] ?? null;
            $id_applis = $_GET['id_appli'] ?? null;

            if ($nom_utilisateur && $nom && $pNom && $mdp && $mail && $id_role && $id_applis) {
                $utilisateurModel->insert($nom_utilisateur, $nom, $pNom, $mdp, $mail, $id_role, $id_applis);

                header('Location: index.php?page=upload');
                exit();
            } else {
                echo $nom_utilisateur, $nom, $pNom, $mdp, $mail, $id_role, $id_applis;

                echo 'Ca marche pas';
            }
        }
    }