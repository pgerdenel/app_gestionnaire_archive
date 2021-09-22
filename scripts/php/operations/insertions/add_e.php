<?php
/**
 * Ajoute un type secondaire dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : type_secondaire = $POST['sec']
 * URL: http://localhost/arch/scripts/php/add_e.php?nom=test&corps=charpenterie&montant=410000
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['corps']) &&
    !empty($_POST['corps'])) {
    /*if(isset($_GET['nom']) && !empty($_GET['nom']) && isset($_GET['corps']) &&
        !empty($_GET['corps']) && isset($_GET['montant']) && !empty($_GET['montant'])) {*/
    $state = -1;
    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // récupération de l'id du corps état
        $nom_corps = $_POST['corps'];
        //$nom_corps = $_GET['corps'];
        $connexion = $connexion->prepare("SELECT ID_CORPS_ETAT FROM corps_etat WHERE NOM_CORPS_ETAT= :nom_corps");
        $state = $connexion->execute(['nom_corps'=>$nom_corps]);
        $id = $connexion->fetch();
        //echo "id recup= ".$id[0];

        $connexion2 = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $connexion2 = $connexion2->prepare("INSERT INTO entreprise(`ID_CORPS_ETAT`, `NOM_ENTREPRISE`) VALUES (:id, :nom)");
        // affection d'une variable à la valeur du paramètre de la requête
        $connexion2->bindParam(':id', $id[0]);
        $connexion2->bindParam(':nom', $_POST['nom']);
        // execution de la requête
        $state = $connexion2->execute();
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
    echo '<p style="color:red">Erreur de paramètre, les paramètres ne sont pas correctes $</p>';
}