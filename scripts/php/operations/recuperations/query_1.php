<?php
/**
 * Renvoie la liste archives correspondantes en BDD de la query 1
 * A FAIRE
 * Convention d'ordre des arguments p_arch p_edi dep commu com_phys com_virt t_edi mm hono
 * prendre en compte qu'un travaux peut ne pas avoir de date de fin
 * résoudre les prolbèmes avec les ou qui insère les données en double
 * Paramètres obligatoires : crit_search
 * URL: 2crit p_arch et commu
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"p_arch":{"crit_p_arch":"eq","p_arch":"1","join_p_arch":"et"},"commu":{"crit_commu":"eq","commu":"pierrefonds","join_commu":"et"}}
 * URL: 2crit  p_edi et dep
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"p_edi":{"crit_p_edi":"eq","p_edi":"j.durand","join_p_edi":"et"},"dep":{"crit_dep":"eq","dep":"oise","join_dep":"et"}}
 * URL: 2crit  com_phys et com_virt
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"com_phys":{"crit_com_phys":"eq","com_phys":"com_phys","join_com_phys":"et"},"com_virt":{"crit_com_virt":"eq","com_virt":"com_virt","join_com_virt":"et"}}
 * URL: 2crit  t_edi et mm
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"t_edi":{"crit_t_edi":"eq","t_edi":"1","join_t_edi":"et"},"mm":{"crit_mm":"eq","mm":"1","join_mm":"et"}}
 * URL: 2crit  mm et hono
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"mm":{"crit_mm":"eq","mm":"8","join_mm":"et"},"hono":{"crit_hono":"eq","hono":"12"}}
 * URL: all crit
 * http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"p_arch":{"crit_p_arch":"eq","p_arch":"1","join_p_arch":"et"},"p_edi":{"crit_p_edi":"eq","p_edi":"j.durand","join_p_edi":"et"},"dep":{"crit_dep":"eq","dep":"oise","join_dep":"et"},"commu":{"crit_commu":"eq","commu":"pierrefonds","join_commu":"et"},"com_phys":{"crit_com_phys":"eq","com_phys":"com_phys","join_com_phys":"et"},"com_virt":{"crit_com_virt":"eq","com_virt":"com_virt","join_com_virt":"et"},"t_edi":{"crit_t_edi":"eq","t_edi":"1","join_t_edi":"et"},"mm":{"crit_mm":"eq","mm":"8","join_mm":"et"},"hono":{"crit_hono":"eq","hono":"12"}}
 * URL one crit
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"p_arch":{"crit_p_arch":"eq","p_arch":"1","join_p_arch":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"p_edi":{"crit_p_edi":"eq","p_edi":"j.durand","join_p_edi":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"dep":{"crit_dep":"eq","dep":"oise","join_dep":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"commu":{"crit_commu":"eq","commu":"pierrefonds","join_commu":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"com_phys":{"crit_com_phys":"eq","com_phys":"com_phys","join_com_phys":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"com_virt":{"crit_com_virt":"eq","com_virt":"com_virt","join_com_virt":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"t_edi":{"crit_t_edi":"eq","t_edi":"1","join_t_edi":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"mm":{"crit_mm":"eq","mm":"1","join_mm":"et"}}
 * URL: http://localhost/arch/scripts/php/operations/recuperations/query_1_get.php?id_request=1&request_data={"hono":{"crit_hono":"eq","hono":"1"}}
 * URL multi crit
 **/
require_once('../../../../config/config_bdd.php');
require_once('../pdo_function.php');
// header("Content-type: application/json");

$state = -1;
$id_request = -1;
$full_data = array();
$full_data['result'] = false;
$full_data['message'] = '';
$full_data['content'] = array();

/*if(isset($_POST['crit_name']) && !empty($_POST['crit_name'])) {*/
if(isset($_POST['id_request']) && !empty($_POST['id_request']) && isset($_POST['request_data']) && !empty($_POST['request_data']) ) {
    $id_request = $_POST['id_request'];

    try
    {
        // connexion
        $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

        switch($id_request) {
            case "1":
                //echo 'requête 1 traitement<br/>';

                $data_request = json_decode($_POST['request_data'], 1);
                //echo 'data_request size = '.sizeof($data_request).'<br/>';
                // var_dump($data_request);
                /*foreach ($data_request as $key => &$value) {
                    foreach ($value as $key2 => &$value2) {
                        echo '<pre>key2 = ' . $key2 . ' = ' . $value2 . '</pre>';
                    }
                }*/
                if(sizeof($data_request) == 1) { // si c'est le seul critère
                    //echo 'only 1 critere<br/>';
                    foreach ($data_request as $key => &$value) {
                        //echo 'key = ' . $key . '<br/>';
                        if ($key == 'p_arch') { // si un nom de propriétaire est spécifié
                            //echo 'crit p_arch called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $arch_comp = $data_request[$key]['crit_p_arch'];
                            // on récupère l'id du propriétaire d'archive
                            $arch_id_prop = $data_request[$key]['p_arch'];
                            // on récupère tous les id_archives matchant le critère
                            $ids_arch = query_1_one_crit_get_id_arch_from_id_prop_arch_and_comp($connexion, $arch_id_prop, $arch_comp);
                            if($ids_arch['result']) {
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    if (check_if_arch_link_to_edifice($connexion, $ids_arch['ids'][$i])['result'] == 1) {
                                        //echo 'pour l\'id archive'.$ids_arch[$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "id_prop_arch" => $arch_id_prop, "comp"=>$arch_comp);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    }
                                    else {
                                        $full_data['message'] = "aucune donnees ne matche ce proprietaire d'archive et son operateur de comparaison";
                                        $full_data['content'] = null;
                                    }
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce proprietaire d'archive et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 'p_edi') {
                            //echo 'crit p_edi called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $edi_comp = $data_request[$key]['crit_p_edi'];
                            // on récupère le nom du proprietaire d'édifice
                            $nom_prop_edi = $data_request[$key]['p_edi'];
                            $id_prop_edi = get_id_prop_edi_from_nom_prop_edi($connexion, $nom_prop_edi); // on récupère l'id_prop_edi correspondant au nom edi
                            if($id_prop_edi['result'] == 1) { // s'il existe un id_prop_edi correspondant au nom_prop_edi
                                // on récupère tous les id_archives matchant le critère
                                $ids_arch = query_1_one_crit_get_id_arch_from_id_prop_edi_and_comp($connexion, $id_prop_edi['id'], $edi_comp);
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                    $param = array("id_arch" => $ids_arch['ids'][$i], "nom_prop_edi"=>$nom_prop_edi, "id_prop_edi" => $id_prop_edi['id'], "comp"=>$edi_comp);
                                    // on recupère toutes les data de la requête
                                    $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce nom de proprietaire d'edifice et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 'dep') {
                            //echo 'crit dep called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $dep_comp = $data_request[$key]['crit_dep'];
                            // on récupère le département
                            $dep = $data_request[$key]['dep'];
                            $dep_exist = check_if_dep_exist_by_dep($connexion, $dep); // on vérifie si le dep existe
                            if($dep_exist['result'] == 1) { // s'il existe un departement correspondant à celui donné par l'utilisateur
                                // on récupère tous les id_archives matchant le critère
                                $ids_arch = query_1_one_crit_get_id_arch_from_dep_edi_and_comp($connexion, $dep, $dep_comp);
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                    $param = array("id_arch" => $ids_arch['ids'][$i], "dep"=>$dep, "comp"=>$dep_comp);
                                    // on recupère toutes les data de la requête
                                    $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce departement et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 'commu') {
                            //echo 'crit commu called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $commu_comp = $data_request[$key]['crit_commu'];
                            // on récupère le nom de commune
                            $commu = $data_request[$key]['commu'];
                            $commu_exist = check_if_commu_exist_by_commu($connexion, $commu); // on vérifie si la commune existe
                            if($commu_exist['result'] == 1) { // s'il existe une commune correspondant à celui donné par l'utilisateur
                                // on récupère tous les id_archives matchant le critère
                                $ids_arch = query_1_one_crit_get_id_arch_from_commu_edi_and_comp($connexion, $commu, $commu_comp);
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                    $param = array("id_arch" => $ids_arch['ids'][$i], "commu"=>$commu, "comp"=>$commu_comp);
                                    // on recupère toutes les data de la requête
                                    $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche cette commune et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 'com_phys') {
                            //echo 'crit com_phys called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $comp_com_phys = $data_request[$key]['crit_com_phys'];
                            // on récupère l'id du propriétaire d'archive
                            $com_phys = $data_request[$key]['com_phys'];
                            // on récupère tous les id_archives matchant le critère
                            $ids_arch = query_1_one_crit_get_id_arch_from_com_phys_and_comp($connexion, $com_phys, $comp_com_phys);
                            if($ids_arch['result']) {
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    if (check_if_arch_link_to_edifice($connexion, $ids_arch['ids'][$i])['result'] == 1) { // pour les archives qui sont liés à des édifices seulement
                                        //echo 'pour l\'id archive'.$ids_arch[$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "com_phys" => $com_phys, "comp"=>$comp_com_phys);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    } else {
                                        echo 'id_arch ' . $ids_arch['ids'][$i] . 'non lié à un édifice';
                                    }
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce commentaire physique et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 'com_virt') {
                            //echo 'crit com_virt called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $comp_com_virt = $data_request[$key]['crit_com_virt'];
                            // on récupère le commentaire virtuel
                            $com_virt = $data_request[$key]['com_virt'];
                            // on récupère tous les id_archives matchant le critère
                            $ids_arch = query_1_one_crit_get_id_arch_from_com_virt_and_comp($connexion, $com_virt, $comp_com_virt);
                            if($ids_arch['result']) {
                                for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                    if (check_if_arch_link_to_edifice($connexion, $ids_arch['ids'][$i])['result'] == 1) { // pour les archives qui sont liés à des édifices seulement
                                        //echo 'pour l\'id archive'.$ids_arch[$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "com_virt" => $com_virt, "comp"=>$comp_com_virt);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    } else {
                                        echo 'id_arch ' . $ids_arch['ids'][$i] . 'non lié à un édifice';
                                    }
                                }
                                // on vérifie que le content contient bien des données
                                if (sizeof($full_data['content']) > 0) {
                                    $full_data['result'] = true;
                                    $full_data['message'] = "donnees de requete recuperees";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce commentaire virtuel et son operateur de comparaison";
                                $full_data['content'] = null;
                            }
                        }
                        else if ($key == 't_edi') {
                            //echo 'crit t_edi called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $t_edi_comp = $data_request[$key]['crit_t_edi'];
                            // on récupère l'id du type d'édifice
                            $id_type_edi = $data_request[$key]['t_edi'];
                            $edi_exist_for_type_edi = check_if_edi_exist_for_type_edi($connexion, $id_type_edi); // on récupère les id_edifice correspondant aux types edi
                            if ($edi_exist_for_type_edi == 1) { // s'il existe des id_edi correspondant à l'id_type_edi
                                // on récupère tous les id_archives matchant le critère
                                $ids_arch = query_1_one_crit_get_id_arch_from_id_type_edi_and_comp($connexion, $id_type_edi, $t_edi_comp);
                                if($ids_arch['result'] == 1) {
                                    for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                        //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "t_edi" => $id_type_edi, "comp" => $t_edi_comp);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    }
                                    // on vérifie que le content contient bien des données
                                    if (sizeof($full_data['content']) > 0) {
                                        $full_data['result'] = true;
                                        $full_data['message'] = "donnees de requete recuperees";
                                    }
                                }
                                else {
                                    $full_data['message'] = "aucune donnees ne matche ce type d'édifice et son operateur de comparaison";
                                }
                            } else {
                                $full_data['message'] = "aucune donnees ne matche ce type d'edifice et son operateur de comparaison";

                            }
                        }
                        else if ($key == 'mm') {
                            //echo 'crit mm called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $mm_comp = $data_request[$key]['crit_mm'];
                            // on récupère le montant de marché
                            $mm = $data_request[$key]['mm'];
                            // on récupère les id_edifice matchant le montant de marché et son critère
                            $ids_edi = query_1_one_crit_get_id_arch_from_mm_and_comp($connexion, $mm, $mm_comp); // on récupère les id_edifice correspondant aux types edi
                            if ($ids_edi['result'] == 1) { // s'il existe des id_edi correspondant au montant de marché et son critère
                                // on récupère tous les id_archives matchant les ids_edi
                                $ids_arch = get_all_id_archive_of_ids_edi($connexion, $ids_edi['ids']);
                                if($ids_arch['result'] == 1) {
                                    for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                        //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "mm" => $mm, "comp" => $mm_comp);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    }
                                    // on vérifie que le content contient bien des données
                                    if (sizeof($full_data['content']) > 0) {
                                        $full_data['result'] = true;
                                        $full_data['message'] = "donnees de requete recuperees";
                                    }
                                }
                                else {
                                    $full_data['message'] = "aucune donnees ne matche ce montant de marche et son operateur de comparaison";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce montant de marche et son operateur de comparaison";

                            }

                        }
                        else if ($key == 'hono') {
                            //echo 'crit hono called<br/><br/><br/>';
                            // on récupère l'opérateur de comparaison
                            $hono_comp = $data_request[$key]['crit_hono'];
                            // on récupère le montant de marché
                            $hono = $data_request[$key]['hono'];
                            // on récupère les id_edifice matchant le montant d'honoraire et son critère
                            $ids_edi = query_1_one_crit_get_id_arch_from_hono_and_comp($connexion, $hono, $hono_comp);
                            if ($ids_edi['result'] == 1) { // s'il existe des id_edi correspondant au montant d'honoraire et son critère
                                // on récupère tous les id_archives matchant les ids_edi
                                $ids_arch = get_all_id_archive_of_ids_edi($connexion, $ids_edi['ids']);
                                if($ids_arch['result'] == 1) {
                                    for ($i = 0; $i < sizeof($ids_arch['ids']); $i++) {
                                        //echo 'pour l\'id archive'.$ids_arch['ids'][$i].'<br/>';
                                        $param = array("id_arch" => $ids_arch['ids'][$i], "hono" => $hono, "comp" => $hono_comp);
                                        // on recupère toutes les data de la requête
                                        $full_data['content'][$i] = query_1_get_data($connexion, $param, $key);
                                    }
                                    // on vérifie que le content contient bien des données
                                    if (sizeof($full_data['content']) > 0) {
                                        $full_data['result'] = true;
                                        $full_data['message'] = "donnees de requete recuperees";
                                    }
                                }
                                else {
                                    $full_data['message'] = "aucune donnees ne matche ce montant d'honoraire et son operateur de comparaison";
                                }
                            }
                            else {
                                $full_data['message'] = "aucune donnees ne matche ce montant d'honoraire et son operateur de comparaison";

                            }
                        }
                        else {
                            echo 'le paramètre critère est inconnu';
                        }
                    }
                }
                else if(sizeof($data_request)> 1) {
                    $ids_arch = query_1_several_crits_get_id_arch_from_crit($connexion, $data_request);
                    //var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        for ($i = 0; $i < sizeof($ids_arch['ids_arch_full']); $i++) {
                            if(check_if_arch_link_to_edifice($connexion, $ids_arch['ids_arch_full'][$i])['result'] == 1) {
                                $param = array("id_arch" => $ids_arch['ids_arch_full'][$i]);
                                //var_dump($param);
                                $full_data['content'][$i] = query_1_get_data($connexion, $param, "multi_crit");
                            }
                        }
                        // on vérifie que le content contient bien des données
                        if (sizeof($full_data['content']) > 0) {
                            $full_data['result'] = true;
                            $full_data['message'] = "donnees de requete recuperees";
                        }
                        else {
                            $full_data['message'] = $ids_arch['message'];
                        }

                    }
                    else {
                        $full_data['message'] = "aucune donnée ne matche le(s) critère(s)";
                    }
                }
                else {
                    $full_data['message'] = "la requête est incorrect";
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
    $full_data['message'] = 'parametre(s) de critere de recherche non défini(s)';
}

$content = json_encode($full_data);

echo $content;

