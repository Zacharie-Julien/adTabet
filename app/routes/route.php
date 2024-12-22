<?php
function getPage($db)
{

    $lesPages = [
        'accueil' => "accueilControleur;0",
        'creation' => "creationControleur;0",
        'insertUser' => "creationControleur;0",
        'liste' => "listeUtilisateursControleur;0",
        'supprime' => "supprimerUtilisateurControleur;0",
        'upload' => "uploadControleur;0",
        'ajout' => "ajouterUtilisateurControleur;1"
    ];

    if ($db != null) {
        $page = $_GET['page'] ?? 'accueil';

        if (isset($lesPages[$page])) {
            $explose = explode(";", $lesPages[$page]);
            $contenu = $explose[0];
            return [$contenu, null]; 
        } else {
            return ["accueilControleur", null]; 
        }
    }
}
?>
