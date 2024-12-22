<?php

function uploadControleur($twig, $db) {

    $utilisateur = new Utilisateur($db);
    $listeUtilisateur = $utilisateur->getAllUsers();
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $adUtilisateur = new adUtilisateur($file, $listeUtilisateur);
        $listeAdUtilisateur = $adUtilisateur->convertCsv()[0];
        $listeBDDUtilisateur = $adUtilisateur->convertCsv()[1];
        
        echo $twig->render('compare.html.twig', array('utilisateurs'=>$listeAdUtilisateur, 'bddUtilisateurs'=>$listeBDDUtilisateur));
    } else {
        echo $twig->render('compare.html.twig');
    }

    
    
}