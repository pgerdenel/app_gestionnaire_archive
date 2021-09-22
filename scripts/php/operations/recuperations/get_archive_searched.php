<?php
/**
 * Renvoie la liste archives correspondantes en BDD
 * Données renvoyées: objet json{"result":"ok", "archive":["nom", "nom", ""]}
 * Paramètres obligatoires : crit_search
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=n_arch&nom_arch=test_nom_arch
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=n_edi&nom_edi=test_nom_edi
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=p_arch&prop_arch=3
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=t1_arch&t1_arch=1
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=t2_arch&t2_arch=2
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=t3_arch&t3_arch=8,7,3
 * URL: http://localhost/arch/scripts/php/operations/recuperations/get_archive_searched.php?crit_name=an_arch&an_arch=2019
 **/
require_once('../../../../config/config_bdd.php');
require_once('../pdo_function.php');
header("Content-type: application/json");

$state = -1;
$crit_search = null;
$full_data_archive = array();
$full_data_archive['result'] = false;
$full_data_archive['message'] = '';
$full_data_archive['archive'] = array();

/*if(isset($_POST['crit_name']) && !empty($_POST['crit_name'])) {*/
if(isset($_POST['crit_name']) && !empty($_POST['crit_name'])) {
    $crit_search = $_POST['crit_name'];
    // $crit_search = $_POST['crit_name'];

    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

        switch ($crit_search) {
            case "t_edi":
                if(isset($_POST['t_edi']) && !empty($_POST['t_edi'])) {
                    //echo "prop_arch= ".$_POST['prop_arch']."<br/>";
                    $tmp_c_edi = get_all_id_archive_of_type_edi($connexion, $_POST['t_edi']);
                    if($tmp_c_edi["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_c_edi["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_c_edi["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce type d'édifice";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre type d'édifice n'est pas correct";
                }
                break;
            case "c_edi":
                if(isset($_POST['c_edi']) && !empty($_POST['c_edi'])) {
                    //echo "prop_arch= ".$_POST['prop_arch']."<br/>";
                    $tmp_c_edi = get_all_id_archive_of_commune($connexion, $_POST['c_edi']);
                    if($tmp_c_edi["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_c_edi["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_c_edi["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à cette commune";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre commune n'est pas correct";
                }
                break;
            case "d_edi":
                if(isset($_POST['d_edi']) && !empty($_POST['d_edi'])) {
                    //echo "prop_arch= ".$_POST['prop_arch']."<br/>";
                    $tmp_d_edi = get_all_id_archive_of_departement($connexion, $_POST['d_edi']);
                    if($tmp_d_edi["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_d_edi["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_d_edi["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce département";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre département n'est pas correct";
                }
                break;
            case "p_edi":
                if(isset($_POST['p_edi']) && !empty($_POST['p_edi'])) {
                    //echo "prop_arch= ".$_POST['prop_arch']."<br/>";
                    $tmp_p_edi = get_all_id_archive_of_nom_prop_edi($connexion, $_POST['p_edi']);
                    if($tmp_p_edi["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_p_edi["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_p_edi["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce nom de propriétaire d'édifice";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de propriétaire d'édifice n'est pas correct";
                }
                break;
            case "n_arch":
                if(isset($_POST['nom_arch']) && !empty($_POST['nom_arch'])) {
                // if(isset($_POST['nom_arch']) && !empty($_POST['nom_arch'])) { // si le nom de l'archive existe
                    // echo "nom_arch= ".$_POST['nom_arch']."<br/>";
                    $tmp_n_arch = check_archive_exist_by_nom_archive($connexion, $_POST['nom_arch']);
                    // $tmp_n_arch = check_archive_exist_by_nom_archive($connexion, $_POST['nom_arch']);
                    if($tmp_n_arch["result"]) {
                        $full_data_archive['result'] = true;
                        $full_data_archive['archive'][0] = get_archive_data($connexion, $tmp_n_arch["id"]);
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce nom d'archive";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom_archive est incorrect";
                }
                break;
            case "n_edi":
                if(isset($_POST['nom_edi']) && !empty($_POST['nom_edi'])) {
                    $tmp_n_edi = check_archive_exist_by_nom_edifice($connexion, $_POST['nom_edi']);
                    if($tmp_n_edi["result"]) {
                        $full_data_archive['result'] = true;
                        $full_data_archive['archive'][0] = get_archive_data($connexion, $tmp_n_edi['id']);
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce nom nom d'édifice";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom_edificei n'est pas correct";
                }
                break;
            case "p_arch":
                if(isset($_POST['prop_arch']) && !empty($_POST['prop_arch'])) {
                    //echo "prop_arch= ".$_POST['prop_arch']."<br/>";
                    $tmp_p_arch = get_all_id_archive_of_id_prop_arch($connexion, $_POST['prop_arch']);
                    if($tmp_p_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_p_arch["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_p_arch["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce nom de propriétaire";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de propriétaire n'est pas correct";
                }
                break;
            case "t1_arch":
                if(isset($_POST['t1_arch']) && !empty($_POST['t1_arch'])) {
                    $tmp_p_arch = get_all_id_archive_of_type_primaire($connexion, $_POST['t1_arch']);
                    if($tmp_p_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_p_arch["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_p_arch["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce type primaire";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de type primaire n'est pas correct";
                }
                break;
            case "t2_arch":
                if(isset($_POST['t2_arch']) && !empty($_POST['t2_arch'])) {
                    $tmp_p_arch = get_all_id_archive_of_type_secondaire($connexion, $_POST['t2_arch']);
                    if($tmp_p_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_p_arch["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_p_arch["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce type secondaire";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de type secondaire n'est pas correct";
                }
                break;
            case "t3_arch":
                if(isset($_POST['t3_arch']) && !empty($_POST['t3_arch'])) {
                    $tmp_p_arch = get_all_id_archive_of_type_tertiaire($connexion, $_POST['t3_arch']);
                    if($tmp_p_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_p_arch["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_p_arch["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à ce(s) type(s) tertiaire(s)";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de type tertiaire n'est pas correct";
                }
                break;
            case "an":
                if(isset($_POST['an_arch']) && !empty($_POST['an_arch'])) {
                    $tmp_an_arch = get_all_id_archive_of_annee($connexion, $_POST['an_arch']);
                    if($tmp_an_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_an_arch["ids"]);$i++) {
                            $full_data_archive['archive'][$i] = get_archive_data($connexion, $tmp_an_arch["ids"][$i]);
                        }
                    }
                    else {
                        $full_data_archive["message"] = "Aucune archive ne correspond à l'année spécifiée";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre annee n'est pas correct";
                }
                break;
        }
    }
    catch(Exception $e)
    {
        echo 'Erreur : '.$e->getMessage().'<br />';
        echo 'N° : '.$e->getCode();
        die();
    }
}
else {
    $full_data_archive['message'] = 'le parametre de critere de recherche n\'est pas definis';
}

$data_archive = json_encode($full_data_archive);

echo $data_archive;

