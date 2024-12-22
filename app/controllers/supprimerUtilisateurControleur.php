<?php
    function supprimerUtilisateurControleur($twig, $db) {
        $utilisateurModel = new Utilisateur($db);
        $id_utilisateur = $_GET['id'] ?? null;

        if ($id_utilisateur && is_numeric($id_utilisateur)) {
            $utilisateur = $utilisateurModel->getUserById($id_utilisateur);
            $applicationsAssociees = $utilisateurModel->getApplicationsByUserId($id_utilisateur);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_appli = $_POST['id_appli'] ?? null;

                if ($id_appli === 'toutes') {
                    // Supprimer l'utilisateur de toutes les applications et la base
                    $utilisateurModel->delete($id_utilisateur);
                    header('Location: index.php?page=liste');
                    exit();
                }

                if ($id_appli) {
                    // Supprimer l'association de l'application
                    $utilisateurModel->deleteApplicationForUser($id_utilisateur, $id_appli);

                    // Vérifier s'il reste d'autres applications
                    $remainingApplications = $utilisateurModel->countApplicationsForUser($id_utilisateur);

                    if ($remainingApplications == 0) {
                        // S'il n'en reste aucune, supprimer l'utilisateur
                        $utilisateurModel->delete($id_utilisateur);
                    }

                    header('Location: index.php?page=liste');
                    exit();
                }
            }

            echo $twig->render('supprimer.html.twig', [
                'utilisateur' => $utilisateur,
                'applicationsAssociees' => $applicationsAssociees,
            ]);
        }
    }
