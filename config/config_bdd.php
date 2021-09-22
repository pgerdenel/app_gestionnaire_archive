<?php
try {
    $PARAM_hote = '***'; // le chemin vers le serveur
    $PARAM_port = '3306';
    $PARAM_nom_bd = 'archive'; // le nom de votre base de données
    $PARAM_utilisateur = '*****'; // nom d'utilisateur pour se connecter
    $PARAM_mot_passe = '****'; // mot de passe de l'utilisateur pour se connecter
    $connexion = new PDO('mysql:host=' . $PARAM_hote . ';port=' . $PARAM_port . ';dbname=' . $PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
}
catch(Exception $e) {
    //echo 'Erreur : '.$e->getMessage().'<br />';
    //echo 'N° : '.$e->getCode();
    if ($e->getCode() == 1049) {
        echo json_encode(array("result"=>false, "message"=>"Serveur de base de données non disponible"));
    }
    die();
}
?>