init_form();

const url_base = "http://localhost/arch/scripts/php/operations/";
const url_base_insertions = url_base+"insertions/";
const url_base_recuperations = url_base+"recuperations/";
const url_base_suppressions = url_base+"suppressions/";

// requêtes d'ajout/suppression
const requeteHTTP_add_prop = new XMLHttpRequest();
requeteHTTP_add_prop.onloadend = handler_add_prop;
const requeteHTTP_del_prop = new XMLHttpRequest();
requeteHTTP_del_prop.onloadend = handler_del_prop;
const requeteHTTP_add_prim = new XMLHttpRequest();
requeteHTTP_add_prim.onloadend = handler_add_prim;
const requeteHTTP_del_prim = new XMLHttpRequest();
requeteHTTP_del_prim.onloadend = handler_del_prim;
const requeteHTTP_add_sec = new XMLHttpRequest();
requeteHTTP_add_sec.onloadend = handler_add_sec;
const requeteHTTP_del_sec = new XMLHttpRequest();
requeteHTTP_del_sec.onloadend = handler_del_sec;
const requeteHTTP_add_ter = new XMLHttpRequest();
requeteHTTP_add_ter.onloadend = handler_add_ter;
const requeteHTTP_del_ter = new XMLHttpRequest();
requeteHTTP_del_ter.onloadend = handler_del_ter;
const requeteHTTP_add_edi = new XMLHttpRequest();
requeteHTTP_add_edi.onloadend = handler_add_edi;
const requeteHTTP_del_edi = new XMLHttpRequest();
requeteHTTP_del_edi.onloadend = handler_del_edi;
const requeteHTTP_add_ce = new XMLHttpRequest();
requeteHTTP_add_ce.onloadend = handler_add_ce;
const requeteHTTP_del_ce = new XMLHttpRequest();
requeteHTTP_del_ce.onloadend = handler_del_ce;
const requeteHTTP_add_e = new XMLHttpRequest();
requeteHTTP_add_e.onloadend = handler_add_e;
const requeteHTTP_del_e = new XMLHttpRequest();
requeteHTTP_del_e.onloadend = handler_del_e;

// requêtes des listes
const requeteHTTP_get_prop = new XMLHttpRequest();
requeteHTTP_get_prop.onloadend = handler_get_prop;
const requeteHTTP_get_prim = new XMLHttpRequest();
requeteHTTP_get_prim.onloadend = handler_get_prim;
const requeteHTTP_get_sec = new XMLHttpRequest();
requeteHTTP_get_sec.onloadend = handler_get_sec;
const requeteHTTP_get_ter = new XMLHttpRequest();
requeteHTTP_get_ter.onloadend = handler_get_ter;
const requeteHTTP_get_edi = new XMLHttpRequest();
requeteHTTP_get_edi.onloadend = handler_get_edi;
const requeteHTTP_get_ce = new XMLHttpRequest();
requeteHTTP_get_ce.onloadend = handler_get_ce;
const requeteHTTP_get_e = new XMLHttpRequest();
requeteHTTP_get_e.onloadend = handler_get_e;

/* OnClick functions */
function add_prop() {
    let data_prop = document.getElementById('input_prop').value;
    console.log("dataprop_add= "+data_prop);
    requeteHTTP_add_prop.open("POST", url_base_insertions+"add_prop.php", true);
    requeteHTTP_add_prop.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_prop.send("prop="+data_prop);
}
function del_prop() {
    let data_prop = document.getElementById('input_prop').value;
    console.log("dataprop_del= "+data_prop);
    requeteHTTP_del_prop.open("POST", url_base_suppressions+"del_prop.php", true);
    requeteHTTP_del_prop.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_prop.send("prop=" + data_prop);
}
function add_prim() {
    let data_prim = document.getElementById('input_prim').value;
    console.log("dataprim_add= "+data_prim);
    requeteHTTP_add_prim.open("POST", url_base_insertions+"add_prim.php", true);
    requeteHTTP_add_prim.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_prim.send("prim="+data_prim);
}
function del_prim() {
    let data_prim = document.getElementById('input_prim').value;
    console.log("dataprim_del= "+data_prim);
    requeteHTTP_del_prim.open("POST", url_base_suppressions+"del_prim.php", true);
    requeteHTTP_del_prim.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_prim.send("prim=" + data_prim);
}
function add_sec() {
    let data_sec = document.getElementById('input_sec').value;
    requeteHTTP_add_sec.open("POST", url_base_insertions+"add_sec.php", true);
    requeteHTTP_add_sec.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_sec.send("sec=" + data_sec);
}
function del_sec() {
    let data_sec = document.getElementById('input_sec').value;
    requeteHTTP_del_sec.open("POST", url_base_suppressions+"del_sec.php", true);
    requeteHTTP_del_sec.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_sec.send("sec=" + data_sec);
}
function add_ter() {
    let data_ter = document.getElementById('input_ter').value;
    requeteHTTP_add_ter.open("POST", url_base_insertions+"add_ter.php", true);
    requeteHTTP_add_ter.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_ter.send("ter=" + data_ter);
}
function del_ter() {
    let data_ter = document.getElementById('input_ter').value;
    requeteHTTP_del_ter.open("POST", url_base_suppressions+"del_ter.php", true);
    requeteHTTP_del_ter.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_ter.send("ter=" + data_ter);
}
function add_edi() {
    let data_edi = document.getElementById('input_edi').value;
    requeteHTTP_add_edi.open("POST", url_base_insertions+"add_edi.php", true);
    requeteHTTP_add_edi.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_edi.send("edi=" + data_edi);
}
function del_edi() {
    let data_edi = document.getElementById('input_edi').value;
    requeteHTTP_del_edi.open("POST", url_base_suppressions+"del_edi.php", true);
    requeteHTTP_del_edi.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_edi.send("edi=" + data_edi);
}
function add_ce() {
    let data_ce = document.getElementById('input_ce').value;
    requeteHTTP_add_ce.open("POST", url_base_insertions+"add_ce.php", true);
    requeteHTTP_add_ce.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_ce.send("ce=" + data_ce);
}
function del_ce() {
    let nom_ce = document.getElementById('input_ce').value;
    console.log("suppression du coprs état "+nom_ce);
    requeteHTTP_del_ce.open("POST", url_base_suppressions+"del_ce.php", true);
    requeteHTTP_del_ce.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_ce.send("ce="+nom_ce);
}
function add_e() {
    let nom_e = document.getElementById('input_ne').value;
    let ce = document.getElementById('input_ece').value;
    requeteHTTP_add_e.open("POST", url_base_insertions+"add_e.php", true);
    requeteHTTP_add_e.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_add_e.send("nom="+nom_e+"&corps="+ce);
}
function del_e() {
    let nom_e = document.getElementById('input_ne').value;
    requeteHTTP_del_e.open("POST", url_base_suppressions+"del_e.php", true);
    requeteHTTP_del_e.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_del_e.send("nom=" + nom_e);
}
function get_prop() {
    requeteHTTP_get_prop.open("POST", url_base_recuperations+"get_prop.php", true);
    requeteHTTP_get_prop.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_prop.send();
}
function get_prim() {
    requeteHTTP_get_prim.open("POST", url_base_recuperations+"get_prim.php", true);
    requeteHTTP_get_prim.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_prim.send();
}
function get_sec() {
    requeteHTTP_get_sec.open("POST", url_base_recuperations+"get_sec.php", true);
    requeteHTTP_get_sec.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_sec.send();
}
function get_ter() {
    requeteHTTP_get_ter.open("POST", url_base_recuperations+"get_ter.php", true);
    requeteHTTP_get_ter.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_ter.send();
}
function get_edi() {
    requeteHTTP_get_edi.open("POST", url_base_recuperations+"get_type_edi.php", true);
    requeteHTTP_get_edi.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_edi.send();
}
function get_ce() {
    requeteHTTP_get_ce.open("POST", url_base_recuperations+"get_ce.php", true);
    requeteHTTP_get_ce.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_ce.send();
}
function get_e() {
    requeteHTTP_get_e.open("POST", url_base_recuperations+"get_e.php", true);
    requeteHTTP_get_e.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_e.send();
}

/* Fonction handler des requêtes HTTP */
function handler_add_prop() {
    if((requeteHTTP_add_prop.readyState === 4) && (requeteHTTP_add_prop.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_prop.responseText+"|");

        try {
            if (requeteHTTP_add_prop.responseText === "true") {
                document.getElementById('r_prop').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_prop').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_prop').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_prop').innerText = 'Problème de requête !';
    }
}
function handler_del_prop() {
    if((requeteHTTP_del_prop.readyState === 4) && (requeteHTTP_del_prop.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_prop.responseText+"|");

        try {
            if (requeteHTTP_del_prop.responseText === "true") {
                document.getElementById('r_prop').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_prop').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_prop').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_prop').innerText = 'Problème de requête !';
    }
}
function handler_add_prim() {
    if((requeteHTTP_add_prim.readyState === 4) && (requeteHTTP_add_prim.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_prim.responseText+"|");

            try {
                if (requeteHTTP_add_prim.responseText === "true") {
                    document.getElementById('r_prim').innerText = 'Enregistrement ajouté en base de données !';
                }
                else {
                    document.getElementById('r_prim').innerText = 'Enregistrement non ajouté en base de données !';
                }
            }
            catch(e) {
                console.log("Problème de résultat de requête "+e);
                document.getElementById('r_prim').innerText = 'Problème de résultat de requête !\n'+e;
            }

    }
    else {
        document.getElementById('r_prim').innerText = 'Problème de requête !';
    }
}
function handler_del_prim() {
    if((requeteHTTP_del_prim.readyState === 4) && (requeteHTTP_del_prim.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_prim.responseText+"|");

        try {
            if (requeteHTTP_del_prim.responseText === "true") {
                document.getElementById('r_prim').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_prim').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_prim').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_prim').innerText = 'Problème de requête !';
    }
}
function handler_add_sec() {
    if((requeteHTTP_add_sec.readyState === 4) && (requeteHTTP_add_sec.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_sec.responseText+"|");

        try {
            if (requeteHTTP_add_sec.responseText === "true") {
                document.getElementById('r_sec').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_sec').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_sec').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_sec').innerText = 'Problème de requête !';
    }
}
function handler_del_sec() {
    if((requeteHTTP_del_sec.readyState === 4) && (requeteHTTP_del_sec.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_sec.responseText+"|");

        try {
            if (requeteHTTP_del_sec.responseText === "true") {
                document.getElementById('r_sec').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_sec').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_sec').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_sec').innerText = 'Problème de requête !';
    }
}
function handler_add_ter() {
    if((requeteHTTP_add_ter.readyState === 4) && (requeteHTTP_add_ter.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_ter.responseText+"|");

        try {
            if (requeteHTTP_add_ter.responseText === "true") {
                document.getElementById('r_ter').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_ter').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_ter').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_ter').innerText = 'Problème de requête !';
    }
}
function handler_del_ter() {
    if((requeteHTTP_del_ter.readyState === 4) && (requeteHTTP_del_ter.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_ter.responseText+"|");

        try {
            if (requeteHTTP_del_ter.responseText === "true") {
                document.getElementById('r_ter').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_ter').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_ter').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_ter').innerText = 'Problème de requête !';
    }
}
function handler_add_edi() {
    if((requeteHTTP_add_edi.readyState === 4) && (requeteHTTP_add_edi.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_edi.responseText+"|");

        try {
            if (requeteHTTP_add_edi.responseText === "true") {
                document.getElementById('r_edi').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_edi').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_edi').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_edi').innerText = 'Problème de requête !';
    }
}
function handler_del_edi() {
    if((requeteHTTP_del_edi.readyState === 4) && (requeteHTTP_del_edi.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_edi.responseText+"|");

        try {
            if (requeteHTTP_del_edi.responseText === "true") {
                document.getElementById('r_edi').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_edi').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_edi').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_edi').innerText = 'Problème de requête !';
    }
}
function handler_add_ce() {
    if((requeteHTTP_add_ce.readyState === 4) && (requeteHTTP_add_ce.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_ce.responseText+"|");

        try {
            if (requeteHTTP_add_ce.responseText === "true") {
                document.getElementById('r_ce').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_ce').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_ce').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_ce').innerText = 'Problème de requête !';
    }
}
function handler_del_ce() {
    if((requeteHTTP_del_ce.readyState === 4) && (requeteHTTP_del_ce.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_ce.responseText+"|");

        try {
            if (requeteHTTP_del_ce.responseText === "true") {
                document.getElementById('r_ce').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_ce').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_ce').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_ce').innerText = 'Problème de requête !';
    }
}
function handler_add_e() {
    console.log("handler_add_e called");
    if((requeteHTTP_add_e.readyState === 4) && (requeteHTTP_add_e.status === 200)) {
        console.log("rtext= |"+requeteHTTP_add_e.responseText+"|");

        try {
            if (requeteHTTP_add_e.responseText === "true") {
                document.getElementById('r_e').innerText = 'Enregistrement ajouté en base de données !';
            }
            else {
                document.getElementById('r_e').innerText = 'Enregistrement non ajouté en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_e').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_e').innerText = 'Problème de requête !';
    }
}
function handler_del_e() {
    if((requeteHTTP_del_e.readyState === 4) && (requeteHTTP_del_e.status === 200)) {
        console.log("rtext= |"+requeteHTTP_del_e.responseText+"|");

        try {
            if (requeteHTTP_del_e.responseText === "true") {
                document.getElementById('r_e').innerText = 'Enregistrement supprimé en base de données !';
            }
            else {
                document.getElementById('r_e').innerText = 'Enregistrement non supprimé en base de données !';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('r_e').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('r_e').innerText = 'Problème de requête !';
    }
}
function handler_get_prop() {
    if((requeteHTTP_get_prop.readyState === 4) && (requeteHTTP_get_prop.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_prop.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_prop.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_prop').innerHTML = list;
            }
            else {
                document.getElementById('result_list_prop').innerText = 'Aucun propriétaire enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_prop').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_prop').innerText = 'Problème de requête !';
    }
}
function handler_get_prim() {
    if((requeteHTTP_get_prim.readyState === 4) && (requeteHTTP_get_prim.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_prim.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_prim.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_prim').innerHTML = list;
            }
            else {
                document.getElementById('result_list_prim').innerText = 'Aucun type primaire enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_prim').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_prim').innerText = 'Problème de requête !';
    }
}
function handler_get_sec() {
    if((requeteHTTP_get_sec.readyState === 4) && (requeteHTTP_get_sec.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_sec.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_sec.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_sec').innerHTML = list;
            }
            else {
                document.getElementById('result_list_sec').innerText = 'Aucun type secondaire enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_sec').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_sec').innerText = 'Problème de requête !';
    }
}
function handler_get_ter() {
    if((requeteHTTP_get_ter.readyState === 4) && (requeteHTTP_get_ter.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_ter.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_ter.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_ter').innerHTML = list;
            }
            else {
                document.getElementById('result_list_ter').innerText = 'Aucun type tertiaire enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_ter').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_ter').innerText = 'Problème de requête !';
    }
}
function handler_get_edi() {
    if((requeteHTTP_get_edi.readyState === 4) && (requeteHTTP_get_edi.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_edi.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_edi.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_edi').innerHTML = list;
            }
            else {
                document.getElementById('result_list_edi').innerText = 'Aucun type primaire enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_edi').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_edi').innerText = 'Problème de requête !';
    }
}
function handler_get_ce() {
    if((requeteHTTP_get_ce.readyState === 4) && (requeteHTTP_get_ce.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_ce.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_ce.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i]+"</li>";
                }
                document.getElementById('result_list_ce').innerHTML = list;
            }
            else {
                document.getElementById('result_list_ce').innerText = 'Aucun corps d\'états enregistré en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_ce').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_ce').innerText = 'Problème de requête !';
    }
}
function handler_get_e() {
    if((requeteHTTP_get_e.readyState === 4) && (requeteHTTP_get_e.status === 200)) {
        console.log("rtext= |"+requeteHTTP_get_e.responseText+"|");
        let docJSON = JSON.parse(requeteHTTP_get_e.responseText);
        console.log("docjson= "+docJSON);
        try {
            console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list="";
                for(var i = 0; i < docJSON['list'].length; i++) {
                    list+="<li>"+docJSON['list'][i].NOM_ENTREPRISE+"</li>";
                }
                document.getElementById('result_list_e').innerHTML = list;
            }
            else {
                document.getElementById('result_list_e').innerText = 'Aucune entreprise enregistrée en base de donnée';
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            document.getElementById('result_list_e').innerText = 'Problème de résultat de requête !\n'+e;
        }

    }
    else {
        document.getElementById('result_list_e').innerText = 'Problème de requête !';
    }
}

/* Initialise l'interface des formulaires */
function init_form() {

    addEvent(
        document.getElementById('add_prop'),
        'click',
        function () {
            add_prop();
        }
    );
    addEvent(
        document.getElementById('del_prop'),
        'click',
        function () {
            del_prop();
        }
    );
    addEvent(
        document.getElementById('help_prop'),
        'click',
        function () {
            alert('Les propriétaires d\'archives sont par exemple vous ou une autre personne ...');
        }
    );
    addEvent(
        document.getElementById('add_prim'),
        'click',
        function () {
            add_prim();
        }
    );
    addEvent(
        document.getElementById('del_prim'),
        'click',
        function () {
            del_prim();
         }
    );
    addEvent(
        document.getElementById('help_prim'),
        'click',
        function () {
            alert('Les types primaires sont par exemple "professionelle", "familiale" ...');
        }
    );

    addEvent(
        document.getElementById('add_sec'),
        'click',
        function () {
            add_sec();
          }
    );
    addEvent(
        document.getElementById('del_sec'),
        'click',
        function () {
            del_sec();
         }
    );
    addEvent(
        document.getElementById('help_sec'),
        'click',
        function () {
            alert('Les types secondaires sont par exemple "civiles", "religieuses" ...');
        }
    );

    addEvent(
        document.getElementById('add_ter'),
        'click',
        function () {
            add_ter();
         }
    );
    addEvent(
        document.getElementById('del_ter'),
        'click',
        function () {
            del_ter();
        }
    );
    addEvent(
        document.getElementById('help_ter'),
        'click',
        function () {
            alert('Les types secondaires sont par exemple "conferences", "plan" ...');
        }
    );

    addEvent(
        document.getElementById('add_edi'),
        'click',
        function () {
            add_edi();
         }
    );
    addEvent(
        document.getElementById('del_edi'),
        'click',
        function () {
            del_edi();
        }
    );
    addEvent(
        document.getElementById('help_edi'),
        'click',
        function () {
            alert('Les types secondaires sont par exemple "chateau", "eglise" ...');
        }
    );

    addEvent(
        document.getElementById('add_ce'),
        'click',
        function () {
            add_ce();
        }
    );
    addEvent(
        document.getElementById('del_ce'),
        'click',
        function () {
            del_ce();
        }
    );
    addEvent(
        document.getElementById('help_ce'),
        'click',
        function () {
            alert('Les corps d\'état sont par exemple "maçonnerie", "charpenterie" ...');
        }
    );

    addEvent(
        document.getElementById('add_e'),
        'click',
        function () {
            add_e();
        }
    );
    addEvent(
        document.getElementById('del_e'),
        'click',
        function () {
            del_e();
        }
    );
    addEvent(
        document.getElementById('help_e'),
        'click',
        function () {
            alert('Les entreprises sont par exemple "charpentierPM", "carrefour" ...');
        }
    );

    addEvent(
        document.getElementById('get_prop'),
        'click',
        function () {
            get_prop();
        }
    );
    addEvent(
        document.getElementById('get_prim'),
        'click',
        function () {
            get_prim();
        }
    );
    addEvent(
        document.getElementById('get_sec'),
        'click',
        function () {
            get_sec();
        }
    );
    addEvent(
        document.getElementById('get_ter'),
        'click',
        function () {
            get_ter();
        }
    );
    addEvent(
        document.getElementById('get_edi'),
        'click',
        function () {
            get_edi();
        }
    );
    addEvent(
        document.getElementById('get_ce'),
        'click',
        function () {
            get_ce();
        }
    );
    addEvent(
        document.getElementById('get_e'),
        'click',
        function () {
            get_e();
        }
    );
}

/** Support fonctions **/
function addEvent(element, evnt, funct){
    if (element.attachEvent)
        return element.attachEvent('on'+evnt, funct);
    else
        return element.addEventListener(evnt, funct, false);
}