<?php
/**
 * Supprime un corps état dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : corps_etat = $POST['ce']
 * URL: http://localhost/arch/scripts/php/del_ce.php?ce=test
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['ce']) && !empty($_POST['ce'])) {
/*if(isset($_GET['ce']) && !empty($_GET['ce'])) {*/
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion = $connexion->prepare("DELETE FROM corps_etat WHERE NOM_CORPS_ETAT = :value");
        // affection d'une variable à la valeur du paramètre de la requête
        $connexion->bindParam(':value', $value);
        //$value = $_GET['ce'];
        $value = $_POST['ce'];
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
    echo '<p style="color:red">Erreur de paramètre, la donnée nom corps état n\'est pas correcte</p>';
}