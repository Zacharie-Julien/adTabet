<?php
    function listeUtilisateursControleur($twig, $db) {
        $utilisateurModel = new Utilisateur($db);

        $utilisateurs = $utilisateurModel->getAllUsers();

        echo $twig->render('liste.html.twig', ['utilisateurs' => $utilisateurs]);
    }