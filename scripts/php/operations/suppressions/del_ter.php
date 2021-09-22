<?php
/**
 * Supprime un type tertiaire dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : type_tertiaire = $POST['ter']
 * URL: http://localhost/arch/scripts/php/del_ter.php?ter=test
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['ter']) && !empty($_POST['ter'])) {
//if(isset($_GET['ter']) && !empty($_GET['ter'])) {
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion = $connexion->prepare("DELETE FROM type_tertiaire_archive WHERE NOM_TYPE_TERTIAIRE_ARCHIVE = :value");
        // affection d'une variable à la valeur du paramètre de la requête
        $connexion->bindParam(':value', $value);
        //$value = $_GET['ter'];
        $value = $_POST['ter'];
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