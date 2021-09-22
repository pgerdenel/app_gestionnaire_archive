<?php
/**
 * Supprime un proprio dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : nom_proprio = $POST['prop']
 * URL: http://localhost/arch/scripts/php/del_prop.php?edi=thl
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['prop']) && !empty($_POST['prop'])) {
//if(isset($_GET['prop']) && !empty($_GET['prop'])) {
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion = $connexion->prepare("DELETE FROM proprietaire WHERE NOM_PROPRIETAIRE = :value");
        // affection d'une variable à la valeur du paramètre de la requête
        $connexion->bindParam(':value', $value);
        //$value = $_GET['prop'];
        $value = $_POST['prop'];
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
    echo '<p style="color:red">Erreur de paramètre, la donnée nom proprio n\'est pas correcte</p>';
}