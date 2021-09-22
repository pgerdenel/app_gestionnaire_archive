<?php
/**
 * Supprime les archives correspondant au critère
 * Données renvoyées: objet json{"result":"ok", "archive":["nom", "nom", ""]}
 * Paramètres obligatoires : crit_search
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=n_arch&nom_arch=ta1
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=n_edi&nom_edi=te1
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=p_arch&prop_arch=3
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=t1_arch&t1_arch=1
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=t2_arch&t2_arch=2
 * URL: http://localhost/arch/scripts/php/operations/suppressions/del_archive_get.php?crit_name=an&an_arch=2019
 **/
require_once('../../../../config/config_bdd.php');
require_once('../pdo_function.php');

// header("Content-type: application/json");

$state = -1;
$crit_search = null;
$full_data_archive = array();
$full_data_archive['result'] = false;
$full_data_archive['message'] = '';

/*if(isset($_POST['crit_name']) && !empty($_POST['crit_name'])) {*/
if(isset($_POST['crit_name']) && !empty($_POST['crit_name'])) {
    $crit_search = $_POST['crit_name'];
    // $crit_search = $_POST['crit_name'];

    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

        switch ($crit_search) {
            case "num_arch":
                if(isset($_POST['num_arch']) && !empty($_POST['num_arch'])) {
                    // if(isset($_POST['nom_arch']) && !empty($_POST['nom_arch'])) { // si le nom de l'archive existe
                    // echo "nom_arch= ".$_POST['nom_arch']."<br/>";
                    $tmp_n_arch = check_archive_exist_by_id_archive($connexion, $_POST['num_arch']);
                    // $tmp_n_arch = check_archive_exist_by_nom_archive($connexion, $_POST['nom_arch']);
                    if($tmp_n_arch["result"]) {
                        $full_data_archive['result'] = del_archive_data($connexion, $tmp_n_arch["id"]);
                        $full_data_archive["message"] = "l'archive d'id ".$tmp_n_arch["id"]." a ete supprime avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le N° d'archive est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le paramètre N° archive est incorrect";
                }
                break;
            case "n_arch":
                if(isset($_POST['nom_arch']) && !empty($_POST['nom_arch'])) {
                    // if(isset($_POST['nom_arch']) && !empty($_POST['nom_arch'])) { // si le nom de l'archive existe
                    // echo "nom_arch= ".$_POST['nom_arch']."<br/>";
                    $tmp_n_arch = check_archive_exist_by_nom_archive($connexion, $_POST['nom_arch']);
                    // $tmp_n_arch = check_archive_exist_by_nom_archive($connexion, $_POST['nom_arch']);
                    if($tmp_n_arch["result"]) {
                        $full_data_archive['result'] = del_archive_data($connexion, $tmp_n_arch["id"]);
                        $full_data_archive["message"] = "l'archive d'id ".$tmp_n_arch["id"]." a ete supprime avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le nom d'archive est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le paramètre nom_archive est incorrect";
                }
                break;
            case "n_edi":
                if(isset($_POST['nom_edi']) && !empty($_POST['nom_edi'])) {
                    $tmp_n_edi = check_archive_exist_by_nom_edifice($connexion, $_POST['nom_edi']);
                    if($tmp_n_edi["result"]) {
                        $full_data_archive['result'] = del_archive_data($connexion, $tmp_n_edi['id']);
                        $full_data_archive["message"] = "l'archive d'id ".$tmp_n_edi["id"]." a ete supprime avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le nom d'édifice est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le paramètre nom_edifice n'est pas correct";
                }
                break;
            case "p_arch":
                if(isset($_POST['prop_arch']) && !empty($_POST['prop_arch'])) {

                    $del = true;
                    $tmp_p_arch = get_all_id_archive_of_id_prop_arch($connexion, $_POST['prop_arch']);
                    if($tmp_p_arch["result"]) {
                        for($i=0;$i<sizeof($tmp_p_arch["ids"]);$i++) {
                            if(del_archive_data($connexion, $tmp_p_arch["ids"][$i]) == 0) {
                                $del = false;
                            }
                        }
                        $full_data_archive['result'] = $del;
                        /*$full_data_archive["message"] = ($del==true)?"archive(s) supprimée(s) avec succes":"euh";*/
                        $full_data_archive["message"] = "archive(s) supprimée(s) avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le propriétaire d'archive est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le paramètre nom de proprietaire n'est pas correct";
                }
                break;
            case "t1_arch":
                if(isset($_POST['t1_arch']) && !empty($_POST['t1_arch'])) {

                    $del = true;
                    $tmp_t1_arch = get_all_id_archive_of_type_primaire($connexion, $_POST['t1_arch']);
                    if($tmp_t1_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_t1_arch["ids"]);$i++) {
                            if(del_archive_data($connexion, $tmp_t1_arch["ids"][$i]) == 0) {
                                $del = false;
                            }
                        }
                        $full_data_archive['result'] = $del;
                        /*$full_data_archive["message"] = ($del==true)?"archive(s) supprimée(s) avec succes":"euh";*/
                        $full_data_archive["message"] = "archive(s) supprimée(s) avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le type primaire est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de type primaire n'est pas correct";
                }
                break;
            case "t2_arch":
                if(isset($_POST['t2_arch']) && !empty($_POST['t2_arch'])) {
                    $del = true;
                    $tmp_t2_arch = get_all_id_archive_of_type_secondaire($connexion, $_POST['t2_arch']);
                    if($tmp_t2_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_t2_arch["ids"]);$i++) {
                            if(del_archive_data($connexion, $tmp_t2_arch["ids"][$i]) == 0) {
                                $del = false;
                            }
                        }
                        $full_data_archive['result'] = $del;
                        /*$full_data_archive["message"] = ($del==true)?"archive(s) supprimée(s) avec succes":"euh";*/
                        $full_data_archive["message"] = "archive(s) supprimée(s) avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, le type secondaire est incorrect ou n'existe pas";
                    }
                }
                else {
                    $full_data_archive["message"] = "le parametre nom de type secondaire n'est pas correct";
                }
                break;
            case "an":
                if(isset($_POST['an_arch']) && !empty($_POST['an_arch'])) {
                    $del = true;
                    $tmp_an_arch = get_all_id_archive_of_annee($connexion, $_POST['an_arch']);
                    if($tmp_an_arch["result"]) {
                        $full_data_archive['result'] = true;
                        for($i=0;$i<sizeof($tmp_an_arch["ids"]);$i++) {
                            if(del_archive_data($connexion, $tmp_an_arch["ids"][$i]) == 0) {
                                $del = false;
                            }
                        }
                        $full_data_archive['result'] = $del;
                        /*$full_data_archive["message"] = ($del==true)?"archive(s) supprimée(s) avec succes":"euh";*/
                        $full_data_archive["message"] = "archive(s) supprimée(s) avec succes";
                    }
                    else {
                        $full_data_archive["message"] = "erreur de suppression de l'archive, l'année est incorrecte ou n'existe pas";
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

/* Renvoie les informations d'une archive sous forme d'un tableau */
function del_archive_data($connexion, $id_arch) {
    $state_del = true;
    $archive_del = array();
    $ids_trav = null;
    //echo "id_arch= ".$id_arch."<br/>";
    $tmp_edi = check_if_arch_link_to_edifice($connexion, $id_arch);
    if ($tmp_edi['result'] != 0) { // l'archive est lié à un édifice
        $id_edi = $tmp_edi['id'];  // on récupère l'id_edifice
        //echo "id_edi= ".$id_edi."<br/>";
        $tmp_trav = check_if_edi_link_to_trav($connexion, $id_edi);

        if ($tmp_trav['result'] == true) { // l'édifice est lié à des travaux
            $ids_trav = $tmp_trav['ids']; // on récupère les id_travaux en tableau

            // on récupère la list des id_list_entreprise lié
            $ids_list_ent = array();
            for ($i = 0; $i < sizeof($ids_trav); $i++) {
                array_push($ids_list_ent, get_list_id_ent_of_id_trav($connexion, $ids_trav[$i]));
            }
            $str_ids_list_ent = implode(",", $ids_list_ent);

            // on supprime les travaux de travaux
            $archive_del['del_travaux_in_travaux'] = del_travaux_in_travaux($connexion, implode(",",$ids_trav));

            // on supprime les id_list_entreprise de list_entreprise_to_entreprise
            $archive_del['del_list_ent_in_list_ent_to_ent'] = del_list_ent_in_list_ent_to_ent($connexion, $str_ids_list_ent);

            // on supprime l'id_list_entreprise de list_entreprise
            $archive_del['del_list_ent_in_list_ent'] = del_list_ent_in_list_ent($connexion, $str_ids_list_ent);
        }

        // on supprime les id_edifice liés à l'idarchive de archive_to_edifice
        $archive_del['del_edifice_in_archive_to_edifice'] = del_edifice_in_archive_to_edifice($connexion, $id_edi);

        // on récupère l'id propriétaire édifice(avant suppression de l'édifice)
        $id_prop_edi = get_id_prop_edifice_from_edifice($connexion, $id_edi);

        // on supprime l'édifice
        $archive_del['del_edifice'] = del_edifice($connexion, $id_edi);

        // on supprime le propriétaire d'édifice
        $archive_del['del_prop_edifice'] = del_prop_edifice($connexion, $id_prop_edi);

        // on supprime les entrées de archive_to_type_tertiaire portant l'id_archive
        $archive_del['del_arch_in_arch_to_type_ter'] = del_arch_in_arch_to_type_ter($connexion, $id_arch);

        // on supprime l'archive
        $archive_del['del_arch_in_arch'] = del_arch_in_arch($connexion, $id_arch);
    }
    else {
        // on supprime les entrées de archive_to_type_tertiaire portant l'id_archive
        $archive_del['del_arch_in_arch_to_type_ter'] = del_arch_in_arch_to_type_ter($connexion, $id_arch);

        // on supprime l'archive
        $archive_del['del_arch_in_arch'] = del_arch_in_arch($connexion, $id_arch);
    }

    foreach ($archive_del as $key => $value) {
        if($value == 0 || $value == null) {
            $state_del = false;
        }
    }

    return $state_del;
}