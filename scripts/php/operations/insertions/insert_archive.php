<?php
/**
 * Ajoute une archive dans la base de donne
 * Données renvoyées: true or false
 * Paramètres obligatoires : type_insert
 * Paramètres obligatoires : type_insert=1
 *   "type_insert="+type_insert+
 *   "&nom_archive="+data_nom_archive+
 *   "&proprio_archive"+data_proprio_archive+
 *   "&type_primaire_archive"+data_type_primaire_archive+
 *   "&type_secondaire_archive"+data_type_secondaire_archive+
 *   "&type_tertiaire_archive"+data_type_tertiaire_archive+
 *   "&annee_archive"+data_annee_archive+
 *   "&est_physique_archive"+data_est_physique_archive+
 *   "&com_physique_archive"+data_com_physique_archive+
 *   "&est_virtuelle_archive"+data_est_virtuelle_archive+
 *   "&com_virtuelle_archive"+data_com_virtuelle_archive;
 *
 * Paramètres obligatoires : type_insert=2
 *   "type_insert="+type_insert+
 *   "&nom_archive="+data_nom_archive+
 *   "&proprio_archive"+data_proprio_archive+
 *   "&type_primaire_archive"+data_type_primaire_archive+
 *   "&type_secondaire_archive"+data_type_secondaire_archive+
 *   "&type_tertiaire_archive"+data_type_tertiaire_archive+
 *   "&annee_archive"+data_annee_archive+
 *   "&est_physique_archive"+data_est_physique_archive+
 *   "&com_physique_archive"+data_com_physique_archive+
 *   "&est_virtuelle_archive"+data_est_virtuelle_archive+
 *   "&com_virtuelle_archive"+data_com_virtuelle_archive+
 *
 *
 * Paramètres obligatoires : type_insert=3
 *   "type_insert="+type_insert+
 *   "&nom_archive="+data_nom_archive+
 *   "&proprio_archive"+data_proprio_archive+
 *   "&type_primaire_archive"+data_type_primaire_archive+
 *   "&type_secondaire_archive"+data_type_secondaire_archive+
 *   "&type_tertiaire_archive"+data_type_tertiaire_archive+
 *   "&annee_archive"+data_annee_archive+
 *   "&est_physique_archive"+data_est_physique_archive+
 *   "&com_physique_archive"+data_com_physique_archive+
 *   "&est_virtuelle_archive"+data_est_virtuelle_archive+
 *   "&com_virtuelle_archive"+data_com_virtuelle_archive+
 *
 *
 * URL type1: http://localhost/arch/scripts/php/insert_archive.php?type_insert=1&nom_archive=nomarchive&proprio_archive=1&type_primaire_archive=1&type_secondaire_archive=1&type_tertiaire_archive=6,7&annee_archive=2019-04-17&date_archivage=2019-04-17&est_physique_archive=1&com_physique_archive=commentairep&est_virtuelle_archive=1&com_virtuelle_archive=commentairev
 * URL type2: http://localhost/arch/scripts/php/insert_archive.php?type_insert=2&nom_archive=nomarchive&proprio_archive=1&type_primaire_archive=1&type_secondaire_archive=1&type_tertiaire_archive=6,7&annee_archive=2019-04-17&date_archivage=2019-04-17&est_physique_archive=1&com_physique_archive=commentairep&est_virtuelle_archive=1&com_virtuelle_archive=commentairev
 * URL type3: http://localhost/arch/scripts/php/insert_archive.php?type_insert=3&nom_archive=nomarchive&proprio_archive=1&type_primaire_archive=1&type_secondaire_archive=1&type_tertiaire_archive=6,7&annee_archive=2019-04-17&date_archivage=2019-04-17&est_physique_archive=1&com_physique_archive=commentairep&est_virtuelle_archive=1&com_virtuelle_archive=commentairev
 **/
require_once('../../../../config/config_bdd.php');

//header("Content-type: application/json");

// On récupère les paramètre de la requête POST
if(isset($_POST['type_insert']) && !empty($_POST['type_insert'])) {

        $state_unique = -1;
        $lastid_arch = -1;
        $lastid_edi = -1;
        $state_arch = -1;
        $state_type_ter = -1;
        $state_edi = -1;
        $state_trav = -1;
        $response=array();

        // connexion
        $connexion = new PDO('mysql:host=' . $PARAM_hote . ';dbname=' . $PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);

        // on vérifie si le nom d'archive existe déjà ou pas en BDDD
        if(isset($_POST['nom_archive']) && !empty($_POST['nom_archive'])) {
            try {
                $connexion->beginTransaction();
                $stmt = $connexion->prepare("SELECT ID_ARCHIVE FROM archive WHERE `NOM_ARCHIVE` = :nom_arch");
                $stmt->bindParam(':nom_arch', $_POST['nom_archive']);

                // execution de la requête
                $state_unique = $stmt->execute();
                //echo "state_unique=".$state_unique." pour le nom d'archive ".$_POST['nom_archive']."<br/>";
                $unique = $stmt->fetch(); // on stocke le résultat de la requête
                //echo "unique=".$unique."<br/>";
                $connexion->commit();

            } catch (Exception $e) {
                echo 'Erreur : ' . $e->getMessage() . '<br />';
                echo 'N° : ' . $e->getCode();
                die();
            }
            if($unique == "") { // si le résultat de la requête est vide alors le nom d'archive n'existe pas

                if (isset($_POST['nom_archive']) && !empty($_POST['nom_archive']) &&
                    isset($_POST['proprio_archive']) && !empty($_POST['proprio_archive']) &&
                    isset($_POST['type_primaire_archive']) && !empty($_POST['type_primaire_archive']) &&
                    isset($_POST['type_secondaire_archive']) && !empty($_POST['type_secondaire_archive']) &&
                    isset($_POST['type_tertiaire_archive']) && !empty($_POST['type_tertiaire_archive']) &&
                    isset($_POST['annee_archive']) && !empty($_POST['annee_archive']) &&
                    isset($_POST['date_archivage']) && !empty($_POST['date_archivage']) &&
                    isset($_POST['est_physique_archive']) && !empty($_POST['est_physique_archive']) &&
                    isset($_POST['com_physique_archive']) && !empty($_POST['com_physique_archive']) &&
                    isset($_POST['est_virtuelle_archive']) && !empty($_POST['est_virtuelle_archive']) &&
                    isset($_POST['com_virtuelle_archive']) && !empty($_POST['com_virtuelle_archive'])
                ) {

                    try {

                        $nom_archive = $_POST['nom_archive'];
                        $proprio_archive = $_POST['proprio_archive'];
                        $type_primaire_archive = $_POST['type_primaire_archive'];
                        $type_secondaire_archive = $_POST['type_secondaire_archive'];
                        $annee_archive = $_POST['annee_archive'];
                        $date_archivage = $_POST['date_archivage'];
                        $est_physique_archive = $_POST['est_physique_archive'];
                        $est_virtuelle_archive = $_POST['est_virtuelle_archive'];
                        $com_physique_archive = $_POST['com_physique_archive'];
                        $com_virtuelle_archive = $_POST['com_virtuelle_archive'];

                        /*echo "on fait la requête<br/>";
                        echo "nom_archive= " . $nom_archive . "<br/>";
                        echo "proprio_archive= " . $proprio_archive . "<br/>";
                        echo "type_primaire_archive= " . $type_primaire_archive . "<br/>";
                        echo "type_secondaire_archive= " . $type_secondaire_archive . "<br/>";
                        echo "annee_archive= " . $annee_archive . "<br/>";
                        echo "date_archivage= " . $date_archivage . "<br/>";
                        echo "est_physique_archive= " . $est_physique_archive . "<br/>";
                        echo "com_physique_archive= " . $est_virtuelle_archive . "<br/>";
                        echo "est_virtuelle_archive= " . $com_physique_archive . "<br/>";
                        echo "com_virtuelle_archive= " . $com_virtuelle_archive . "<br/>";*/

                        // préparation de la requête
                        $connexion->beginTransaction();
                        $stmt = $connexion->prepare("INSERT INTO archive(`NOM_ARCHIVE`, `ID_PROPRIETAIRE`, `ID_TYPE_PRIMAIRE_ARCHIVE`, `ID_TYPE_SECONDAIRE_ARCHIVE`, `ANNEE_ARCHIVE`, `DATE_ARCHIVAGE_ARCHIVE`, `EST_PHYSIQUE_ARCHIVE`, `EST_VIRTUELLE_ARCHIVE`, `REFERENCE_PHYSIQUE_ARCHIVE`, `REFERENCE_VIRTUELLE_ARCHIVE`) VALUES (:nom, :id_prop, :id_tp, :id_ts, :an, :datea, :epa, :eva, :refp, :refv)");

                        // affection d'une variable à la valeur du paramètre de la requête
                        /*$connexion->bindParam(':nom', $_GET['nom_archive']);
                        $connexion->bindParam(':id_prop', $_GET['proprio_archive']);
                        $connexion->bindParam(':id_tp', $_GET['type_primaire_archive']);
                        $connexion->bindParam(':id_ts', $_GET['type_secondaire_archive']);
                        $connexion->bindParam(':an', $_GET['annee_archive']);
                        $connexion->bindParam(':datea', $_GET['date_archivage']);
                        $connexion->bindParam(':epa', $_GET['est_physique_archive']);
                        $connexion->bindParam(':eva', $_GET['est_virtuelle_archive']);
                        $connexion->bindParam(':refp', $_GET['com_physique_archive']);
                        $connexion->bindParam(':refv', $_GET['com_virtuelle_archive']);*/

                        $stmt->bindParam(':nom', $_POST['nom_archive']);
                        $stmt->bindParam(':id_prop', $_POST['proprio_archive']);
                        $stmt->bindParam(':id_tp', $_POST['type_primaire_archive']);
                        $stmt->bindParam(':id_ts', $_POST['type_secondaire_archive']);
                        $stmt->bindParam(':an', $_POST['annee_archive']);
                        $stmt->bindParam(':datea', $_POST['date_archivage']);
                        $epa = filter_var($_POST['est_physique_archive'], FILTER_VALIDATE_BOOLEAN);
                        $stmt->bindParam(':epa', $epa);
                        $eva = filter_var($_POST['est_virtuelle_archive'], FILTER_VALIDATE_BOOLEAN);
                        $stmt->bindParam(':eva', $eva);
                        $stmt->bindParam(':refp', $_POST['com_physique_archive']);
                        $stmt->bindParam(':refv', $_POST['com_virtuelle_archive']);

                        // execution de la requête
                        $state_arch = $stmt->execute();
                        $lastid_arch = $connexion->lastInsertId();
                        $connexion->commit();

                        // enregistrement de l'ID de cette archive avec les types tertiaires

                        $state_type_ter = 0;
                        $state_ter[] = array();
                        $ar_type_ter = explode(",", $_POST['type_tertiaire_archive']);
                        for ($i = 0; $i < sizeof($ar_type_ter); $i++) {
                            $connexion->beginTransaction();
                            $sql = "INSERT INTO archive_to_type_tertiaire(`ID_ARCHIVE`, `ID_TYPE_TERTIAIRE_ARCHIVE`) VALUES (:id_arch, :id_ter)";
                            $stmt = $connexion->prepare($sql);
                            $stmt->bindParam(':id_arch', $lastid_arch);
                            $stmt->bindParam('id_ter', $ar_type_ter[$i]);
                            if ($stmt->execute() == 1) {
                                $state_type_ter = 1;
                            } else {
                                $state_type_ter = 0;
                            }
                            $connexion->commit();
                        }

                        if ($state_arch == 1 && $state_type_ter == 1) {
                            $response = array("result" => $state_arch, "id" => $lastid_arch);
                        } else {
                            $response = array("result" => false);
                        }

                        //echo 'état de la requête '.$state;
                    } catch (Exception $e) {
                        echo 'Erreur : ' . $e->getMessage() . '<br />';
                        echo 'N° : ' . $e->getCode();
                        die();
                    }

                    // enregistrement de l'édifice
                    if ($_POST['type_insert'] == "2" || $_POST['type_insert'] == "3") {
                        if (
                            isset($_POST['nom_edifice']) && !empty($_POST['nom_edifice']) &&
                            isset($_POST['type_edifice']) && !empty($_POST['type_edifice']) &&
                            isset($_POST['commune_edifice']) && !empty($_POST['commune_edifice']) &&
                            isset($_POST['dep_edifice']) && !empty($_POST['dep_edifice']) &&
                            isset($_POST['nom_proprio_edifice']) && !empty($_POST['nom_proprio_edifice']) &&
                            isset($_POST['est_part']) && !empty($_POST['est_part']) &&
                            isset($_POST['est_commu']) && !empty($_POST['est_commu'])
                        ) {

                            // vérification si le propriétaire existe
                            $connexion->beginTransaction();
                            $stmt = $connexion->prepare("SELECT ID_PROPRIETAIRE_EDIFICE FROM proprietaire_edifice WHERE `NOM_PROPRIETAIRE_EDIFICE` = :nom_prop");
                            $stmt->bindParam(':nom_prop', $_POST['nom_proprio_edifice']);

                            // execution de la requête
                            $state_unique = $stmt->execute();
                            //echo "state_unique=".$state_unique." pour le nom d'archive ".$_POST['nom_archive']."<br/>";
                            $unique_prop = $stmt->fetch(); // on stocke le résultat de la requête
                            //echo "unique=".$unique."<br/>";
                            $connexion->commit();

                            //echo "state_unique= ".$state_unique."<br/>";
                            //echo "unique_prop= <br/>";
                            //var_dump($unique_prop);

                            $state_prop_edi = -1;

                            if($unique_prop == "") { // propriétaire unique
                                //echo "prop unique";
                                // préparation de la requête d'insertion d'un propriétaire d'édifice
                                $connexion->beginTransaction();
                                $stmt = $connexion->prepare("INSERT INTO proprietaire_edifice(`NOM_PROPRIETAIRE_EDIFICE`, `EST_UN_PARTICULIER_PROPRIETAIRE_EDIFICE`, `EST_UNE_COMMUNE_PROPRIETAIRE_EDIFICE`) VALUES (:nom_prop, :est_part, :est_commu)");

                                $stmt->bindParam(':nom_prop', $_POST['nom_proprio_edifice']);
                                $est_part = filter_var($_POST['est_part'], FILTER_VALIDATE_BOOLEAN);
                                $stmt->bindParam(':est_part', $est_part);
                                $est_commu = filter_var($_POST['est_commu'], FILTER_VALIDATE_BOOLEAN);
                                $stmt->bindParam(':est_commu', $est_commu);

                                // execution de la requête
                                $state_prop_edi = $stmt->execute();
                                $last_id_prop = $connexion->lastInsertId();
                                $connexion->commit();
                            }
                            else {
                                //echo "prop pas unique";
                                $state_prop_edi = 1;
                                $last_id_prop = $unique_prop[0];
                            }
                            // préparation de la requête d'insertion d'édifice
                            $connexion->beginTransaction();
                            $stmt = $connexion->prepare("INSERT INTO edifice(`ID_TYPE_EDIFICE`, `ID_PROPRIETAIRE_EDIFICE`, `COMMUNE_EDIFICE`, `DEPARTEMENT_EDIFICE`, `NOM_EDIFICE`) VALUES (:id_type_edi, :id_prop_edi, :com, :dep, :nom)");

                            $stmt->bindParam(':id_type_edi', $_POST['type_edifice']);
                            $stmt->bindParam(':id_prop_edi', $last_id_prop);
                            $stmt->bindParam(':com', $_POST['commune_edifice']);
                            $stmt->bindParam(':dep', $_POST['dep_edifice']);
                            $stmt->bindParam(':nom', $_POST['nom_edifice']);

                            // execution de la requête
                            $state_edi = $stmt->execute();
                            if ($state_prop_edi != 1 || $state_edi != 1) {
                                $state_edi = 0;
                            }
                            $last_id_edi = $connexion->lastInsertId();
                            $connexion->commit();

                            // préparation de la requête d'insertion dans la table archive_to_edifice
                            $connexion->beginTransaction();
                            $stmt = $connexion->prepare("INSERT INTO archive_to_edifice(`ID_ARCHIVE`, `ID_EDIFICE`) VALUES (:id_arch, :id_edi)");

                            $stmt->bindParam(':id_arch', $lastid_arch);
                            $stmt->bindParam(':id_edi', $last_id_edi);

                            // execution de la requête
                            $state_arch_to_edi = $stmt->execute();
                            if ($state_edi != 1 && $state_arch_to_edi != 1) {
                                $state_edi = 0;
                            }
                            $connexion->commit();

                            if ($state_arch == 1 && $state_type_ter == 1 && $state_edi == 1) {
                                $response = array("result" => $state_arch, "id" => $lastid_arch);
                            } else {
                                $response = array("result" => false);
                            }

                        } else {
                            echo '<p style="color:red">Erreur de paramètre, les paramètres de l\'édifice ne sont pas correctes</p>';

                        }
                        // enregistrement des travaux
                        if ($_POST['type_insert'] == "3") {

                            if (isset($_POST['trav_json']) && !empty($_POST['trav_json'])) {
                                $tab_trav = json_decode($_POST['trav_json'], true);

                                //echo("taille tab list_ent= ".sizeof($tab_trav)."<br/>");
                                for ($i = 0; $i < sizeof($tab_trav); $i++) {
                                    $state_list_ent = -1;
                                    $state_list_ent_to_ent = -1;
                                    // création de la list_entreprise
                                    $connexion->beginTransaction();
                                    $sql = "INSERT INTO list_entreprise() VALUES ()";
                                    $stmt = $connexion->prepare($sql);
                                    if ($stmt->execute() == 1) {
                                        $state_list_ent = 1;
                                    } else {
                                        $state_list_ent = 0;
                                    }
                                    $last_id_list_ent = $connexion->lastInsertId();
                                    $connexion->commit();

                                    /*echo("DEBUG= tab <br/>");
                                    var_dump($tab_trav[$i]);
                                    echo ("duree= ".$tab_trav[$i]['duree']."<br/>");*/
                                    //print_r($tab_trav[$i]['list_entreprise']);
                                    //echo "<br/><br/>";
                                    // echo($tab_trav[$i]['list_entreprise'][0]."<br/><br/>");

                                    // création des entrées dans list_entreprise_to_entreprise
                                    for ($j = 0; $j < sizeof($tab_trav[$i]['list_entreprise']); $j++) {
                                        $connexion->beginTransaction();
                                        $sql = "INSERT INTO list_entreprise_to_entreprise(`ID_LIST_ENTREPRISE`, `ID_ENTREPRISE`) VALUES (:id_list_ent, :id_ent)";
                                        $stmt = $connexion->prepare($sql);
                                        $stmt->bindParam(':id_list_ent', $last_id_list_ent);
                                        $stmt->bindParam(':id_ent', $tab_trav[$i]['list_entreprise'][$j]);
                                        if ($stmt->execute() == 1) {
                                            $state_list_ent_to_ent = 1;
                                        } else {
                                            echo "\nPDO::errorCode(): ", $connexion->errorCode() . "<br/><br/>";
                                            echo "<br/>PDO::errorInfo():<br/>";
                                            print_r($connexion->errorInfo());
                                            echo "<br/><br/>";
                                            $state_list_ent_to_ent = 0;
                                        }
                                        $connexion->commit();
                                    }

                                    // insertion du travaux
                                    $connexion->beginTransaction();
                                    $sql = "INSERT INTO travaux(`ID_LIST_ENTREPRISE`, `ID_EDIFICE`, `MONTANT_MARCHE_TRAVAUX`, `HONORAIRE_TRAVAUX`, `DATE_DEBUT_TRAVAUX`, `DATE_FIN_TRAVAUX`, `DUREE_TRAVAUX`) VALUES (:id_list_ent, :id_edi, :mont, :hono, :ddeb, :dfin, :duree)";
                                    $stmt = $connexion->prepare($sql);
                                    $stmt->bindParam(':id_list_ent', $last_id_list_ent);
                                    $stmt->bindParam(':id_edi', $last_id_edi);
                                    $stmt->bindParam(':mont', $tab_trav[$i]['montant_marche']);
                                    $stmt->bindParam(':hono', $tab_trav[$i]['honoraire']);
                                    $stmt->bindParam(':ddeb', $tab_trav[$i]['date_debut']);
                                    $stmt->bindParam(':dfin', $tab_trav[$i]['date_fin']);
                                    $stmt->bindParam(':duree', $tab_trav[$i]['duree']);
                                    $a = $stmt->execute();
                                    if ($a == 1 && $state_list_ent == 1 && $state_list_ent_to_ent == 1) {
                                        $state_trav = 1;
                                    } else {
                                        /*echo "a= ".$a."<br/>";
                                        echo "state_list_ent= ".$state_list_ent."<br/>";
                                        echo "state_list_ent_to_ent= ".$state_list_ent_to_ent."<br/>";*/
                                        $state_trav = 0;
                                    }
                                    $connexion->commit();
                                }

                                if ($state_arch == 1 && $state_type_ter == 1 && $state_edi == 1 && $state_trav == 1) {
                                    $response = array("result" => $state_arch, "id" => $lastid_arch);
                                } else {
                                    /*echo "state_arch= ".$state_arch."<br/>";
                                    echo "state_type_ter= ".$state_type_ter."<br/>";
                                    echo "state_edi= ".$state_edi."<br/>";
                                    echo "state_trav= ".$state_trav."<br/>";*/
                                    $response = array("result" => false);
                                }
                            } else {
                                echo '<p style="color:red">Erreur de paramètre, les paramètres des travaux ne sont pas correctes</p>';
                            }
                        }
                    }

                    /*echo "\nPDO::errorCode(): ".$connexion->errorCode()."<br/>";
                    print_r($connexion->errorInfo());*/

                    echo json_encode($response);
                } else {
                    echo '<p style="color:red">Erreur de paramètre, les paramètres de l\'archive ne sont pas correctes</p>';
                }

            }
            else {
                //echo "existe";
                $response = array("result" => false, "message" => "il existe déjà un nom d'archive portant ce nom");
                echo json_encode($response);
            }
        }
        else {
            echo '<p style="color:red">Erreur de paramètre, le nom de l\'archive existe déjà n\'est pas correct</p>';
        }



}
else {
    echo '<p style="color:red">Erreur de paramètre, le paramètre type_insert n\'est pas correct</p>';
}