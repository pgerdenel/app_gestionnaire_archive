<?php
/**
 * Renvoie la liste des ID et noms d'entreprise enregistrés dans la BDD
 * Données renvoyées: objet json{"result":"ok", "list":{"1":"conference", "2", "restauration"}}
 * Paramètres obligatoires : aucun
 * URL: http://localhost/arch/scripts/php/get_list_ent_form.php
 **/
require_once('../../../../config/config_bdd.php');

header("Content-type: application/json");

$state = -1;
if(isset($_POST['id']) && !empty($_POST['id'])) {
    try {
        // connexion
        $db = new PDO('mysql:host=' . $PARAM_hote . ';dbname=' . $PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        // préparation de la requête
        $db = $db->prepare("SELECT ID_ENTREPRISE, NOM_ENTREPRISE FROM entreprise");
        // execution de la requête
        $state = $db->execute();
        $list = $db->fetchAll(PDO::FETCH_OBJ);
        //echo 'état de la requête '.$state;
        // print_r($list);
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage() . '<br />';
        echo 'N° : ' . $e->getCode();
        die();
    }

    if (!empty($list)) {
        $array = array("result" => $state, "id"=>$_POST['id'], "list" => $list);
    } else {
        $array = array("result" => false, "list" => $list);
    }
    $list_json = json_encode($array);

    echo $list_json;
}
else {
    echo 'problème de requête PHP, paramètres sans doute incorrect';
}