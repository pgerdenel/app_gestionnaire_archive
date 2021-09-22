<?php
/**
 * Renvoie la liste des coprs états enregistrés dans la BDD
 * Données renvoyées: objet json{"result":"ok", "list":["", "", ""]}
 * {"result":true,"list":["eglise","maconnerie", "charpenterie"]}
 * Paramètres obligatoires : aucun
 * URL: http://localhost/arch/scripts/php/get_ce.php
 **/
require_once('../../../../config/config_bdd.php');

header("Content-type: application/json");

$state = -1;
try
{
    // connexion
    $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
    // préparation de la requête
    $connexion = $connexion->prepare("SELECT nom_corps_etat FROM corps_etat");
    // execution de la requête
    $state = $connexion->execute();
    $list = $connexion->fetchAll(PDO::FETCH_COLUMN);
    //echo 'état de la requête '.$state;
}

catch(Exception $e)
{
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
    die();
}
if(!empty($list)) {
    $array=array("result"=>$state, "list"=>$list);
}
else {
    $array=array("result"=>false, "list"=>$list);
}

$list_json = json_encode($array);

echo $list_json;