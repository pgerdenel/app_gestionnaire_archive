<?php
/**
 * Supprime un type secondaire dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : type_secondaire = $çPOST['sec']
 * URL: http://localhost/arch/scripts/php/del_sec.php?sec=test
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['sec']) && !empty($_POST['sec'])) {
//if(isset($_GET['sec']) && !empty($_GET['sec'])) {
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion = $connexion->prepare("DELETE FROM type_secondaire_archive WHERE NOM_TYPE_SECONDAIRE_ARCHIVE = :value");
        // affection d'une variable à la valeur du paramètre de la requête
        $connexion->bindParam(':value', $value);
        //$value = $_GET['sec'];
        $value = $_POST['sec'];
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
    echo '<p style="color:red">Erreur de paramètre, la donnée type primaire n\'est pas correcte</p>';
}