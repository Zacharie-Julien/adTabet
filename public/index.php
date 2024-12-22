<?php
session_start();

require_once '../lib/vendor/autoload.php';  // Charger Twig
require_once '../app/routes/route.php';        // Charger les routes
require_once '../app/controllers/_controleurs.php';  // Charger les contrôleurs
require_once '../app/models/_classes.php';

$loader = new \Twig\Loader\FilesystemLoader('../app/views/');
$twig = new \Twig\Environment($loader, []);
$twig->addGlobal('session', $_SESSION);

// Connexion à la base de données
require_once '../app/config/parametres.php';
require_once '../app/config/connexion.php';
$db = connect($config);
if ($db === null) {
    die("Échec de la connexion à la base de données.");
}

// Obtenir la fonction à appeler
$contenu = getPage($db);

// Appeler dynamiquement la fonction contrôleur
if (is_callable($contenu[0])) {
    call_user_func($contenu[0], $twig, $db);
} else {
    die("Erreur : la fonction contrôleur n'existe pas.");
}
