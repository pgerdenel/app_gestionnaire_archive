<?php
/* regroupe l'ensemble des fonctions pdo utilisées dans les scripts */

/* Depuis un id d'archive, renvoie un boolean pr savoir si l'archive existe */
function check_archive_exist_by_id_archive($connexion, $id_arch) {
    $arch_exist=array("result"=>0);

    /* vérifie si l'archive est liée à un édifice (si sizeof(array_result) != 0) veut dire qu'il y a un id_edifice lié */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    // echo "id_edi= ".$id_edifice[0];
    // echo "size= ".sizeof($id_edifice);
    if(sizeof($id_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["id"] = $id_arch[0];
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un nom d'archive, renvoie un tableau avec un boolean pour savoir si l'archive existe et son id d'archive */
function check_archive_exist_by_nom_archive($connexion, $nom_arch) {
    $arch_exist=array("result"=>0);

    /* vérifie si l'archive est liée à un édifice (si sizeof(array_result) != 0) veut dire qu'il y a un id_edifice lié */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE NOM_ARCHIVE=:nom_arch";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':nom_arch', $nom_arch);
    // execution de la requête
    $state = $stmt->execute();
    $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    // echo "id_edi= ".$id_edifice[0];
    // echo "size= ".sizeof($id_edifice);
    if(sizeof($id_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["id"] = $id_arch[0];
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un nom d'édifice, renvoie un tableau avec un boolean pour savoir si l'archive existe et son id d'archive */
function check_archive_exist_by_nom_edifice($connexion, $nom_edifice) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id edifice correspondant au nom d'édifice */
    $connexion->beginTransaction();
    $sql_id_edi = "SELECT ID_EDIFICE FROM edifice WHERE NOM_EDIFICE=:nom_edi";
    $stmt = $connexion->prepare($sql_id_edi);
    $stmt->bindParam(':nom_edi', $nom_edifice);
    // execution de la requête
    $state = $stmt->execute();
    $id_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //echo "id_edi= ".$id_edi[0];

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_edi', $id_edi[0]);
    // execution de la requête
    $state = $stmt->execute();
    $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($id_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["id"] = $id_arch[0];
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un département, renvoie un tableau avec un boolean pour savoir si un édifice existe et ses id edifice */
function check_if_dep_exist_by_dep($connexion, $dep) {
    $data=array("result"=>0, "ids"=>null);

    /* on récupère l'id edifice correspondant au nom d'édifice */
    $connexion->beginTransaction();
    $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE DEPARTEMENT_EDIFICE=:dep";
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':dep', $dep);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //echo "id_edi= ".$id_edi[0];

    if($state == 1 && sizeof($ids_edi) > 0) {
        $data["result"] = 1;
        $data["ids"] = $ids_edi;
        //var_dump($ids_edi);
    }

    return $data;
}
/* Depuis un type d'édifice, renvoie un boolean pour savoir si un édifice existe matchant ce type */
function check_if_edi_exist_for_type_edi($connexion, $id_type_edi) {
    $id_edi_exist = 0;

    /* on récupère l'id edifice correspondant au nom d'édifice */
    $connexion->beginTransaction();
    $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE ID_TYPE_EDIFICE=:id_type_edi";
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':id_type_edi', $id_type_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //echo "id_edi= ".$id_edi[0];

    if($state == 1 && sizeof($ids_edi) > 0) {
        $id_edi_exist = 1;
        //var_dump($ids_edi);
    }

    return $id_edi_exist;
}

/* Depuis une commune, renvoie un tableau avec un boolean pour savoir si un édifice existe et ses id edifice */
function check_if_commu_exist_by_commu($connexion, $commu) {
    $data=array("result"=>0, "ids"=>null);

    /* on récupère l'id edifice correspondant à la commune */
    $connexion->beginTransaction();
    $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE COMMUNE_EDIFICE=:commu";
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':commu', $commu);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //echo "id_edi= ".$id_edi[0];

    if($state == 1 && sizeof($ids_edi) > 0) {
        $data["result"] = 1;
        $data["ids"] = $ids_edi;
        //var_dump($ids_edi);
    }

    return $data;
}
/* Depuis un nom de propriétaire d'archive, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_nom_prop_arch($connexion, $nom_prop) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id proprietaire correspondant au nom propriétaire */
    $connexion->beginTransaction();
    $sql_id_prop = "SELECT ID_PROPRIETAIRE FROM proprietaire WHERE NOM_PROPRIETAIRE=:nom_prop";
    $stmt = $connexion->prepare($sql_id_prop);
    $stmt->bindParam(':nom_prop', $nom_prop);
    // execution de la requête
    $state = $stmt->execute();
    $id_prop = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //echo "id_edi= ".$id_prop[0];

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_PROPRIETAIRE=:id_prop";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_prop', $id_prop[0]);
    // execution de la requête
    $state = $stmt->execute();
    $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($id_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $id_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un id de nom propriétaire d'archive, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_id_prop_arch($connexion, $id_prop) {
    $arch_exist=array("result"=>0);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_PROPRIETAIRE=:id_prop";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_prop', $id_prop);
    // execution de la requête
    $state = $stmt->execute();
    $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($id_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $id_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un nom de type primaire d'archive, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_type_primaire($connexion, $id_type_prim) {
    $arch_exist=array("result"=>0);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_TYPE_PRIMAIRE_ARCHIVE =:id_type_prim";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_type_prim', $id_type_prim);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($ids_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $ids_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un nom de type primaire d'archive, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_type_secondaire($connexion, $id_type_sec) {
    $arch_exist=array("result"=>0);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_TYPE_SECONDAIRE_ARCHIVE =:id_type_sec";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_type_sec', $id_type_sec);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($ids_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $ids_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis un nom de type primaire d'archive, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_type_tertiaire($connexion, $id_type_ter) {
    // $id_type_ter est un string de format "1,2,3" correspondant au ID_TYPE_TER
    $arch_exist=array("result"=>0);
    $array_type_ter = explode(",", $id_type_ter);

    $ids_arch = null;
    /* vérifie si il existe une archive liés à l'id édifice */
    for($i=0;$i<sizeof($array_type_ter);$i++) {
        //echo "id_type_tertiaire= ".$array_type_ter[$i]."<br/>";
        $a=array();
        $connexion->beginTransaction();
        if($i==0) { // 1ere itération on récupère tous les id archives du 1er type tertiaire
            $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_type_tertiaire WHERE ID_TYPE_TERTIAIRE_ARCHIVE =".$array_type_ter[$i];
            $stmt = $connexion->prepare($sql_arch_exist);
            // execution de la requête
            $state = $stmt->execute();
            $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
            //var_dump($ids_arch);
            $connexion->commit();
        }
        else { // autre itération on récupère les id_archives des types tertiaires suivant correspondant aux id archives de la précédente itération
            $a = implode(",", $ids_arch);
            //var_dump($a);
            $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_type_tertiaire WHERE ID_TYPE_TERTIAIRE_ARCHIVE = ".$array_type_ter[$i]." AND ID_ARCHIVE IN (".$a.")";
            $stmt = $connexion->prepare($sql_arch_exist);
            // execution de la requête
            $state = $stmt->execute();
            $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $connexion->commit();
        }
    }

    //var_dump($id_arch);
    if(sizeof($ids_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $ids_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis une année, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_annee($connexion, $an) {
    $arch_exist=array("result"=>0);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $date_debut=$an."-01-01";
    $date_fin=$an."-12-31";
    $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ANNEE_ARCHIVE BETWEEN :date_debut AND :date_fin";
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($ids_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $ids_arch;
        //var_dump($arch_exist);
    }

    return $arch_exist;
}
/* Depuis une commune, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_commune($connexion, $commu) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id édifice des édifices qui contiennent cette commune */
    $connexion->beginTransaction();
    $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE COMMUNE_EDIFICE = :commu";
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_edi_exist);
    $stmt->bindParam(':commu', $commu);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($ids_edi) == 0) { // si aucun id n'a été récupéré
        $arch_exist["result"] = 0;
        $arch_exist["ids"] = null;
        //var_dump($arch_exist);
    }
    else { /* on récupère les id d'archive qui matchent cette liste d'id édifice */
        $connexion->beginTransaction();
        $ids_edi_str = implode(",",$ids_edi);
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
        //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
        $stmt = $connexion->prepare($sql_arch_exist);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        //var_dump($ids_edi);
        if(sizeof($ids_edi) != 0) {
            $arch_exist["result"] = 1;
            $arch_exist["ids"] = $ids_arch;
            //var_dump($arch_exist);
        }
        else {
            $arch_exist["result"] = 0;
            $arch_exist["ids"] = null;
        }
    }

    return $arch_exist;
}
/* Depuis un departement, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_departement($connexion, $dep) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id édifice des édifices qui contiennent cette commune */
    $connexion->beginTransaction();
    $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE DEPARTEMENT_EDIFICE = :dep";
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_edi_exist);
    $stmt->bindParam(':dep', $dep);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($ids_edi) == 0) { // si aucun id n'a été récupéré
        $arch_exist["result"] = 0;
        $arch_exist["ids"] = null;
        //var_dump($arch_exist);
    }
    else { /* on récupère les id d'archive qui matchent cette liste d'id édifice */
        $connexion->beginTransaction();
        $ids_edi_str = implode(",",$ids_edi);
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
        //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
        $stmt = $connexion->prepare($sql_arch_exist);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        //var_dump($ids_edi);
        if(sizeof($ids_edi) != 0) {
            $arch_exist["result"] = 1;
            $arch_exist["ids"] = $ids_arch;
            //var_dump($arch_exist);
        }
        else {
            $arch_exist["result"] = 0;
            $arch_exist["ids"] = null;
        }
    }

    return $arch_exist;
}
/* Depuis un propriétaire d'édifice, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_nom_prop_edi($connexion, $nom_prop_edi) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id propriétaire du nom de propriétaire d'édifice qui correspond à ce nom propriétaire d'édifice*/
    $connexion->beginTransaction();
    $sql_prop_edi_exist = "SELECT ID_PROPRIETAIRE_EDIFICE FROM proprietaire_edifice WHERE NOM_PROPRIETAIRE_EDIFICE = :nom_prop_edi";
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_prop_edi_exist);
    $stmt->bindParam(':nom_prop_edi', $nom_prop_edi);
    // execution de la requête
    $state = $stmt->execute();
    $id_prop = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($id_prop) == 0) { // si aucun id n'a été récupéré
        $arch_exist["result"] = 0;
        $arch_exist["ids"] = null;
        //var_dump($arch_exist);
    }
    else { /* on récupère l'id édifice des édifices qui matchent l'id_propriétaire */
        $connexion->beginTransaction();
        $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE ID_PROPRIETAIRE_EDIFICE = :id_prop_edi";
        //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
        $stmt = $connexion->prepare($sql_edi_exist);
        $stmt->bindParam(':id_prop_edi', $id_prop[0]);
        // execution de la requête
        $state = $stmt->execute();
        $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        if(sizeof($ids_edi) == 0) { // si aucun id n'a été récupéré
            $arch_exist["result"] = 0;
            $arch_exist["ids"] = null;
            //var_dump($arch_exist);
        }
        else { /* on récupère les id d'archive qui matchent cette liste d'id édifice */
            $connexion->beginTransaction();
            $ids_edi_str = implode(",",$ids_edi);
            $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
            //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
            $stmt = $connexion->prepare($sql_arch_exist);
            // execution de la requête
            $state = $stmt->execute();
            $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $connexion->commit();
            //var_dump($ids_edi);
            if(sizeof($ids_edi) != 0) {
                $arch_exist["result"] = 1;
                $arch_exist["ids"] = $ids_arch;
                //var_dump($arch_exist);
            }
            else {
                $arch_exist["result"] = 0;
                $arch_exist["ids"] = null;
            }
        }
    }


    return $arch_exist;
}
/* Depuis un propriétaire d'édifice, renvoie un tableau avec un boolean pour savoir si des archives existes et leur id d'archive */
function get_all_id_archive_of_type_edi($connexion, $type_edi) {
    $arch_exist=array("result"=>0);

    /* on récupère l'id édifice des édifices qui matchent l'id type edifice */
        $connexion->beginTransaction();
        $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE ID_TYPE_EDIFICE = :id_type_edi";
        //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
        $stmt = $connexion->prepare($sql_edi_exist);
        $stmt->bindParam(':id_type_edi', $type_edi);
        // execution de la requête
        $state = $stmt->execute();
        $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        if(sizeof($ids_edi) == 0) { // si aucun id n'a été récupéré
            $arch_exist["result"] = 0;
            $arch_exist["ids"] = null;
            //var_dump($arch_exist);
        }
        else { /* on récupère les id d'archive qui matchent cette liste d'id édifice */
            $connexion->beginTransaction();
            $ids_edi_str = implode(",",$ids_edi);
            $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
            //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
            $stmt = $connexion->prepare($sql_arch_exist);
            // execution de la requête
            $state = $stmt->execute();
            $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $connexion->commit();
            //var_dump($ids_edi);
            if(sizeof($ids_edi) != 0) {
                $arch_exist["result"] = 1;
                $arch_exist["ids"] = $ids_arch;
                //var_dump($arch_exist);
            }
            else {
                $arch_exist["result"] = 0;
                $arch_exist["ids"] = null;
            }
        }

    return $arch_exist;
}
/* Depuis une liste d'id_edifice, renvoie un tableau avec un boolean de résultat de requête et leur id d'archive */
function get_all_id_archive_of_ids_edi($connexion, $array_id_edi) {
    $data=array("result"=>0, "ids"=>null);
    //var_dump($array_id_edi);
    $str_id_edi = implode(",",$array_id_edi);
    $connexion->beginTransaction();
    $sql_ids_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$str_id_edi.")";
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_ids_arch);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_arch);
    if(sizeof($ids_arch) != 0) {
        $data["result"] = 1;
        $data["ids"] = $ids_arch;
        //var_dump($arch_exist);
    }

    return $data;
}

/* Renvoie un array contenant le nom du propriétaire d'archive correspondant à son id d'archive */
function get_nom_prop_arch_from_id_arch($connexion, $id_arch) {
    $nom_prop = null;
    // on récupère l'id_prop_arch de l'archive
    $connexion->beginTransaction();
    $sql_id_prop = "SELECT ID_PROPRIETAIRE FROM archive WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_id_prop);
    $stmt->bindParam(':id_arch', $id_arch);
    $state = $stmt->execute();
    $data_id_prop = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();
    // var_dump($data_nom_prop);
    // echo $data_nom_prop[0];

    $nom_prop = get_nom_prop_arch_from_id_prop_arch($connexion, $data_id_prop[0]);
    return $nom_prop;
}
/* Renvoie un string contenant le nom du propriétaire d'archive correspondant à son id */
function get_nom_prop_arch_from_id_prop_arch($connexion, $id_prop) {
    $connexion->beginTransaction();
    // selectionne tous les ID_TYPE_TER de l'id d'archive
    $sql_nom_prop = "SELECT NOM_PROPRIETAIRE FROM proprietaire WHERE ID_PROPRIETAIRE=:id_prop";
    $stmt = $connexion->prepare($sql_nom_prop);
    $stmt->bindParam(':id_prop', $id_prop);
    $state = $stmt->execute();
    $data_nom_prop = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();
    // var_dump($data_nom_prop);
    // echo $data_nom_prop[0];

    return $data_nom_prop[0];
}
/* Renvoie un array contenant le nom du propriétaire d'archive correspondant à l'id de son propriétaire d'édifice */
function get_nom_prop_arch_from_id_prop_edi($connexion, $_id_prop_edifice) {
    $data=array('result'=>0, 'nom'=>null);
    // on récupère l'id_edifice correspondant à l'id_prop_edifice de edifice
    $connexion->beginTransaction();
    $sql_id_edi = "SELECT ID_EDIFICE FROM edifice WHERE ID_PROPRIETAIRE_EDIFICE=:id_prop_edi";
    $stmt = $connexion->prepare($sql_id_edi);
    //var_dump($_id_prop_edifice);
    $stmt->bindParam(':id_prop_edi', $_id_prop_edifice);
    $state = $stmt->execute();
    $id_edi = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();
    if(sizeof($id_edi) > 0) {
        // on récupère l'id_archive correspondant à l'id_edifice de la table archive_to_edifice
        $connexion->beginTransaction();
        $sql_id_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE=:id_edi";
        $stmt = $connexion->prepare($sql_id_arch);
        $stmt->bindParam(':id_edi', $id_edi[0]);
        $state = $stmt->execute();
        $id_arch = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
        $connexion->commit();
        // var_dump($data_nom_prop);
        if(sizeof($id_arch) > 0) {
            // on récupère l'id_prop_arch de l'id_arc
            $connexion->beginTransaction();
            $sql_id_prop_arch = "SELECT ID_PROPRIETAIRE FROM archive WHERE ID_ARCHIVE=:id_arch";
            $stmt = $connexion->prepare($sql_id_prop_arch);
            $stmt->bindParam(':id_arch', $id_arch[0]);
            $state = $stmt->execute();
            $id_prop_arch = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
            $connexion->commit();
            if(sizeof($id_prop_arch) > 0) {
                $data['result'] = 1;
                $data['nom_prop_arch'] = get_nom_prop_arch_from_id_prop_arch($connexion, $id_prop_arch[0]);
            }
        }
    }

    return $data;
}
/* Renvoie un string contenant le nom du propriétaire d'édifice correspondant à son id */
function get_nom_prop_edi_from_id_prop_edi($connexion, $id_prop_edi) {
    $connexion->beginTransaction();
    $sql_nom_prop_edi = "SELECT NOM_PROPRIETAIRE_EDIFICE FROM proprietaire_edifice WHERE ID_PROPRIETAIRE_EDIFICE=:id_prop_edi";
    $stmt = $connexion->prepare($sql_nom_prop_edi);
    $stmt->bindParam(':id_prop_edi', $id_prop_edi);
    // execution de la requête
    $state = $stmt->execute();
    $nom_prop_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($nom_prop_edi);
    // on insère les données récupérées dans la variable de résultat
    return $nom_prop_edi[0];
}
/* Renvoie un array contenant l'id du propriétaire d'édifice correspondant à son nom de proprietaire */
function get_id_prop_edi_from_nom_prop_edi($connexion, $nom_prop_edi) {
    $data = array("result"=>0,"id"=>null);
    $connexion->beginTransaction();
    $sql_id_prop_edi = "SELECT ID_PROPRIETAIRE_EDIFICE FROM proprietaire_edifice WHERE NOM_PROPRIETAIRE_EDIFICE=:nom_prop_edi";
    $stmt = $connexion->prepare($sql_id_prop_edi);
    $stmt->bindParam(':nom_prop_edi', $nom_prop_edi);
    // execution de la requête
    $state = $stmt->execute();
    $id_prop_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_prop_edi);
    if($state == 1 && sizeof($id_prop_edi) > 0) {
        $data['result'] = 1;
        $data['id'] = $id_prop_edi[0];
    }
    return $data;
}

/* Renvoie un string contenant le nom du prorpiétaire d'édifice correspondant à l'id_d'archive */
function get_nom_prop_edi_from_id_arch($connexion, $id_arch) {
    $nom_prop_edi_r = null;
    // on récupère l'id édifice liés à l'archive
    $id_edi = check_if_arch_link_to_edifice($connexion, $id_arch)['id'];
    $id_prop_edi = get_id_prop_edifice_from_edifice($connexion, $id_edi);
    $connexion->beginTransaction();
    $sql_nom_prop_edi = "SELECT NOM_PROPRIETAIRE_EDIFICE FROM proprietaire_edifice WHERE ID_PROPRIETAIRE_EDIFICE=:id_prop_edi";
    $stmt = $connexion->prepare($sql_nom_prop_edi);
    $stmt->bindParam(':id_prop_edi', $id_prop_edi);
    // execution de la requête
    $state = $stmt->execute();
    $nom_prop_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($nom_prop_edi);
    // on insère les données récupérées dans la variable de résultat
    if($state == 1 && sizeof($nom_prop_edi) > 0) {
        $nom_prop_edi_r = $nom_prop_edi[0];
    }
    return $nom_prop_edi_r;
}
/* Renvoie un string contenant le nom de l'édifice correspondant à l'id_d'archive */
function get_nom_edi_from_id_arch($connexion, $id_arch) {
    $nom_edi_r = null;
    // on récupère l'id édifice liés à l'archive
    $id_edi = check_if_arch_link_to_edifice($connexion, $id_arch)['id'];
    // récupère le nom de l'édifice liés à l'id edifice
    $connexion->beginTransaction();
    $sql_nom_edi = "SELECT NOM_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_nom_edi);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $nom_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($nom_prop_edi);
    // on insère les données récupérées dans la variable de résultat
    if($state == 1 && sizeof($nom_edi) > 0) {
        $nom_edi_r = $nom_edi[0];
    }
    return $nom_edi_r;
}
/* Renvoie un string contenant le nom de l'édifice correspondant à l'id_edi */
function get_nom_edi_from_id_edi($connexion, $id_edi) {
    $nom_edi_r = null;
    // récupère le nom de l'édifice liés à l'id edifice
    $connexion->beginTransaction();
    $sql_nom_edi = "SELECT NOM_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_nom_edi);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $nom_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($nom_prop_edi);
    // on insère les données récupérées dans la variable de résultat
    if($state == 1 && sizeof($nom_edi) > 0) {
        $nom_edi_r = $nom_edi[0];
    }
    return $nom_edi_r;
}
/* Renvoie un string contentant le nom du type primaire de l'id archive (familiale)*/
function get_nom_type_prim($connexion, $id_type_prim) {
    $connexion->beginTransaction();
    // selectionne tous les ID_TYPE_PRIM de l'id d'archive
    $sql_nom_type_prim = "SELECT NOM_TYPE_PRIMAIRE_ARCHIVE FROM type_primaire_archive WHERE ID_TYPE_PRIMAIRE_ARCHIVE=:id_type_prim";
    $stmt = $connexion->prepare($sql_nom_type_prim);
    $stmt->bindParam(':id_type_prim', $id_type_prim);
    $state = $stmt->execute();
    $data_nom_type_prim = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();

    return $data_nom_type_prim[0];
}
/* Renvoie un string contentant les noms des types secondaires de l'id archive (civiles)*/
function get_nom_type_sec($connexion, $id_type_sec) {
    $connexion->beginTransaction();
    // selectionne tous les ID_TYPE_SEC de l'id d'archive
    $sql_nom_type_sec = "SELECT NOM_TYPE_SECONDAIRE_ARCHIVE FROM type_secondaire_archive WHERE ID_TYPE_SECONDAIRE_ARCHIVE=:id_type_sec";
    $stmt = $connexion->prepare($sql_nom_type_sec);
    $stmt->bindParam(':id_type_sec', $id_type_sec);
    $state = $stmt->execute();
    $data_nom_type_sec = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();

    return $data_nom_type_sec[0];
}
/* Renvoie un string contentant les noms des types tertiaires de l'id archive (VIDEO, PLAN)*/
function get_list_nom_type_ter($connexion, $id_arch) {
    /* on récupère les id des types tertiaires de l'archive */
    $connexion->beginTransaction();
    // selectionne tous les ID_TYPE_TER de l'id d'archive
    $sql_all_ter_id = "SELECT ID_TYPE_TERTIAIRE_ARCHIVE FROM archive_to_type_tertiaire WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_all_ter_id);
    $stmt->bindParam(':id_arch', $id_arch);
    $state = $stmt->execute();
    $data_id_ter = $stmt->fetchAll(PDO::FETCH_COLUMN); // tableau indexé int avec résultat
    $connexion->commit();

    /* on récupèreles NOM_TYPE_TER de chaque ID_TYPE_TER */
    $connexion->beginTransaction();
    $comma_separated_id = implode(', ', $data_id_ter); // on formatte le tableau en valeur séparé par des virgules pour le passer en paramètres
    // var_dump($comma_separated_id);
    $sql_all_ter_name = "SELECT NOM_TYPE_TERTIAIRE_ARCHIVE FROM type_tertiaire_archive WHERE ID_TYPE_TERTIAIRE_ARCHIVE IN (" . $comma_separated_id . ")";
    $stmt = $connexion->prepare($sql_all_ter_name);
    // execution de la requête
    $state = $stmt->execute();
    $data_name_ter = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($data_name_ter);
    // on insère les données récupérées dans la variable de résultat
    return implode(', ', $data_name_ter);
}
/* Renvoie un string contenant le nom du type d'édifice correspondant au type_edifice */
function get_nom_type_edi_from_id_type_edi($connexion, $id_type_edi) {
    $connexion->beginTransaction();
    $sql_nom_type_edi = "SELECT NOM_TYPE_EDIFICE FROM type_edifice WHERE ID_TYPE_EDIFICE=:id_type_edi";
    $stmt = $connexion->prepare($sql_nom_type_edi);
    $stmt->bindParam(':id_type_edi', $id_type_edi);
    // execution de la requête
    $state = $stmt->execute();
    $nom_type_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    return $nom_type_edi[0];
}
/* Renvoie un string contenant le nom du type d'édifice correspondant à l'id édifice */
function get_nom_type_edi_from_id_edi($connexion, $id_edi) {
    $nom_type_edi_r = null;
    //echo 'get_nom_type_edi_from_id_edi called with id_edi= '.$id_edi.'<br/>';
    // on récupère l'id type edifice de l'id edifice
    $connexion->beginTransaction();
    $sql_id_type_edi = "SELECT ID_TYPE_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_id_type_edi);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $id_type_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_type_edi);

    // on récupère le nom du type d'édifice depuis l'id type edifice
    $connexion->beginTransaction();
    $sql_nom_type_edi = "SELECT NOM_TYPE_EDIFICE FROM type_edifice WHERE ID_TYPE_EDIFICE=:id_type_edi";
    $stmt = $connexion->prepare($sql_nom_type_edi);
    $stmt->bindParam(':id_type_edi', $id_type_edi[0]);
    // execution de la requête
    $state = $stmt->execute();
    $nom_type_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($nom_type_edi);
    if($state == 1 && sizeof($nom_type_edi) >0) {
        $nom_type_edi_r = $nom_type_edi[0];
    }
    return $nom_type_edi_r;
}
/* Renvoie un boolean indiquant si l'archive est lié à un édifice et l'id edifice */
function check_if_arch_link_to_edifice($connexion, $id_arch) {
    //echo 'check_if_arch_link_to_edifice called with id_arch= '.$id_arch.'<br/>';
    $link_edi = array();
    $connexion->beginTransaction();
    $sql_check_link_edi = "SELECT ID_EDIFICE FROM archive_to_edifice WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_check_link_edi);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $id_edifice = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();

    if(sizeof($id_edifice) != 0) {
        $link_edi["result"] = 1;
        $link_edi["id"] = $id_edifice[0];
        //var_dump($link_edi);
    }
    else {
        $link_edi["result"] = 0;
        $link_edi["id"] = null;
        //var_dump($link_edi);
    }

    return $link_edi;
}
/* Renvoie un boolean indiquant si l'édifice est lié à des travaux et la liste des id_trav */
function check_if_edi_link_to_trav($connexion, $id_edi) {
    $link_trav = array();
    $connexion->beginTransaction();
    $sql_check_link_trav = "SELECT * FROM travaux WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_check_link_trav);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ids_trav = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();

    if(sizeof($ids_trav) != 0) {
        $link_trav["result"] = 1;
        $link_trav["ids"] = $ids_trav;
        //var_dump($ids_trav);
    }
    else {
        $link_trav["result"] = 0;
        $link_trav["ids"] = $ids_trav;
    }

    return $link_trav;
}
/* Renvoie l'id__list_entreprise d'un id_travaux */
function get_list_id_ent_of_id_trav($connexion, $id_trav) {
    $connexion->beginTransaction();
    $sql_id_list_ent = "SELECT ID_LIST_ENTREPRISE FROM travaux WHERE ID_TRAVAUX=:id_trav";
    $stmt = $connexion->prepare($sql_id_list_ent);
    $stmt->bindParam(':id_trav', $id_trav);
    // execution de la requête
    $state = $stmt->execute();
    $id_list_ent = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_list_ent);

    return $id_list_ent[0];
}
/* Renvoie un string contentant les noms des entreprises de l'id travaux (CHARPENTIERPM, FRANC)*/
function get_list_nom_ent($connexion, $id_list_entreprise) {
    /* on récupère les id d'entreprise de la liste des entreprises de ce travaux */
    $connexion->beginTransaction();
    $sql_id_list_ent = "SELECT ID_ENTREPRISE FROM list_entreprise_to_entreprise WHERE ID_LIST_ENTREPRISE=:id_ent";
    $stmt = $connexion->prepare($sql_id_list_ent);
    $stmt->bindParam(':id_ent', $id_list_entreprise);
    // execution de la requête
    $state = $stmt->execute();
    $id_list_ent = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_list_ent);

    /* on récupère le NOM_ENTREPRISE de l'entreprise correspondant à l'ID_ENTREPRISE */
    $connexion->beginTransaction();
    $comma_separated_id_ent = implode(', ', $id_list_ent); // on formatte le tableau en valeur séparé par des virgules pour le passer en paramètres
    //var_dump($comma_separated_id_ent);
    $sql_nom_list_ent = "SELECT NOM_ENTREPRISE FROM entreprise WHERE ID_ENTREPRISE IN (".$comma_separated_id_ent.")";
    $stmt = $connexion->prepare($sql_nom_list_ent);
    // execution de la requête
    $state = $stmt->execute();
    $nom_list_ent = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($nom_list_ent);

    return implode(",", $nom_list_ent);
}
/* Renvoie les informations d'une archive sous forme d'un tableau */
function get_archive_data($connexion, $id_arch) {
    $data_archive  = array();
    /* on récupère les informations de l'archive portant l'id du POST */
    $connexion->beginTransaction();
    $stmt = $connexion->prepare("SELECT ID_ARCHIVE, NOM_ARCHIVE, ID_PROPRIETAIRE, ID_TYPE_PRIMAIRE_ARCHIVE, ID_TYPE_SECONDAIRE_ARCHIVE, ANNEE_ARCHIVE, DATE_ARCHIVAGE_ARCHIVE, EST_PHYSIQUE_ARCHIVE, EST_VIRTUELLE_ARCHIVE, REFERENCE_PHYSIQUE_ARCHIVE, REFERENCE_VIRTUELLE_ARCHIVE FROM ARCHIVE WHERE ID_ARCHIVE=:id_arch");
    // $stmt->bindParam(':nom_arch', $_POST['nom_arch']);
    $stmt->bindParam(':id_arch', $id_arch);
    $state = $stmt->execute();
    $data_arch = $stmt->fetchAll(PDO::FETCH_ASSOC); // tableau indéxé int associatif
    $connexion->commit();
    // var_dump($data_arch);
    // echo $data_arch[0]['ID_ARCHIVE'];
    // on insère les données récupérées dans la variable de résultat
    $data_archive['ID_ARCHIVE'] = $data_arch[0]['ID_ARCHIVE'];
    $data_archive['NOM_ARCHIVE'] = $data_arch[0]['NOM_ARCHIVE'];
    $data_archive['ANNEE_ARCHIVE'] = $data_arch[0]['ANNEE_ARCHIVE'];
    $data_archive['DATE_ARCHIVAGE_ARCHIVE'] = $data_arch[0]['DATE_ARCHIVAGE_ARCHIVE'];
    $data_archive['EST_PHYSIQUE_ARCHIVE'] = ($data_arch[0]['EST_PHYSIQUE_ARCHIVE'] == 1) ? "oui" : "non";
    $data_archive['EST_VIRTUELLE_ARCHIVE'] = ($data_arch[0]['EST_VIRTUELLE_ARCHIVE'] == 1) ? "oui" : "non";
    $data_archive['REFERENCE_PHYSIQUE_ARCHIVE'] = $data_arch[0]['REFERENCE_PHYSIQUE_ARCHIVE'];
    $data_archive['REFERENCE_VIRTUELLE_ARCHIVE'] = $data_arch[0]['REFERENCE_VIRTUELLE_ARCHIVE'];

    /* on récupère le nom de propriétaire de l'id propriétaire correspondant */
    $data_archive['NOM_PROPRIETAIRE'] = get_nom_prop_arch_from_id_prop_arch($connexion, $data_arch[0]['ID_PROPRIETAIRE']);

    /* on récupère le nom du type_primaire correspondant à son ID_TYPE_PRIMAIRE */
    $data_archive['TYPE_PRIMAIRE_ARCHIVE'] = get_nom_type_prim($connexion, $data_arch[0]['ID_TYPE_PRIMAIRE_ARCHIVE']);

    /* on récupère le nom du type_secondaire correspondant à son ID_TYPE_SECONDAIRE */
    $data_archive['TYPE_SECONDAIRE_ARCHIVE'] = get_nom_type_sec($connexion, $data_arch[0]['ID_TYPE_SECONDAIRE_ARCHIVE']);

    /* on récupère les id des types tertiaires de l'archive */
    $data_archive['TYPE_TERTIAIRE_ARCHIVE'] = get_list_nom_type_ter($connexion, $data_arch[0]['ID_ARCHIVE']);

    /* vérifie si l'archive est liée à un édifice (si sizeof(array_result) != 0) veut dire qu'il y a un id_edifice lié */
    $tmp = check_if_arch_link_to_edifice($connexion, $data_arch[0]['ID_ARCHIVE']);
    $link_edi = $tmp['result'];
    $id_edifice = $tmp['id'];

    if ($link_edi) { // si le tableau (résultat de la requête) contient une entrée alors il existe un édifice lié

        $data_archive['is_edi'] = true; // on indique dans la réponse que les données contiennents celles d'un édifice lié à l'archive

        /* On récupère les informations de l'édifice */
        $connexion->beginTransaction();
        $sql_data_edi = "SELECT ID_TYPE_EDIFICE, ID_PROPRIETAIRE_EDIFICE, COMMUNE_EDIFICE, DEPARTEMENT_EDIFICE, NOM_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
        $stmt = $connexion->prepare($sql_data_edi);
        $stmt->bindParam(':id_edi', $id_edifice);
        // execution de la requête
        $state = $stmt->execute();
        $data_edi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $connexion->commit();
        // var_dump($data_edi);
        // echo $data_edi[0]['ID_TYPE_EDIFICE'];
        // on insère les données récupérées dans la variable de résultat
        $data_archive['COMMUNE_EDIFICE'] = $data_edi[0]['COMMUNE_EDIFICE'];
        $data_archive['NOM_EDIFICE'] = $data_edi[0]['NOM_EDIFICE'];
        $data_archive['DEPARTEMENT_EDIFICE'] = $data_edi[0]['DEPARTEMENT_EDIFICE'];

        /* On récupère le nom de propriétaire de l'ID_PRORIETAIRE_EDIFICE */
        $data_archive['NOM_PROPRIETAIRE_EDIFICE'] = get_nom_prop_edi_from_id_prop_edi($connexion, $data_edi[0]['ID_PROPRIETAIRE_EDIFICE']);

        /* on récupère le NOM_TYPE_EDIFICE correspondant à l'ID_TYPE_EDIFICE */
        $data_archive['NOM_TYPE_EDIFICE'] = get_nom_type_edi_from_id_type_edi($connexion, $data_edi[0]['ID_TYPE_EDIFICE']);

        /* vérifie si l'édifice est liée à des travaux (si sizeof(array_result) != 0) veut dire qu'il y a des travaux lié */
        $tmp_trav = check_if_edi_link_to_trav($connexion, $id_edifice);
        $link_trav = $tmp_trav['result'];
        $ids_trav = $tmp_trav['ids'];
        // var_dump($ids_trav);

        if ($link_trav) { // si le tableau (résultat de la requête) contient une entrée alors il existe un/des travaux liés

            $data_archive['is_travaux'] = true; // on indique dans la réponse que les données contiennents celles de travaux lié à l'édifice
            $tab_trav = array();

            /* on récupère les données de chaque travaux */
            for ($i = 0; $i < sizeof($ids_trav); $i++) {

                $format_trav = array();
                $trav = array();

                $connexion->beginTransaction();
                $sql_data_trav = "SELECT ID_LIST_ENTREPRISE, MONTANT_MARCHE_TRAVAUX, HONORAIRE_TRAVAUX, DATE_DEBUT_TRAVAUX, DATE_FIN_TRAVAUX, DUREE_TRAVAUX FROM travaux WHERE ID_TRAVAUX=:id_trav";
                $stmt = $connexion->prepare($sql_data_trav);
                $stmt->bindParam(':id_trav', $ids_trav[$i]);
                // execution de la requête
                $state = $stmt->execute();
                $trav = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $connexion->commit();
                // var_dump($trav);

                // on insère les données récupérées dans la variable d'un travaux
                $format_trav['ID_TRAVAUX'] = $ids_trav[$i];
                $format_trav['MONTANT_MARCHE_TRAVAUX'] = $trav[0]['MONTANT_MARCHE_TRAVAUX'];
                $format_trav['HONORAIRE_TRAVAUX'] = $trav[0]['HONORAIRE_TRAVAUX'];
                $format_trav['DATE_DEBUT_TRAVAUX'] = $trav[0]['DATE_DEBUT_TRAVAUX'];
                $format_trav['DATE_FIN_TRAVAUX'] = $trav[0]['DATE_FIN_TRAVAUX'];
                $format_trav['DUREE_TRAVAUX'] = $trav[0]['DUREE_TRAVAUX'];

                /* on récupère le NOM_ENTREPRISE de l'entreprise correspondant à l'ID_ENTREPRISE */
                $format_trav['LIST_ENTREPRISE'] = get_list_nom_ent($connexion, $trav[0]['ID_LIST_ENTREPRISE']);
                // var_dump($format_trav);
                // on insère les données d'un travaux complet dans le tableau de travaux
                array_push($tab_trav, $format_trav);

            }
            // on insère les données récupérées dans la variable de résultat
            $data_archive['travaux'] = $tab_trav;
        }

    }

    return $data_archive;
}
/* Récupère l'id_prop_edifice qui matchent l'id_edifice */
function get_id_prop_edifice_from_edifice($connexion, $id_edifice) {
    $id_prop_edifice = null;

    $connexion->beginTransaction();
    $sql_id_edi = "SELECT ID_PROPRIETAIRE_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_id_edi);
    $stmt->bindParam(':id_edi', $id_edifice);
    // execution de la requête
    $state = $stmt->execute();
    $id_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1 && sizeof($id_edi) > 0) {
        $id_prop_edifice =  $id_edi[0];
    }
    return $id_prop_edifice;
}
/* Récupère le département qui matchent l'id_edifice */
function get_dep_from_edifice($connexion, $id_edi) {
    $data = array();
    $data['result'] = 0;

    $connexion->beginTransaction();
    $sql_dep = "SELECT DEPARTEMENT_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_dep);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $dep = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1) {
        $data['result'] = 1;
        $data['dep'] = $dep;
    }

    return $data;
}
/* Récupère le département qui matchent l'id_archive */
function get_dep_from_id_arch($connexion, $id_arch) {
    $data = array();
    $data['result'] = 0;
    // on recupère l'id edifice matchant l'id_arch dans archive_to_edifice
    $connexion->beginTransaction();
    $sql_id_edi = "SELECT ID_EDIFICE FROM archive_to_edifice WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_id_edi);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $id_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1) {
        $data['result'] = 1;
        $data['dep'] = get_dep_from_edifice($connexion, $id_edi[0]);
    }

    return $data;
}
/* Récupère la commune qui matchent l'id_edifice */
function get_commu_from_edifice($connexion, $id_edi) {
    $data = array();
    $data['result'] = 0;

    $connexion->beginTransaction();
    $sql_commu = "SELECT COMMUNE_EDIFICE FROM edifice WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_commu);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $commu = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($commu);
    if($state == 1) {
        $data['result'] = 1;
        $data['commu'] = $commu;
    }

    return $data;
}
/* Récupère la commune qui matchent l'id_archive */
function get_commu_from_id_arch($connexion, $id_arch) {
    $data = array("result"=>0, "commu"=>null);
    // on recupère l'id edifice matchant l'id_arch dans archive_to_edifice
    $connexion->beginTransaction();
    $sql_id_edi = "SELECT ID_EDIFICE FROM archive_to_edifice WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_id_edi);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $id_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1) {
        $data['result'] = 1;
        $data['commu'] = get_commu_from_edifice($connexion, $id_edi[0])['commu'][0];
    }

    return $data;
}
/* Récupère le commentaire physique qui matchent l'id_archive */
function get_com_phys_from_id_arch($connexion, $id_arch) {
    $data = array("result"=>0, "com_phys"=>null);
    // on recupère l'id edifice matchant l'id_arch dans archive_to_edifice
    $connexion->beginTransaction();
    $sql_com_phys = "SELECT REFERENCE_PHYSIQUE_ARCHIVE  FROM archive WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_com_phys);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $com_phys = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1) {
        $data['result'] = 1;
        $data['com_phys'] = $com_phys[0];
    }

    return $data;
}
/* Récupère le commentaire virtuelle qui matchent l'id_archive */
function get_com_virt_from_id_arch($connexion, $id_arch) {
    $data = array("result"=>0, "com_phys"=>null);
    // on recupère l'id edifice matchant l'id_arch dans archive_to_edifice
    $connexion->beginTransaction();
    $sql_com_virt = "SELECT REFERENCE_VIRTUELLE_ARCHIVE FROM archive WHERE ID_ARCHIVE=:id_arch";
    $stmt = $connexion->prepare($sql_com_virt);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $com_virt = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($id_edi);
    if($state == 1) {
        $data['result'] = 1;
        $data['com_virt'] = $com_virt[0];
    }

    return $data;
}

/* supprime les travaux qui matchent la liste d'id_travaux  */
function del_travaux_in_travaux($connexion, $ids_trav) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM travaux WHERE ID_TRAVAUX IN (".$ids_trav.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    //var_dump($ids_trav);

    return $row_count;
}
/* supprime les entrées de list_entreprise_to_entreprise qui matchent la liste des id_list_ntreprise  */
function del_list_ent_in_list_ent_to_ent($connexion, $ids_list_entreprise) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM list_entreprise_to_entreprise WHERE ID_LIST_ENTREPRISE IN (".$ids_list_entreprise.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime les entrées de list_entreprise_to_entreprise qui matchent la liste des id_list_ntreprise  */
function del_list_ent_in_list_ent($connexion, $ids_list_entreprise) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM list_entreprise WHERE ID_LIST_ENTREPRISE IN (".$ids_list_entreprise.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime les entrée de archive_to_edifice qui matchent l'id_edifice */
function del_edifice_in_archive_to_edifice($connexion, $id_edifice) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$id_edifice.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime un édifice par son id_edifice */
function del_edifice($connexion, $id_edifice) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM edifice WHERE ID_EDIFICE IN (".$id_edifice.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime un propriétaire d'édifice par son id_prop_edifice */
function del_prop_edifice($connexion, $id_prop_edifice) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM proprietaire_edifice WHERE ID_PROPRIETAIRE_EDIFICE IN (".$id_prop_edifice.")";
    $stmt = $connexion->prepare($sql_del);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime les entrées de archive_to_type_tertiaire qui matchent l'id_archive */
function del_arch_in_arch_to_type_ter($connexion, $id_arch) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM archive_to_type_tertiaire WHERE ID_ARCHIVE = :id_arch";
    $stmt = $connexion->prepare($sql_del);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}
/* supprime les entrées de archive qui matchent l'id_archive */
function del_arch_in_arch($connexion, $id_arch) {
    $row_count = null;
    $connexion->beginTransaction();
    //var_dump($comma_separated_id_ent);
    $sql_del = "DELETE FROM archive WHERE ID_ARCHIVE = :id_arch";
    $stmt = $connexion->prepare($sql_del);
    $stmt->bindParam(':id_arch', $id_arch);
    // execution de la requête
    $state = $stmt->execute();
    $row_count = $stmt->rowCount();
    $connexion->commit();
    // var_dump($nom_list_ent);

    return $row_count;
}

/* renvoie le montant de marché total de tous les travaux d'un édifice */
function get_all_mm_of_edifice($connexion, $id_edi) {
    $t_mm = null;
    $connexion->beginTransaction();
    $sql_mm = "SELECT MONTANT_MARCHE_TRAVAUX FROM travaux WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_mm);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ar_mm = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    // var_dump($ar_mm);

    for($i=0;$i<sizeof($ar_mm);$i++) {
        $t_mm += intval($ar_mm[$i]);
    }

    return $t_mm;

}
/* renvoie les honoraires totaaux de tous les travaux d'un édifice */
function get_all_hono_of_edifice($connexion, $id_edi) {
    $t_hono = null;
    $connexion->beginTransaction();
    $sql_h = "SELECT HONORAIRE_TRAVAUX FROM travaux WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_h);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ar_h = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($ar_h);

    for($i=0;$i<sizeof($ar_h);$i++) {
        $t_hono += intval($ar_h[$i]);
    }

    return $t_hono;

}
/* renvoie la date de début de travaux du premier travaux et la date de fin de travaux du dernier travaux d'un id_edifice */
function get_interval_date_of_travaux_edifice($connexion, $id_edi) {
    $data = null;
    $data['result'] = 0;
    $data['dates'] = null;
    $date_interval = array();

    // on récupère les ID_TRAVAUX de l'édifice
    $connexion->beginTransaction();
    $sql_ids_trav = "SELECT ID_TRAVAUX FROM travaux WHERE ID_EDIFICE=:id_edi";
    $stmt = $connexion->prepare($sql_ids_trav);
    $stmt->bindParam(':id_edi', $id_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ids_trav = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    /*var_dump($ids_trav);
    echo 'id_trav_date_deb= '.$ids_trav[0].'<br/>';
    echo 'id_trav_fin_deb= '.$ids_trav[sizeof($ids_trav)-1].'<br/>';*/
    if($state == 1 && sizeof($ids_trav) > 0) {
        // on récupère la date de début du 1er id de travaux
        $connexion->beginTransaction();
        $sql_date_deb_trav = "SELECT DATE_DEBUT_TRAVAUX FROM travaux WHERE ID_TRAVAUX=:id_trav1";
        $stmt = $connexion->prepare($sql_date_deb_trav);
        $stmt->bindParam(':id_trav1', $ids_trav[0]);
        // execution de la requête
        $state = $stmt->execute();
        $date_deb_trav = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        // var_dump($date_deb_trav);
        $date_interval[0] = $date_deb_trav[0];
        if($state == 1 && $date_deb_trav != null) {
            // on récupère la date de fin du dernier id de travaux
            $connexion->beginTransaction();
            $sql_date_fin_trav = "SELECT DATE_FIN_TRAVAUX FROM travaux WHERE ID_TRAVAUX=:id_trav2";
            $stmt = $connexion->prepare($sql_date_fin_trav);
            $stmt->bindParam(':id_trav2', $ids_trav[sizeof($ids_trav)-1]);
            // execution de la requête
            $state = $stmt->execute();
            $date_fin_trav = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $connexion->commit();
            // var_dump($date_fin_trav);
            $date_interval[1] = $date_fin_trav[0];
            if($state == 1 && $date_fin_trav != null) {
                $data['result'] = 1;
                $data['dates'] = $date_interval;
            }
        }
    }

    /*echo 'id_trav_date_deb= '.$date_interval['d_deb'].'<br/>';
    echo 'id_trav_fin_deb= '.$date_interval['d_fin'].'<br/>';*/

    return $data;
}

/* execute la requête query 1
 * propriétaire_archive         // get_all_id_archive_of_id_prop_arch
 * proprietaire_edifice         // get_all_id_archive_of_prop_edi
 * nom_edifice                  // check_archive_exist_by_nom_edifice
 * type_edifice                 // get_all_id_archive_of_type_edi
 * departement                  // get_all_id_archive_of_departement
 * commune                      // get_all_id_archive_of_commune
 * date_debut_premier_travaux   // get_interval_date_of_travaux_edifice[0]
 * date_fin_dernier_travaux     // get_interval_date_of_travaux_edifice[1]
 * montant_de_marche            // get_all_mm_of_edifice
 * honoraire                    // get_all_hono_of_edifice
 */
function query_1_get_data($connexion, $param, $crit) {
    $data = array();
    $id_arch =$param['id_arch'];
    $prop_arch = null;
    $prop_edi = null;
    $id_edi = null;
    $nom_edi = null;
    $type_edi = null;
    $dep = null;
    $commu = null;
    $com_phys = null;
    $com_virt = null;
    $dd = null;
    $df = null;
    $mm = null;
    $hono = null;

    //echo 'query_1_get_data called with crit= ';
    switch($crit) {
        case 'p_arch':
            //echo 'query_1_get_data for p_arch<br/>';
            if($param['comp'] == "eq") {
                $prop_arch = get_nom_prop_arch_from_id_prop_arch($connexion, $param['id_prop_arch']); // $param = id_prop
            }
            else {
                $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            }
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
                $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
                //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            if($id_edi != null) { // on vérifie si l'archive est lié à un édifice
                $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
                $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
                $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
                $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
                $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];

                if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                    $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                    $dd = $result_dates_tmp['dates'][0];
                    $df = $result_dates_tmp['dates'][1];
                    $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                    $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
                }
                else {
                    $result_dates_tmp = "";
                    $dd = "";
                    $df = "";
                    $mm = "";
                    $hono = "";
                }

            }
            else {
                $type_edi = "";
                $dep = "";
                $commu = "";
                $com_phys = "";
                $com_virt = "";
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }

            break;
        case 'p_edi':
            // param (id_arch, nom_prop_edi, id_prop_edi)
            //var_dump($param['id_prop_edi']);
            $prop_arch = get_nom_prop_arch_from_id_prop_edi($connexion, $param['id_prop_edi'])['nom_prop_arch']; // $param = id_prop_edi
            if($param['comp'] == "eq") {
                $prop_edi = $param['nom_prop_edi'];
            }
            else {
                $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            }
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $dd = $result_dates_tmp['dates'][0];
                $df = $result_dates_tmp['dates'][1];
                $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            }
            else {
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
        case 'dep':
            // param ("id_arch", "dep", "comp")
            //var_dump($param['dep']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']); // $param = id_prop_edi
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);

            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            if($param['comp'] == "eq") {
                $dep = $param['dep'];
            }
            else {
                $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            }
            $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $dd = $result_dates_tmp['dates'][0];
                $df = $result_dates_tmp['dates'][1];
                $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            }
            else {
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
        case 'commu':
            // param ("id_arch", "commu", "comp")
            //var_dump($param['commu']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']); // $param = id_prop_edi
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            $id_edi = $tmp_id_edi;
            $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            if($param['comp'] == "eq") {
                $commu = $param['commu'];
            }
            else {
                $commu = get_commu_from_id_arch($connexion, $param['id_arch'])['commu'];
            }
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $dd = $result_dates_tmp['dates'][0];
                $df = $result_dates_tmp['dates'][1];
                $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            }
            else {
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
        case 'com_phys':
            //echo 'query_1_get_data for p_arch<br/>';
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            if($id_edi != null) { // on vérifie si l'archive est lié à un édifice
                $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
                $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
                $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
                $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
                if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                    $dd = $result_dates_tmp['dates'][0];
                    $df = $result_dates_tmp['dates'][1];
                    $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                    $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
                }
                else {
                    $result_dates_tmp = "";
                    $dd = "";
                    $df = "";
                    $mm = "";
                    $hono = "";
                }
            }
            else {
                $type_edi = "";
                $dep = "";
                $commu = "";
                $com_phys = "";
                $com_virt = "";
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
        case 'com_virt':
            //echo 'query_1_get_data for p_arch<br/>';
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            if($id_edi != null) { // on vérifie si l'archive est lié à un édifice
                $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
                $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
                $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
                $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];

                if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                    $dd = $result_dates_tmp['dates'][0];
                    $df = $result_dates_tmp['dates'][1];
                    $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                    $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
                }
                else {
                    $result_dates_tmp = "";
                    $dd = "";
                    $df = "";
                    $mm = "";
                    $hono = "";
                }
            }
            else {
                $type_edi = "";
                $dep = "";
                $commu = "";
                $com_phys = "";
                $com_virt = "";
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
        case 't_edi':
            // param (id_arch, id_typ_edi, id_prop_edi)
            //var_dump($param['id_typ_edi']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            if($param['comp'] == "eq") {
                $type_edi = get_nom_type_edi_from_id_type_edi($connexion, $param['t_edi']);
            }
            else {
                $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            }
            $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
                $dd = $result_dates_tmp['dates'][0];
                $df = $result_dates_tmp['dates'][1];
                $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
                $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            }
            else {
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }

            break;
        case 'mm':
            // param (id_arch, id_typ_edi, id_prop_edi)
            //var_dump($param['id_typ_edi']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
            $dd = $result_dates_tmp['dates'][0];
            $df = $result_dates_tmp['dates'][1];
            if($param['comp'] == "eq") {
             $mm = $param['mm'];
            }
            else {
                $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
            }
            $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            break;
        case 'hono':
            // param (id_arch, id_typ_edi, id_prop_edi)
            //var_dump($param['id_typ_edi']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $tmp_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch'])['id'];
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            $id_edi = $tmp_id_edi;
            $type_edi = get_nom_type_edi_from_id_edi($connexion, $tmp_id_edi);
            $dep = get_dep_from_edifice($connexion, $tmp_id_edi)['dep'][0];
            $commu = get_commu_from_edifice($connexion, $tmp_id_edi)['commu'][0];
            $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
            $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];
            $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $tmp_id_edi);
            $dd = $result_dates_tmp['dates'][0];
            $df = $result_dates_tmp['dates'][1];
            $mm = get_all_mm_of_edifice($connexion, $tmp_id_edi);
            if($param['comp'] == "eq") {
             $hono = $param['hono'];
            }
            else {
                $hono = get_all_hono_of_edifice($connexion, $tmp_id_edi);
            }
            break;
        case 'multi_crit':
            // param (id_arch, id_typ_edi, id_prop_edi)
            //var_dump($param['id_typ_edi']);
            $prop_arch = get_nom_prop_arch_from_id_arch($connexion, $param['id_arch']);
            $prop_edi = get_nom_prop_edi_from_id_arch($connexion, $param['id_arch']);
            $nom_edi = get_nom_edi_from_id_arch($connexion, $param['id_arch']);
            $result_id_edi = check_if_arch_link_to_edifice($connexion, $param['id_arch']);
            //echo 'query_1_data_ tmp_id_edi= '.$tmp_id_edi.'<br/>';
            if($result_id_edi['result'] == 1) { // on vérifie si l'archive est lié à un édifice
                $id_edi = $result_id_edi['id'];
                $type_edi = get_nom_type_edi_from_id_edi($connexion, $id_edi);
                $dep = get_dep_from_edifice($connexion, $id_edi)['dep'][0];
                $commu = get_commu_from_edifice($connexion, $id_edi)['commu'][0];
                $com_phys = get_com_phys_from_id_arch($connexion, $param['id_arch'])['com_phys'];
                $com_virt = get_com_virt_from_id_arch($connexion, $param['id_arch'])['com_virt'];

                if(check_if_edi_link_to_trav($connexion, $id_edi)['result'] == 1) { // on vérifie si l'archive est lié à des travaux
                $result_dates_tmp = get_interval_date_of_travaux_edifice($connexion, $id_edi);
                $dd = $result_dates_tmp['dates'][0];
                $df = $result_dates_tmp['dates'][1];
                $mm = get_all_mm_of_edifice($connexion, $id_edi);
                $hono = get_all_hono_of_edifice($connexion, $id_edi);
                }
                else {
                    $result_dates_tmp = "";
                    $dd = "";
                    $df = "";
                    $mm = "";
                    $hono = "";
                }
            }
            else {
                $type_edi = "";
                $dep = "";
                $commu = "";
                $com_phys = "";
                $com_virt = "";
                $result_dates_tmp = "";
                $dd = "";
                $df = "";
                $mm = "";
                $hono = "";
            }
            break;
    }

    // on met les données récupérés dans le tableau
    $data['id_arch'] = $id_arch;
    $data['prop_arch'] = $prop_arch;
    $data['id_edi'] = $id_edi;
    $data['prop_edi'] = $prop_edi;
    $data['nom_edi'] = $nom_edi;
    $data['type_edi'] = $type_edi;
    $data['dep'] = $dep;
    $data['commu'] = $commu;
    $data['com_phys'] = $com_phys;
    $data['com_virt'] = $com_virt;
    $data['dd'] = $dd;
    $data['df'] = $df;
    $data['mm'] = $mm;
    $data['hono'] = $hono;

    return $data;

}

// Fonctions pour un seul critère
/* Fonction permettant de récupérer les ID archives matchant le critère id_propriétaire avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_id_prop_arch_and_comp($connexion, $id_prop, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_id_prop_arch_and_comp called<br/>';
    $ids_arch = null;

    //echo 'id_prop= '.$id_prop.'<br/>';
    //echo 'comp= '.$comp.'<br/>';
    // on récupère toute les archives du prop

    $arch_exist=array("result"=>0);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist="";
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_PROPRIETAIRE =:id_prop";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE ID_PROPRIETAIRE !=:id_prop";
    }
    else {
        echo 'comp non reconnu'.$comp.'<br/>';
    }

    $stmt = $connexion->prepare($sql_arch_exist);
    $stmt->bindParam(':id_prop', $id_prop);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($ids_arch) != 0) {
        $arch_exist["result"] = 1;
        $arch_exist["ids"] = $ids_arch;

    }
    else {
        $arch_exist["ids"] = null;
    }
    //var_dump($arch_exist);

    return $arch_exist;
}
/* Fonction permettant de récupérer les ID archives matchant le critère id_propriétaire édifice avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_id_prop_edi_and_comp($connexion, $id_prop_edi, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_id_prop_edi_and_comp called';
    $data=array("result"=>0, "ids"=>null);
    $sql_ids_edi = "";
    // on récupère les id_edifice matchant l'id edifice et son critère dans edifice
    $connexion->beginTransaction();
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE ID_PROPRIETAIRE_EDIFICE =:id_prop_edi";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE ID_PROPRIETAIRE_EDIFICE !=:id_prop_edi";
    }
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':id_prop_edi', $id_prop_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if($state == 0) { // si aucun id n'a été récupéré
        $data["result"] = 0;
        $data["ids"] = null;
        //var_dump($data);
    }
    else { /* on récupère l'id archive qui matchent les id_edifice */
        $connexion->beginTransaction();
        $ids_edi_str = implode(",",$ids_edi);
        $sql_ids_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
        $stmt = $connexion->prepare($sql_ids_arch);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        if($state == 1) { // si des id ont été récupéré
            $data["result"] = 1;
            $data["ids"] = $ids_arch;
            //var_dump($data);
        }
    }

    return $data;
}
/* Fonction permettant de récupérer les ID archives matchant le critère departement avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_dep_edi_and_comp($connexion, $dep, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_dep_edi_and_comp called';
    //var_dump($dep);
    $data=array("result"=>0, "ids"=>null);
    $sql_ids_edi = "";
    // on récupère les id_edifice matchant le dep et son critère dans edifice
    $connexion->beginTransaction();
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE DEPARTEMENT_EDIFICE =:dep";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE DEPARTEMENT_EDIFICE !=:dep";
    }
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':dep', $dep);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if($state == 0 && sizeof($ids_edi) > 0) { // si aucun id n'a été récupéré
        $data["result"] = 0;
        $data["ids"] = null;
        //var_dump($data);
    }
    else { /* on récupère l'id archive qui matchent les id_edifice */
        $connexion->beginTransaction();
        $ids_edi_str = implode(",",$ids_edi);
        $sql_ids_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
        $stmt = $connexion->prepare($sql_ids_arch);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        if($state == 1) { // si aucun id n'a été récupéré
            $data["result"] = 1;
            $data["ids"] = $ids_arch;
            //var_dump($data);
        }
    }

    return $data;
}
/* Fonction permettant de récupérer les ID archives matchant le critère commune avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_commu_edi_and_comp($connexion, $commu, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_commu_edi_and_comp called';
    //var_dump($commu);
    $data=array("result"=>0, "ids"=>null);
    $sql_ids_edi = "";
    // on récupère les id_edifice matchant la commu et son critère dans edifice
    $connexion->beginTransaction();
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE COMMUNE_EDIFICE =:commu";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_ids_edi = "SELECT ID_EDIFICE FROM edifice WHERE COMMUNE_EDIFICE !=:commu";
    }
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':commu', $commu);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($ids_edi);
    if($state == 1 && sizeof($ids_edi) > 0) { // si des  id sont récupérés
        /* on récupère l'id archive qui matchent les id_edifice */
        $connexion->beginTransaction();
        $ids_edi_str = implode(",",$ids_edi);
        $sql_ids_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$ids_edi_str.")";
        $stmt = $connexion->prepare($sql_ids_arch);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        if($state == 1 && sizeof($ids_arch) > 0) { // si aucun id n'a été récupéré
            $data["result"] = 1;
            $data["ids"] = $ids_arch;
            //var_dump($ids_arch);
        }
    }

    return $data;
}
/* Fonction permettant de récupérer les ID archives matchant le critère com_phys avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_com_phys_and_comp($connexion, $com_phys, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_id_prop_arch_and_comp called<br/>';
    $data =array("result"=>0, 'ids'=>null);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist="";
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE REFERENCE_PHYSIQUE_ARCHIVE  LIKE '%".$com_phys."%' ";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE REFERENCE_PHYSIQUE_ARCHIVE NOT LIKE '%".$com_phys."%' ";
    }
    else {
        echo 'comp non reconnu'.$comp.'<br/>';
    }

    $stmt = $connexion->prepare($sql_arch_exist);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($ids_arch) != 0) {
        $data["result"] = 1;
        $data["ids"] = $ids_arch;

    }
    //var_dump($data);

    return $data;
}
/* Fonction permettant de récupérer les ID archives matchant le critère com_virt avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_com_virt_and_comp($connexion, $com_virt, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_com_virt_and_comp called<br/>';
    $data =array("result"=>0, 'ids'=>null);

    /* vérifie si il existe une archive liés à l'id édifice */
    $connexion->beginTransaction();
    $sql_arch_exist="";
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE REFERENCE_VIRTUELLE_ARCHIVE   LIKE '%".$com_virt."%' ";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_arch_exist = "SELECT ID_ARCHIVE FROM archive WHERE REFERENCE_VIRTUELLE_ARCHIVE  NOT LIKE '%".$com_virt."%' ";
    }
    else {
        echo 'comp non reconnu'.$comp.'<br/>';
    }

    $stmt = $connexion->prepare($sql_arch_exist);
    // execution de la requête
    $state = $stmt->execute();
    $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if(sizeof($ids_arch) != 0) {
        $data["result"] = 1;
        $data["ids"] = $ids_arch;

    }
    //var_dump($data);

    return $data;
}
/* Fonction permettant de récupérer les ID archives matchant le critère com_virt avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_id_type_edi_and_comp($connexion, $t_edi, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_id_type_edi_and_comp called<br/>';
    $data =array("result"=>0, 'ids'=>null);

    /* vérifie si il existe un ou des édifices liés à l'id type édifice */

    $connexion->beginTransaction();
    $sql_edi_exist="";
    if($comp == "eq") {
        //echo 'comp eq called<br/>';
        $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE ID_TYPE_EDIFICE  = :id_type_edi";
    }
    else if($comp == "neq") {
        //echo 'comp neq called<br/>';
        $sql_edi_exist = "SELECT ID_EDIFICE FROM edifice WHERE ID_TYPE_EDIFICE  != :id_type_edi";
    }
    else {
        echo 'comp non reconnu'.$comp.'<br/>';
    }

    $stmt = $connexion->prepare($sql_edi_exist);
    $stmt->bindParam(':id_type_edi', $t_edi);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    //var_dump($ids_edi);
    if($state == 1 && sizeof($ids_edi) > 0) {

        $str_ids_edi = implode(",",$ids_edi);
        $connexion->beginTransaction();
        $sql_ids_arch = "SELECT ID_ARCHIVE FROM archive_to_edifice WHERE ID_EDIFICE IN (".$str_ids_edi.")";
        $stmt = $connexion->prepare($sql_ids_arch);
        // execution de la requête
        $state = $stmt->execute();
        $ids_arch = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $connexion->commit();
        //var_dump($ids_arch);
        if($state == 1 && sizeof($ids_arch) > 0) {
            $data['result'] = 1;
            $data['ids'] = $ids_arch;
        }

    }
    // var_dump($data);

    return $data;
}
/* Fonction permettant de récupérer les ID édifices matchant le critère montant de marché avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_mm_and_comp($connexion, $mm, $comp) {
    //echo 'query_1_one_crit_get_id_arch_from_mm_and_comp called with mm='.$comp.' à '.$mm.'<br/>';
    $data=array("result"=>0, "ids"=>null);

    /* on récupère les id_edifice dans travaux qui matchent le montant de marché TOTAL DE TOUS LEURS TRAVAUX et son critère */
    $connexion->beginTransaction();
    if($comp == "eq") {
        $sql_ids_edi = "SELECT ID_EDIFICE from total_mm_travaux_edifice WHERE MONTANT_MARCHE_TOTAL_TRAVAUX = :mm";
    }
    else if($comp == "inf"){
        $sql_ids_edi = "SELECT ID_EDIFICE from total_mm_travaux_edifice WHERE MONTANT_MARCHE_TOTAL_TRAVAUX < :mm";
    }
    else if($comp == "sup"){
        $sql_ids_edi = "SELECT ID_EDIFICE from total_mm_travaux_edifice WHERE MONTANT_MARCHE_TOTAL_TRAVAUX > :mm";
    }
    else {
        echo "opérateur de comparaison non reconnu = ".$comp."<br/>";
    }
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':mm', $mm);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if($state == 1 && sizeof($ids_edi) > 0) { // si aucun id n'a été récupéré
        $data["result"] = 1;
        $data["ids"] = get_all_id_archive_of_ids_edi($connexion, $ids_edi)['ids'];
        //var_dump($ids_edi);
    }

    return $data;
}
/* Fonction permettant de récupérer les ID édifices matchant le critère montant de marché avec son opérateur de comparaison */
function query_1_one_crit_get_id_arch_from_hono_and_comp($connexion, $hono, $comp) {
    $data=array("result"=>0, "ids"=>null);

    /* on récupère les id_edifice dans travaux qui matchent le montant des honoraires TOTAL DE TOUS LEURS TRAVAUX et son critère */
    $connexion->beginTransaction();
    if($comp == "eq") {
        $sql_ids_edi = "SELECT ID_EDIFICE from total_hono_travaux_edifice WHERE HONORAIRE_TOTAL_TRAVAUX = :hono";
    }
    else if($comp == "inf"){
        $sql_ids_edi = "SELECT ID_EDIFICE from total_hono_travaux_edifice WHERE HONORAIRE_TOTAL_TRAVAUX < :hono";
    }
    else if($comp == "sup"){
        $sql_ids_edi = "SELECT ID_EDIFICE from total_hono_travaux_edifice WHERE HONORAIRE_TOTAL_TRAVAUX > :hono";
    }
    else {
        echo "opérateur de comparaison non reconnu = ".$comp."<br/>";
    }
    //echo "sql_arch_exist=".$sql_arch_exist."<br/>";
    $stmt = $connexion->prepare($sql_ids_edi);
    $stmt->bindParam(':hono', $hono);
    // execution de la requête
    $state = $stmt->execute();
    $ids_edi = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $connexion->commit();
    if($state == 1 && sizeof($ids_edi) > 0) { // si aucun id n'a été récupéré
        $data["result"] = 1;
        $data["ids"] = get_all_id_archive_of_ids_edi($connexion, $ids_edi)['ids'];
        //var_dump($ids_edi);
    }

    return $data;
}

// Fonctions pour plusieurs critères
/* Renvoie les id_arch matchant l'ensemble des critères(valeur, opérateur de liaison, opérateur de jointure) */
function query_1_several_crits_get_id_arch_from_crit($connexion, $data_request) {
    $ids_arch_full = array();
    $data=array("result"=>0, "message"=>"", "ids_arch_full"=>null);
    $error = false;
    $ids_arch__p_arch = array();
    $ids_arch__p_edi = array();
    $ids_arch__dep = array();
    $ids_arch__commu = array();
    $ids_arch__com_phys = array();
    $ids_arch__com_virt = array();
    $ids_arch__t_edi = array();
    $ids_arch__mm = array();
    $ids_arch__hono = array();

    $current_key = null;
    $current_crit = null;
    $current_join = null;

    foreach ($data_request as $key => &$value) { // pour chaque critère(et ses données) de la requête
        switch($key) {
            case 'p_arch':
                //echo "case p_arch<br/>";
                // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                $ids_arch = query_1_one_crit_get_id_arch_from_id_prop_arch_and_comp($connexion, $data_request['p_arch']['p_arch'], $data_request[$key]['crit_p_arch']);
                //var_dump($ids_arch);
                if($ids_arch['result'] == 1) {
                    //echo "id recup<br/>";
                    $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']); // on push tous les id dans le tableau d'id_arch_full car aucun critère n'est évalué avant p_arch
                    //var_dump($ids_arch_full);
                    // on met à jour les données de jointure
                    $current_key = "p_arch";
                    $current_crit = "crit_".$current_key;
                    $current_join = "join_".$current_key;
                }
                else {
                    $error = true;
                    $data['message'] = "le nom du proprietaire d'archive, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'p_edi':
                //echo "case p_edi<br/>";
                if($error == false) {
                    $id_prop_edi = get_id_prop_edi_from_nom_prop_edi($connexion, $data_request['p_edi']['p_edi']); // on récupère l'id_prop_edi correspondant au nom edi
                    //var_dump($id_prop_edi);
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_id_prop_edi_and_comp($connexion, $id_prop_edi['id'], $data_request[$key]['crit_p_edi']);
                    //var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {

                        // on détermine s'il y a jointure
                        if($current_key == null) { // soit c'est le premier critère à être évalué
                            //echo "first id recup<br/>";
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']); // on push ces id_arch dans le tableau d'id_arch_full
                            $current_key = "p_edi";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else { // soit un critère a été évalué avant et on applique la jointure
                            //echo "jointure";
                            // on vérifie que le critère de jointure est défini
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_p_arch est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {           // on conserve les id_arch de p_arch et de p_edi (pas de double)
                                    foreach ($ids_arch_full as $id) {                               // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {       // on regarde si la valeur n'est pas dans le tableau $ids_arch__p_edi
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {    // on conserve les id_arch qui sont dans $ids_arch__p_arch ou dans $ids_arch__p_edi (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                             // pour chaque id du tableau d'id de p_edi
                                        if (in_array($id, $ids_arch_full, true) == false) {         // on regarde si la valeur est dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);        // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure ".$current_key." non reconnu";
                                }
                            }
                            $current_key = "p_edi";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le nom du proprietaire d'édifice, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le proprietaire d'edifice, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'dep':
                //echo "case p_dep<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_dep_edi_and_comp($connexion, $data_request['dep']['dep'], $data_request[$key]['crit_dep']);
                    //var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        // on détermine s'il y a jointure
                        if($current_key == null) {
                            echo "first id recup";
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);

                            $current_key = "dep";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            //echo "jointure";
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_p_edi est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {           // on conserve les id_arch de p_edi et de dep (pas de double)
                                    foreach ($ids_arch_full as $id) {                               // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {       // on regarde si la valeur n'est pas dans le tableau $ids_arch__dep
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {    // on conserve les id_arch qui sont dans p_edi ou dans p_dep (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                             // pour chaque id du tableau d'id de p_edi
                                        if (in_array($id, $ids_arch_full, true) == false) {         // on regarde si la valeur n'est pas dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);        // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                }
                                else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure ".$current_key." non reconnu";
                                }
                            }
                            $current_key = "dep";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le departement, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le departement, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'commu':
                //echo "case commu<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    //echo 'nom de commune= '.$data_request['commu']['commu'].'<br/>';
                    //echo 'crit commune= '.$data_request[$key]['crit_commu'].'<br/>';
                    $ids_arch = query_1_one_crit_get_id_arch_from_commu_edi_and_comp($connexion, $data_request['commu']['commu'], $data_request[$key]['crit_commu']);
                    //var_dump($ids_arch_full);
                    //var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        //echo "id recup<br/>";
                        if($current_key == null) {
                            //echo "first value ";
                            //echo 'commu current key null';
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "commu";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            //echo "jointure<br/>";
                            //echo 'commu current key not null donc jointure';
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_dep est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {           // on conserve les id_arch de dep et de commu (pas de double)
                                    foreach ($ids_arch_full as $id) {                               // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {       // on regarde si la valeur n'est pas dans le tableau $ids_arch__commu
                                            //echo '<br/>suppression de l\'id_arch'.$id.'<br/>';
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                }
                                else if ($data_request[$current_key][$current_join] == "ou") {            // on conserve les id_arch qui sont dans dep ou dans commu (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                                 // pour chaque id du tableau d'id de p_commu
                                        if (in_array($id, $ids_arch_full, true)) {               // on regarde si la valeur est dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);    // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                }
                                else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure dep non reconnu";
                                }
                            }
                            //var_dump($ids_arch_full);
                            $current_key = "commu";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "la commune, son critere de comparaison et son operateur de jointure ne matchent aucune donnees(ids_arch null)";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "la commune, son critere de comparaison et son operateur de jointure ne matchent aucune donnees(error prev)";
                }
                break;
            case 'com_phys':
                //echo "case com_phys<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_com_phys_and_comp($connexion, $data_request['com_phys']['com_phys'], $data_request[$key]['crit_com_phys']);
                    if($ids_arch['result'] == 1) {
                        if($current_key == null) {
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "com_phys";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_commu est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {           // on conserve les id_arch de com_phys et de commu (pas de double)
                                    foreach ($ids_arch_full as $id) {                               // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {       // on regarde si la valeur n'est pas dans le tableau $ids_arch__com_phys
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {    // on conserve les id_arch qui sont dans com_phys ou dans commu (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                             // pour chaque id du tableau d'id de com_phys
                                        if (in_array($id, $ids_arch_full, true) == false) {         // on regarde si la valeur est dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);        // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                }
                                else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure commu non reconnu";
                                }
                            }
                            $current_key = "com_phys";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le commentaire physique, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le commentaire physique, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'com_virt':
                //echo "case com_virt<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_com_virt_and_comp($connexion, $data_request['com_virt']['com_virt'], $data_request[$key]['crit_com_virt']);
                    if($ids_arch['result'] == 1) {
                        if($current_key == null) {
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "com_virt";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_com_phys est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {            // on conserve les id_arch de com_virt et de com_phys (pas de double)
                                    foreach ($ids_arch_full as $id) {                                // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {                       // on regarde si la valeur n'est pas dans le tableau com_virt
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {     // on conserve les id_arch qui sont dans com_virt ou dans com_phys (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                              // pour chaque id du tableau d'id de com_phys
                                        if (in_array($id, $ids_arch_full, true) == false) {                   // on regarde si la valeur n'est pas dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);         // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure com_phys non reconnu";
                                }
                            }
                            $current_key = "com_virt";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le commentaire virtuel, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le commentaire virtuel, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 't_edi':
                //echo "case t_edi<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_id_type_edi_and_comp($connexion, $data_request['t_edi']['t_edi'], $data_request[$key]['crit_t_edi']);
                    //var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        if($current_key == null) {
                            //echo "first id recup<br/>";
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "t_edi";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            //echo "jointure<br/>";
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_com_vrit est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {            // on conserve les id_arch de t_edi et de com_virt (pas de double)
                                    foreach ($ids_arch_full as $id) {                                // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {                       // on regarde si la valeur n'est pas dans le tableau t_edi
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {    // on conserve les id_arch qui sont dans t_edi ou dans com_virt (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                             // pour chaque id du tableau d'id de t_edi
                                        if (in_array($id, $ids_arch_full, true) == false) {         // on regarde si la valeur n'est pas dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);        // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure com_phys non reconnu";
                                }
                            }

                            $current_key = "t_edi";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le type d'edifice, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le type d'edifice, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'mm':
                // echo "case mm<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    $ids_arch = query_1_one_crit_get_id_arch_from_mm_and_comp($connexion, $data_request['mm']['mm'], $data_request[$key]['crit_mm']);
                    // var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        if($current_key == null) {
                            // echo "first id recup<br/>";
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "mm";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                        else {
                            // echo "jointure<br/>";
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_t_edi est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {                  // on conserve les id_arch de mm et de t_edi (pas de double)
                                    //echo "jointure ET<br/>";
                                    // var_dump($ids_arch_full);
                                    foreach ($ids_arch_full as $id) {                                // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {        // on regarde si la valeur n'est pas dans le tableau mm
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {            // on conserve les id_arch qui sont dans mm ou dans t_edi (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                                     // pour chaque id du tableau d'id de mm
                                        if (in_array($id, $ids_arch_full, true) == false) {                 // on regarde si la valeur n'est pas dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);                // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure t_edi non reconnu";
                                }
                            }
                            else {
                                echo "curent crit non défini<br/>";
                            }
                            $current_key = "mm";
                            $current_crit = "crit_".$current_key;
                            $current_join = "join_".$current_key;
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le montant total des montants de marche, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le montant total des montants de marche, son critère de comparaison et son opérateur de jointure ne matchent aucune donnees";
                }
                break;
            case 'hono':
                // echo "case hono<br/>";
                if($error == false) {
                    // on appelle la fonction pour récupérer les id_arch correspondant à ce critère
                    // var_dump($ids_arch_full);
                    $ids_arch = query_1_one_crit_get_id_arch_from_hono_and_comp($connexion, $data_request['hono']['hono'], $data_request[$key]['crit_hono']);
                    // var_dump($ids_arch);
                    if($ids_arch['result'] == 1) {
                        // echo "first id recup<br/>";
                        if($current_key == null) {
                            $ids_arch_full = array_merge($ids_arch_full, $ids_arch['ids']);
                            $current_key = "hono";
                            $current_crit = "crit_".$current_key;
                            $current_join = "end";
                        }
                        else {
                            // echo "jointure<br/>";
                            // on applique la jointure
                            if (isset($data_request[$current_key][$current_crit]) && !empty($data_request[$current_key][$current_crit])) { // si le crit_mm est dans la requête
                                if ($data_request[$current_key][$current_join] == "et") {                  // on conserve les id_arch de hono et de mm (pas de double)
                                    foreach ($ids_arch_full as $id) {                                // pour chaque id du tableau d'id_arch final
                                        if (in_array($id, $ids_arch['ids'], true) == false) {                       // on regarde si la valeur n'est pas dans le tableau hono
                                            $ids_arch_full = del_array($ids_arch_full, $id);        // si elle ne l'ai pas, on supprime cette valeur du tableau d'id_arch final
                                        }
                                        // sinon on ne fait rien
                                    }
                                } else if ($data_request[$current_key][$current_join] == "ou") {            // on conserve les id_arch qui sont dans hono ou dans mm (pas de double)
                                    foreach ($ids_arch['ids'] as $id) {                                     // pour chaque id du tableau d'id de hono
                                        if (in_array($id, $ids_arch_full, true) == false) {                 // on regarde si la valeur $id n'est pas dans le tableau d'id_arch final
                                            $ids_arch_full = add_array($ids_arch_full, $id);                // si elle ne l'ai pas, on ajoute cette valeur dans le tableau d'id_arch final
                                        }
                                        // sinon si la valeur y est déjà on ne fait rien
                                    }
                                } else {
                                    $error = true;
                                    $data['message'] = "critere de jointure ".$current_key." non reconnu";
                                    echo "critere de jointure hono non reconnu";
                                }
                            }
                            $current_key = "hono";
                            $current_crit = "crit_".$current_key;
                            $current_join = "end";
                        }
                    }
                    else {
                        $error = true;
                        $data['message'] = "le montant total des honoraires, son critère de comparaison ne matchent aucune donnees";
                    }
                }
                else {
                    $error = true;
                    $data['message'] = "le montant total des honoraires, son critère de comparaison ne matchent aucune donnees";
                }
                break;
        }
    }

    /* WHYYYYYYYYYYYYYYYYYYYYYYYYYYYYY OOOOOOOOOOOMMMMMMMMMMMMMMMMMMMGGGGGGGGGGGGGGGGGGG
    if($error = false) {
        $data['result'] = 1;
        $data['ids_arch_full'] = $ids_arch_full;
    }
    else {
        echo "erreur dans multi crit";
    }*/

    if($error == false) {
        if(sizeof($ids_arch_full)> 0) {
            $data['result'] = 1;
            $data['ids_arch_full'] = $ids_arch_full;
        }
        else {
            $data['result'] = 0;
            $data['message'] = "aucune donnée ne matche les critères";
        }
    }

    //var_dump($data);
    return $data;
}

/* Fonction de support pour opérations sur tableaux cf omfg */
function display_array($ar) {
    echo "<br/><br/>array_content=<br/>";
    for($i=0;$i<sizeof($ar);$i++) {
        echo "index".$i." val= ".$ar[$i]."<br/>";
    }
    echo "<br/>";
}
function del_array($ar, $val) {
    for($i=0;$i<sizeof($ar);$i++) {
        if($ar[$i] == $val) {
            unset($ar[$i]);
        }
    }
    $newar = array_values($ar); // réindexe l'array
    return $newar;
}
function add_array($ar, $val) {
    array_push($ar, $val);
    return $ar;
}
function check_array($ar, $val) {
    $trouve = 0;
    for($i=0;$i<sizeof($ar);$i++) {
        if($ar[$i] == $val) {
            $trouve = 1;
            break;
        }
    }
    return $trouve;
}


