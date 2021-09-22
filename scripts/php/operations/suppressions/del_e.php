<?php
/**
 * Supprime une entreprise dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : nom_entreprise = $POST['nom']
 * URL: http://localhost/arch/scripts/php/del_e.php?nom=charpentierPM
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['nom']) && !empty($_POST['nom'])) {
    /*if(isset($_GET['e']) && !empty($_GET['e'])) {*/
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion = $connexion->prepare("DELETE FROM entreprise WHERE NOM_ENTREPRISE = :value");
        // affection d'une variable à la valeur du paramètre de la requête
        $value = $_POST['nom'];
        //$value = $_GET['e'];
        $connexion->bindParam(':value', $value);
        // execution de la requête
        $state = $connexion->execute();
        //echo 'état de la requête '.$state;
    }

    catch(Exception $e)
    {
        echo 'Erreur : '.$e->getMessage().'<br />';
        echo 'N° : '.$e->getCode();
        die();
    }


    if($state == 1) {
        echo "true";
    }
    else {
        echo "false";
    }
}
else {
    echo '<p style="color:red">Erreur de paramètre, la donnée nom_entreprise n\'est pas correcte</p>';
}