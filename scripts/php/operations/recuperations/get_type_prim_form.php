<?php
/**
 * Renvoie la liste des ID et noms de type primaire enregistrés dans la BDD
 * Données renvoyées: objet json{"result":"ok", "list":{"1":"famille", "2", "pro"}}
 * Paramètres obligatoires : aucun
 * URL: http://localhost/arch/scripts/php/get_type_prim_form.php
 **/
require_once('../../../../config/config_bdd.php');

header("Content-type: application/json");

$state = -1;
try
{
    // connexion
    $db = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
    // préparation de la requête
    $db = $db->prepare("SELECT ID_TYPE_PRIMAIRE_ARCHIVE, NOM_TYPE_PRIMAIRE_ARCHIVE FROM type_primaire_archive");
    // execution de la requête
    $state = $db->execute();
    $list = $db->fetchAll(PDO::FETCH_OBJ);
    //echo 'état de la requête '.$state;
    // print_r($list);
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