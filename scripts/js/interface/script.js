/** variables globales */
var visibility_clear = true;
/** Variables d'attente **/
var code_html = document.getElementById('html');
var content_f1_operation = 1;
var operation = 1; // 1=insertion, 2=suppression, 3=modification
var count = -1;
var current_id = -1;
var current_form_id;
var tab_travaux = [];
var expanded = false;
var step1_v = true;
var step2_v = false;
var step3_v = false;
var presentation = 0;
var have_swicthed = false;
var crit_search = "p_arch";
var crit_del = "p_arch";
var request1_open = false;
var win_op_is_visible = true;
var win_rarch_win_is_visible = false;
var win_rinfo_win_is_visible = false;
const url_base = "http://localhost/arch/scripts/php/operations/";
const url_base_insertions = url_base+"insertions/";
const url_base_recuperations = url_base+"recuperations/";
const url_base_suppressions = url_base+"suppressions/";
const requeteHTTP_insert_form = new XMLHttpRequest();
const requeteHTTP_get_prop_form = new XMLHttpRequest();
const requeteHTTP_get_type_prim_form = new XMLHttpRequest();
const requeteHTTP_get_type_sec_form = new XMLHttpRequest();
const requeteHTTP_get_type_ter_form = new XMLHttpRequest();
const requeteHTTP_get_type_edi_form = new XMLHttpRequest();
const requeteHTTP_get_list_ent_form = new XMLHttpRequest();
const requeteHTTP_get_archive_searched = new XMLHttpRequest();
const requeteHTTP_del_archive = new XMLHttpRequest();
const requeteHTTP_query_1 = new XMLHttpRequest()

requeteHTTP_insert_form.onloadend = handler_insert_form;
requeteHTTP_get_prop_form.onloadend = handler_get_prop_form;
requeteHTTP_get_type_prim_form.onloadend = handler_get_type_prim_form;
requeteHTTP_get_type_sec_form.onloadend = handler_get_type_sec_form;
requeteHTTP_get_type_ter_form.onloadend = handler_get_type_ter_form;
requeteHTTP_get_type_edi_form.onloadend = handler_get_type_edi_form;
requeteHTTP_get_list_ent_form.onloadend = handler_get_list_ent_form;
requeteHTTP_get_archive_searched.onloadend = handler_get_archive_searched;
requeteHTTP_del_archive.onloadend = handler_del_archive;
requeteHTTP_query_1.onloadend = handler_query_1;

$(document).ready(function() {

    init_interface();
    init_form_event_insert();
    $( "select.select_op" ).change(function() {
        // on récupère la valeur du select
        var selected_mode = $(this).children("option:selected").val();
        console.log("calling init_interface_imf with selected_mode= "+selected_mode);
        have_swicthed = true;
        init_interface_imf(selected_mode);
    });
});

/** Initialisation de l'interface **/
function init_interface() {

    init_interface_windows();
    init_interface_imf('1');
    init_interface_recherche();
    init_interface_infos();
    init_interface_console();

    /** actions des boutons onclick **/
    // onclick visibility opérations
    addEvent(
        document.getElementById('button_ope_vis'),
        'click',
        function () {
            // on récupère le content_f1
            let div = document.getElementById('div_ope_vis');
            let svg = document.getElementById('svg_ope_vis');
            let content_f1 = document.getElementById('content_f1');
            let content_f1_jq = $("#content_f1");

            // si la csss visibility est à hidden
                if(content_f1.style.visibility === "hidden") {
                    // modifier l'icone
                    div.classList.remove("fas", "fa-eye-slash");
                    svg.classList.remove("fas", "fa-eye-slash");
                    div.classList.add("fas", "fa-eye");
                    svg.classList.add("fas", "fa-eye");
                    content_f1.style.visibility = "visible";// on affiche le contenu de content_f1
                    content_f1_jq.children().show();

                }
                else {
                    // modifier l'icone
                    div.classList.remove("fas", "fa-eye");
                    svg.classList.remove("fas", "fa-eye");
                    div.classList.add("fas", "fa-eye-slash");
                    svg.classList.add("fas", "fa-eye-slash");
                    content_f1.style.visibility = "hidden"; // sinon on cache le contenu de F1
                    content_f1_jq.children().hide();
                }

        }
    );
    /* onclick switch mode view opération insert
    addEvent(
        document.getElementById('button_ope_switch'),
        'click',
        function () {
            // on récupère le content_f1
            var div = document.getElementById('div_ope_switch');
            var svg = document.getElementById('svg_ope_switch');

            // si c'est le mode compact
            if(presentation === 0) {
                // modifier l'icone
                div.classList.remove("fas", "fa-dice-one");
                svg.classList.remove("fas", "fa-dice-one");
                div.classList.add("fas", "fa-clone");
                svg.classList.add("fas", "fa-clone");
                console.log("prez "+presentation+"=== 0 --> icone changed ==> prez = "+presentation);
             }
            else { // mode non compacte 1
                // modifier l'icone
                div.classList.remove("fas", "fa-clone");
                svg.classList.remove("fas", "fa-clone");
                div.classList.add("fas", "fa-dice-one");
                svg.classList.add("fas", "fa-dice-one");
                console.log("prez "+presentation+"=== 1 --> icone changed ==> prez = "+presentation);
             }
        }
    );*/
    // onclick validate & send to bdd insert
    addEvent(
        document.getElementById('button_ope_send'),
        'click',
        function () {
            // on récupère les éléments contenant des values
            let link_edi = $('#link_edi').prop("checked");
            console.log("taille tab travaux btn send "+tab_travaux.length);
            console.log("data tab = "+JSON.stringify(tab_travaux));
            let checkbox_trav = $('#link_trav');
            if (count >= 0 && checkbox_trav.prop("checked") === true) {
                store_actual_travaux_form();
            }
            let link_trav = (checkbox_trav.prop("checked") === true && tab_travaux.length >= 0 && JSON.stringify(tab_travaux) !== "[]");
            console.log("link trav= "+link_trav);
            if(valid_data_form(link_edi, link_trav)) { // le formulaire ne comporte pas d'erreur
                insert_form(link_edi, link_trav);
                reset_insert_form(link_edi, link_trav);
            }
        }
    );
    // onclick bouton maximize F1
    addEvent(
        document.getElementById('max_F1'),
        'click',
        function () {
            let content_F1 = $('#content_f1');
            content_F1.children().show();
        }
    );
    // onclick bouton minimize F1
    addEvent(
        document.getElementById('min_F1'),
        'click',
        function () {
            let content_F1 = $('#content_f1');
            content_F1.children().hide();

            let content_F1_jq = $('#box-html');

            // on met F1 à width 0%
            content_F1_jq.css({'width': '0%'});
        }
    );
    // onclick visibility archives
    addEvent(
        document.getElementById('button_arch_vis'),
        'click',
        function () {
            // on récupère le content_f2
            let div = document.getElementById('div_arch_vis');
            let svg = document.getElementById('svg_arch_vis');
            let content_f2 = document.getElementById('content_f2');
            let content_f2_jq = $("#content_f2");

            // si la csss visibility est à hidden
            if(content_f2.style.visibility === "hidden") {
                // modifier l'icone
                div.classList.remove("fas", "fa-eye-slash");
                svg.classList.remove("fas", "fa-eye-slash");
                div.classList.add("fas", "fa-eye");
                svg.classList.add("fas", "fa-eye");
                content_f2.style.visibility = "visible";// on affiche le contenu de content_f1
                content_f2_jq.children().show();
            }
            else {
                // modifier l'icone
                div.classList.remove("fas", "fa-eye");
                svg.classList.remove("fas", "fa-eye");
                div.classList.add("fas", "fa-eye-slash");
                svg.classList.add("fas", "fa-eye-slash");
                content_f2.style.visibility = "hidden"; // sinon on cache le contenu de F1
                content_f2_jq.children().hide();
            }
        }
    );
    // onclick visibility recherches
    addEvent(
        document.getElementById('button_info_vis'),
        'click',
        function () {
            // on récupère les contents
            let div = document.getElementById('div_info_vis');
            let svg = document.getElementById('svg_info_vis');
            let content_f3 = document.getElementById('content_f3');
            let content_f3_jq = $("#content_f3");

            // si la csss visibility est à hidden
            if(content_f3.style.visibility === "hidden") {
                // modifier l'icone
                div.classList.remove("fas", "fa-eye-slash");
                svg.classList.remove("fas", "fa-eye-slash");
                div.classList.add("fas", "fa-eye");
                svg.classList.add("fas", "fa-eye");
                content_f3.style.visibility = "visible";// on affiche le contenu de content_f1
                content_f3_jq.children().show();
            }
            else {
                // modifier l'icone
                div.classList.remove("fas", "fa-eye");
                svg.classList.remove("fas", "fa-eye");
                div.classList.add("fas", "fa-eye-slash");
                svg.classList.add("fas", "fa-eye-slash");
                content_f3.style.visibility = "hidden"; // sinon on cache le contenu de F1
                content_f3_jq.children().hide();
            }
        }
    );
    // onclick print pdf
    addEvent(
        document.getElementById('button_pdf'),
        'click',
        function () {

            print(1);

        }
    );
    // onclick bouton maximize F2
    addEvent(
        document.getElementById('max_F2'),
        'click',
        function () {
            // on hide la form F1
            let content_F1 = $('#content_f1');
            content_F1.children().hide();
            // on affiche la form F2
            let content_F2 = document.getElementById('content_f2');
            let content_F2_jq = $('#content_f2');
            content_F2_jq.children().show();
            content_F2.style.visibility = "visible";// on affiche le contenu de content_f1
        }
    );
    // onclick bouton minimize F2
    addEvent(
        document.getElementById('min_F2'),
        'click',
        function () {
            // on hide la form F1
            let content_F1 = $('#content_f1');
            content_F1.children().hide();
            // on hide la form F2
            let content_F2 = document.getElementById('content_f2');
            let content_F2_jq = $('#content_f2');
            content_F2_jq.children().hide();
            content_F2.style.visibility = "hidden";// on affiche le contenu de content_f1

            let content_F2_jq_2 = $('#box-css');
            // on met F1 à width 0%
            content_F2_jq_2.css({'width': '0%'});
        }
    );
    // onclick visibility reset
    addEvent(
        document.getElementById('button_reset_vis'),
        'click',
        function () {
            // on récupère les content
            let reset_li = document.getElementById('reset_vis');
            let content_f1 = document.getElementById('content_f1');
            let content_f2 = document.getElementById('content_f2');
            let content_f3 = document.getElementById('content_f3');

            /**/let content_f1_jq = $("#content_f1");
            let content_f2_jq = $("#content_f2");
            let content_f3_jq = $("#content_f3");

            // si la visibility est à hidden
            if(!visibility_clear) {
                // on met l'icone oeil visible
                reset_li.classList.remove("fas", "fa-eye-slash");
                reset_li.classList.add("fas", "fa-eye");
                // on les met tous à visible
                content_f1.style.visibility = "visible";
                content_f2.style.visibility = "visible";
                content_f3.style.visibility = "visible";
                /* on affiche tous les enfants*/
                content_f1_jq.children().show();
                content_f2_jq.children().show();
                content_f3_jq.children().show();
                visibility_clear = true;
            }
            else { // sinon
                // on met l'icone oeil hidden
                reset_li.classList.remove("fas", "fa-eye");
                reset_li.classList.add("fas", "fa-eye-slash");
                // on les met à hidden
                content_f1.style.visibility = "hidden";
                content_f2.style.visibility = "hidden";
                content_f3.style.visibility = "hidden";
                /* on affiche tous les enfants*/
                content_f1_jq.children().hide();
                content_f2_jq.children().hide();
                content_f3_jq.children().hide();
                visibility_clear = false;
            }
        }
    );
    // onclick bouton maximize F3
    addEvent(
        document.getElementById('max_F3'),
        'click',
        function () {
            // on hide la form F1
            let content_F1 = $('#content_f1');
            content_F1.children().hide();
            // on hide la form F2
            let content_F2 = document.getElementById('content_f2');
            let content_F2_jq = $('#content_f2');
            content_F2_jq.children().hide();
            content_F2.style.visibility = "hidden";// on affiche le contenu de content_f1
        }
    );
    // onclick bouton min F3
    addEvent(
        document.getElementById('min_F3'),
        'click',
        function () {
            // on hide la form F1
            let content_F1 = $('#content_f1');
            content_F1.children().hide();
            // on hide la form F2
            let content_F2 = document.getElementById('content_f2');
            let content_F2_jq = $('#content_f2');
            content_F2_jq.children().hide();
            content_F2.style.visibility = "hidden";// on affiche le contenu de content_f1

            let content_F3_jq_2 = $('#box-js');
            // on met F1 à width 0%
            content_F3_jq_2.css({'width': '0%'});
        }
    );
    // on click clear
    addEvent(
        document.getElementById('button_clear_result'),
        'click',
        function () {
            // on récupère les content
            var clear_result_li = document.getElementById('clear_result');
            var content_console = document.getElementById('content_console');

            // on supprime le contenu des résultats
            content_console.innerText = "";
        }
    );
    // on click db_edit
    addEvent(
        document.getElementById('button_edit_db'),
        'click',
        function () {
            window.open("http://localhost/arch/pages/add_spec.html");
        }
    );
    // onclick switch opération btn
    addEvent(
        document.getElementById('btn_fen_op'),
        'click',
        function () {
            $('#max_F1').click();

            // on affiche F1
            let content_F1_jq = $('#content_f1');
            let content_F1 = document.getElementById('content_f1');
            content_F1_jq.children().show();
            content_F1_jq.show();
            content_F1.style.visibility = "visible";
            /*var t=e.data("dropdown-type");
            $(".maximize",e).click(function(){
                Hub.pub("editor-expand",t),Hub.pub("popup-close")
            });
            Hub.pub("editor-expand", t); /!*Hub.pub("popup-close");*!/

            /!*top-boxes editor-parent*!/*/

        }
    );
    // onclick switch fenêtre recherche archive
    addEvent(
        document.getElementById('btn_fen_ra'),
        'click',
        function () {
            $('#max_F2').click();
            // on hide la form F1
            let content_F1_jq = $('#content_f1');
            let content_F1 = document.getElementById('content_f1');
            content_F1_jq.children().hide();
            content_F1_jq.hide();
            content_F1.style.visibility = "hidden";

            // on affiche F2
            let content_F2_jq = $('#content_f2');
            let content_F2 = document.getElementById('content_f2');
            content_F2_jq.children().show();
            content_F2_jq.show();
            content_F2.style.visibility = "visible";
        }
    );
    // onclick switch fenêtre recherche information
    addEvent(
        document.getElementById('btn_fen_ri'),
        'click',
        function () {
            $('#max_F3').click();
            // on hide la form F1
            let content_F1_jq = $('#content_f1');
            let content_F1 = document.getElementById('content_f1');
            content_F1_jq.hide();
            content_F1_jq.children().hide();
            content_F1.style.visibility = "hidden";
            // on hide la form F2
            let content_F2_jq = $('#content_f2');
            let content_F2 = document.getElementById('content_f2');
            content_F2_jq.hide();
            content_F2_jq.children().hide();
            content_F2.style.visibility = "hidden";

            // on affiche F3
            let content_F3_jq = $('#content_f3');
            let content_F3 = document.getElementById('content_f3');
            content_F3_jq.children().show();
            content_F3_jq.show();
            content_F3.style.visibility = "visible";

            $('#first_h3_q1').click();
        }
    );
}
/* Initialise les fenêtres de l'interface */
function init_interface_windows() {
    $("#box-html .maximize").click(); // on met la fenêtre opération par default
    $(".top-boxes").css({'height': '770px'}); // on met la fenêtre de la console en bas
}
/* Initialise l'interface de Insertion/Modification/Suppression
 *
 * - initialisation du mode insertion par default
 * 1 = insertion
 * 2 = suppression
 * 3 = modification
 * */
function init_interface_imf(num_op) {
    console.log("init_interface_imf() called");
    let selected_op_val = document.getElementById('i_select_op');
    let content_f1_ins = document.getElementById('content_f1_ins');
    let content_f1_supp = document.getElementById('content_f1_supp');
    let content_f1_modif = document.getElementById('content_f1_modif');

    let content_f1_insertion = ''+
        '<div id="i_master_container" class="master_container">'+
        '<div id="i_container_form" class="container_form">'+
        '<div id="i_sub_container_form" class="sub_container_form">'+
        '<div id="step1" class="op-form">'+
        '<form class="op-form-name">'+
        '<div class="form-title_FM1"><u>Caractéristiques</u> &nbsp;d\'une archive<li id="btn_nxt_a" class="fas fa-arrow-right"></li></div>'+
        '<div class="form-body">'+
        '<div class="row">'+
        '<input id="data_nom_arch" minlength="3" maxlength="19" size="19" type="text" placeholder="nom de l\'archive" title="Initial du : type secondaire _ type edifice _ departement _ commune _ nombre ou lettre" >'+
        '<span id="data_nom_arch_error"></span>'+
        '</div>'+
        '<div class="row">'+
        '<label class="label_point" for="prop-select" >Choississez un propriétaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="prop-select">'+
        '<option value="">--votre choix--</option>'+
        '</select>'+
        '</div>'+
        '<div class="row_prim">'+
        '<label class="label_point" for="prim-select">Choississez un type primaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="prim-select">'+
        '<option value="">--votre choix--</option>'+
        '</select>'+
        '</div>'+
        '<div class="row_sec">'+
        '<label class="label_point" for="sec-select">Choississez un type secondaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="sec-select">'+
        '<option value="" >--votre choix--</option>'+
        '</select>'+
        '</div>'+
        '<div class="row_sm" >' +
        '<div class="container_mselect_ter" >' +
        '<label class="label_point_ter">Selectionner les types tertiaires</label>'+
        '<select id="m_select_type_ter" multiple="multiple">' +
        '</select>'+
        '<i id="i_btn_ter_refresh" class="fas fa-sync-alt"></i>'+
        '</div>'+
        '</div>'+
        '<div class="row_dd">'+
        '<div class="data_picker">'+
        '<label for="year_picker" class="label_year">Année: </label>'+
        '<div class="inputs_year">'+
        '<input id="year_picker" type="date" min="1900" max="2050"/>'+
        '<input type="hidden" id="todayDate" name="startdate" />'+
        '<label for="year_inconnu" class="label_year_unknow"> ou inconnue</label>'+
        '<input id="year_inconnu" type="checkbox" onchange="document.getElementById(\'year_picker\').disabled = this.checked;">'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="row">'+
        '<input type="hidden" placeholder="date archivage"> <!-- date d\'enregistrement -->'+
        '</div>'+
        '<div class="rowcheckbox">'+
        '<p class="carac_arch">Cette archive est :</p>'+
        '</div>'+
        '<div class="rowcheckbox">'+
        '<div>'+
        '<input type="checkbox" id="phys" onchange="document.getElementById(\'phys_com\').disabled = !this.checked;">'+
        '<label for="phys">&nbsp physique</label><br/><br/>'+
        '<textarea id="phys_com" cols="50" disabled placeholder="Commentaire sur l\'archive physique"></textarea>'+
        '</div>'+
        '</div>'+
        '<div class="rowcheckbox">'+
        '<div>'+
        '<input type="checkbox" id="virt" onchange="document.getElementById(\'virt_com\').disabled = !this.checked;">'+
        '<label for="virt">&nbsp virtuelle</label><br/><br/>'+
        '<textarea id="virt_com" cols="50" disabled placeholder="Commentaire sur l\'archive virtuelle"></textarea>'+
        '</div>'+
        '</div>'+
        '<div class="rowcheckbox">'+
        '<div class="div_link_edi">'+
        '<input type="checkbox" id="link_edi">'+
        '<label for="link_edi">&nbsp lié à un édifice ?</label><br/><br/>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</form>'+
        '</div>'+
        '<div id="step2" class="op-form" disabled>'+
        '<form class="op-form-name">'+
        '<div class="form-title"><li id="btn_prev_e" class="fas fa-arrow-left"></li><u>Édifice</u> &nbsp;lié à l\'archive<li id="btn_nxt_e" class="fas fa-arrow-right"></div>'+
        '<div class="form-body">'+
        '<div class="row">'+
        '<input id="nom_edi" minlength="3" maxlength="50" size="50" type="text" placeholder="nom de l\'édifice">'+
        '</div>'+
        '<div class="row">'+
        '<label class="label_point" for="tedi-select">Choississez un type d\'édifice:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="tedi-select">'+
        '<option value="">--votre choix--</option>'+
        '</select>'+
        '</div>'+
        '<div class="row">'+
        '<input id="commu_edi" minlength="3" maxlength="50" size="50" type="text" placeholder="commune de l\'édifice">'+
        '</div>'+
        '<div class="row">'+
        '<input id="dep_edi" minlength="3" maxlength="50" size="50" type="text" placeholder="département de l\'édifice">'+
        '</div>'+

        '</div>'+
        '</form>'+
        '<div class="op-sub_form">'+
            '<form class="op-form-name" style="margin-bottom:2.5em;">'+
                '<div class="form-sub_title">Création d\'un propriétaire</div>'+
                    '<div class="form-body">'+
                        '<div class="row">'+
                            '<input id="prop_edi" type="text" placeholder="nom du propriétaire de l\'édifice">'+
                        '</div>'+
                        '<div class="rowc">'+
                            '<div>'+
                                '<input type="checkbox" id="est_part_prop" onchange="document.getElementById(\'est_commu_prop\').disabled = this.checked;">'+
                                '<label for="part">&nbsp est un particulier</label>'+
                            '</div>'+
                        '</div>'+
                    '<div class="rowc">'+
                        '<div>'+
                            '<input type="checkbox" id="est_commu_prop" onchange="document.getElementById(\'est_part_prop\').disabled = this.checked;">'+
                            '<label for="part">&nbsp est une commune</label>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</form>'+
        '</div>'+
        '<div class="rowcheckbox">'+
        '<div class="div_link_trav">'+
        '<input type="checkbox" id="link_trav">'+
        '<label for="link_trav">&nbsp lié à des travaux ?</label><br/><br/>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div id="step3" class="op-form" disabled>'+
        '<div class="form-title-t"><li id="btn_prev_t" class="fas fa-arrow-left"></li><u>Travaux</u> &nbsp;liés à l\'édifice</div>'+
        '<div class="btn-form-t">'+
        '<button id="ajouter">ajouter</button>'+
        '<button id="precedent">precent</button>'+
        '<button id="suivant">suivant</button>'+
        '<button id="reset">reset</button>'+
        '</div>'+
        '<div id="form-placeholder"></div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>';

    let content_f1_suppression =
        '<div id="i_master_container_F2" class="master_container">'+
        '<div id="i_container_form_F2" class="container_form">'+
        '<div id="i_sub_container_form_F2" class="sub_container_form">'+

        '<div id="step1_F1M2" class="op-form">'+
        '<form class="op-form-name" >'+
        '<div class="form-title">Critère de suppression d\'archive' +
        '<div class="crit_box_div">'+
        '<select id="crit-select_s" required="true">'+
        '<option value="">--votre choix--</option>'+
        '<option value="num_arch">par N° d\'archive</option>'+
        '<option value="n_arch">par nom d\'archive</option>'+
        '<option value="n_edi">par nom d\'édifice</option>'+
        '<option value="p_arch" selected>par propriétaire d\'archive</option>'+
        '<option value="t1_arch">par type primaire</option>'+
        '<option value="t2_arch">par type secondaire</option>'+
        '<option value="an">par annee</option>'+
        '</select>'+
        '</div>'+
        '</div>'+
        '<div class="form-body" >'+

        '<div class="row_s">'+
        '<div id="i_crit_box" class="crit_box">'+

        '<div class="crit_box_value">'+

        '<div id="crit_value_num_arch">'+
        '<input id="s_data_num_arch" min="1" type="number" placeholder="N° d\'archive">'+
        '</div>'+

        '<div id="crit_value_nom_arch">'+
        '<input id="s_data_nom_arch" minlength="3" maxlength="19" size="19" type="text" placeholder="nom de l\'archive">'+
        '</div>'+

        '<div id="crit_value_nom_edi">'+
        '<input id="s_data_nom_edi" minlength="3" maxlength="50" size="25" type="text" placeholder="nom de l\'édifice">'+
        '</div>'+

        '<div id="crit_value_prop_select">'+
        '<label class="s_label_point" for="s_prop-select" >Choississez un propriétaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="s_prop-select" required="true">'+
        '<option value="">--votre choix--</option>'+
        '</select>'+
        '</div>'+

        '<div id="crit_value_prim_select">'+
        '<label class="s_label_point" for="s_prim-select">Choississez un type primaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="s_prim-select" required="true">'+
        '<option value="">--votre choix--</option>'+
        '</select>'+
        '</div>'+

        '<div id="crit_value_sec_select">'+
        '<label class="s_label_point" for="s_sec-select">Choississez un type secondaire:</label><!-- id à récupérer pour enregistrement -->'+
        '<select id="s_sec-select" required="true">'+
        '<option value="" >--votre choix--</option>'+
        '</select>'+
        '</div>'+

        '<div id="crit_value_year_picker">'+
        '<input id="s_year_picker" minlength="3" maxlength="50" size="50" type="number" placeholder="annee de l\'archive">'+
        '</div>'+

        '</div>'+

        '<a href="#myModal" role="button" class="btn_del" data-toggle="modal">Supprimer</a>'+

        '<div class="bs-example">'+
            '<div id="myModal" class="modal fade" tabindex="-1" style="background-color: transparent;">'+
                '<div class="modal-dialog">'+
                    '<div class="modal-content">'+
                        '<div class="modal-header">'+
                            '<h5 class="modal-title" style="text-align:center;color:black;">Confirmation de <br/>suppression d\'archive(s)</h5>'+
                            '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
                        '</div>'+
                        '<div class="modal-body">'+
                            '<p style="font-weight: bold;color:red;text-align:center;margin-top:1em;">Voulez vous vraiment supprimer toutes les archives <br/>correspondant au critère spécifié ???<script>crit_del</script></p>'+
                        '</div>'+
                        '<div class="modal-footer" style="text-align:center;">'+
                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">non</button>'+
                            '<button type="button" id="s_btn_del" class="btn btn-primary" data-dismiss="modal">Boquet est ok</button>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+

        '</div>'+
        '</div>'+
        '</div>'+
        '</form>'+
        '</div>'+

        '</div>'+
        '</div>'+
        '</div>';
    let content_f1_modification = "Modification d'une archive existante";

    selected_op_val.value = num_op; // on met la valeur du select change op à la valeur du num_op


    if(num_op === '1') {
        console.log("init_interface_imf with insertion num_op= "+num_op);
        content_f1_modif.style.display = "none";
        content_f1_supp.style.display = "none";
        content_f1_ins.innerHTML = content_f1_insertion;
        content_f1_ins.style.display = "block";
        init_style_op();
        init_travaux_form();
        init_form_event_insert();
    }
    else if(num_op === '2') {
        console.log("init_interface_imf with suppression num_op= "+num_op);
        content_f1_ins.style.display = "none";
        content_f1_modif.style.display = "none";
        content_f1_supp.innerHTML = content_f1_suppression;
        content_f1_supp.style.display = "block";
        init_style_op_suppression();
        init_form_event_suppression();
    }
    else if(num_op === '3') {
        console.log("init_interface_imf with modification num_op= "+num_op);
        content_f1_ins.style.display = "none";
        content_f1_supp.style.display = "none";
        content_f1_modif.innerHTML = content_f1_modification;
        content_f1_modif.style.display = "block";
    }
    else {
        console.log("aucun mode ");
        console.log("aucun mode "+typeof num_op);
    }

}
/* Initialize l'interface de travaux */
function init_travaux_form() {
    // on récupère la zone où les forms seront ajoutées
    var placeholder     = $("#form-placeholder");

    // on récupère les boutons
    var add_button      = $("#ajouter");
    var del_button      = $("#supprimer");
    var prev_button      = $("#precedent");
    var nxt_button      = $("#suivant");
    var rst_button      = $("#reset");
    var btn_switch_form   = $("#btn_switch");

    // onclick bouton ajouter
    $(add_button).click(function(e){
        console.log("\n# ajouter clicked");
        console.log("-- COUNT = "+count);
        console.log("-- CURRENT FORM ID = "+current_form_id);
        console.log("-- CURRENT ID = "+current_id);
        console.log("-- TAB LENGTH = "+tab_travaux.length);

        if(count>=0 && current_id >=0 && have_swicthed === false){
            hide_prev_travaux_form(); // on cache la form d'avant
            store_actual_travaux_form(); // on ajoute la nouvelle form
        }
        else if(count>=0 && current_id >=0 && have_swicthed === true){
            //rst_button.click();
            //eventFire(document.getElementById('reset'), 'click');
            have_swicthed = false;
        }
        else {

        }
        count++;
        let j = count;
        let form_id = "form"+j.toString();
        let montant_marche_id = "montant"+j.toString();
        let honoraire_id = "hono"+j.toString();
        let date_debut_id = "year_picker_deb_travaux"+j.toString();
        let date_fin_id = "year_picker_fin_travaux"+j.toString();
        let duree_id = "duree"+j.toString();
        let mselect_id = "mselect"+j.toString();
        let mselect_class = "c_mselect"+j.toString();
        let btn_refresh_id = "btn_refresh"+j.toString();

        e.preventDefault();

        $(placeholder).append('' +
            '<form id="'+form_id+'" class="op-form-name"> ' +
            '<div class="form-title">Travaux N°'+j+' liés à l\'édifice</div>' +
            '<div class="form-body">' +
            '<div class="rowc"><div><label class="label_point" for="part">montant de marché</label><input type="number" min="1" required id="'+montant_marche_id+'"></div></div> ' +
            '<div class="rowc"><div><label class="label_point" for="part">honoraire</label><input type="number" min="1" required id="'+honoraire_id+'"> </div></div> ' +
            '<div class="rowc"><label class="label_point" for="'+date_debut_id+'">Date de début: </label><input id="'+date_debut_id+'" type="date" min="500" max="2050" required/> ' +
            '<input type="hidden" id="todayDate_deb_travaux_" name="startdate" /></div> ' +
            '<div class="rowc"><label class="label_point" for="'+date_fin_id+'">Date de fin: </label> <input id="'+date_fin_id+'" type="date" min="500" max="2050"/> ' +
            '<input type="hidden" id="todayDate_fin_travaux_" name="startdate" /></div> ' +
            '<div class="rowc"><div><label class="label_point" for="part">durée des travaux(en mois)</label><input type="number" id="'+duree_id+'" type="date" min="0" max="2050" required></div></div> ' +
            '<div class="container_mselect">' +
            '<label class="label_point" style="margin-top:0.5em;" >Selectionner les entreprises liés à ce travaux</label><br/>'+
            '<select id="'+mselect_id+'" class="'+mselect_class+'" multiple="multiple" required>' +
            '</select>'+
            '<i id="'+btn_refresh_id+'" class="fas fa-sync-alt"></i>'+
            '</div></div> </form>');

        /* met tous les checkbox à 0 */
        let jquery_id_mselect = $("#"+mselect_id);
        jquery_id_mselect.multiselect({
            includeSelectAllOption: false,
            disableIfEmpty: true,
            numberDisplayed: 1,
            nonSelectedText: '--vos choix--', // modifier le texte lorsqu'aucune option n'est selectionnée
        });
        let btn_refresh_id_trav = $('#'+btn_refresh_id);
        btn_refresh_id_trav.on("click", function() {
            console.log("on refresh la liste des entreprises de la liste "+btn_refresh_id+" id du select= "+mselect_id);
            console.log("le bouton devient vert");
            // on recupère l'icone du bouton
            btn_refresh_id_trav.css({
                'color':'#3bcb15',
                'background-color':"#3bcb15",
                'border-radius':"1em"
            });
            setTimeout(function(){
                // on enlève la couleur verte au bout de 3sec
                btn_refresh_id_trav.css({
                    'color':'#000',
                    'background-color':"#ffffff"
                });
            },3000); // 1 second delay
            get_list_ent_form(mselect_id);
        });
        /*$("."+mselect_class).on("click", function() {
            console.log("on refresh la liste des entreprises");
        });*/

        current_form_id = form_id;
        current_id = j;

        let form = document.getElementById(form_id);
        let montant_marche = document.getElementById(montant_marche_id);
        let honoraire = document.getElementById(honoraire_id);
        let date_debut = document.getElementById(date_debut_id);
        let date_fin = document.getElementById(date_fin_id);
        let duree = document.getElementById(duree_id);
        let mselect = document.getElementById(mselect_id);
        let btn_refresh = document.getElementById(btn_refresh_id);

        /*console.log("id form= "+form_id);
        console.log("id montant_marche= "+montant_marche_id);
        console.log("id honoraire= "+honoraire_id);
        console.log("id date_debut= "+date_debut_id);
        console.log("id date_fin= "+date_fin_id);
        console.log("id duree= "+duree_id);
        console.log("id mselect= "+mselect_id);

        console.log("value montant_marche= "+montant_marche);
        console.log("value honoraire= "+honoraire);
        console.log("value date_debut= "+date_debut);
        console.log("value date_fin= "+date_fin);
        console.log("value duree= "+duree);
        console.log("value mselect= "+mselect);*/

        console.log("COUNT = "+count+" --");
        console.log("CURRENT FORM ID = "+current_form_id+" --");
        console.log("CURRENT ID = "+current_id+" --");
        console.log("TAB LENGTH = "+tab_travaux.length+" --");
    });
    // onclick bouton supprimer OK
    $(del_button).click(function(e) {
        console.log("\n# supprimer clicked");
        console.log("-- COUNT = "+count);
        console.log("-- CURRENT FORM ID = "+current_form_id);
        console.log("-- CURRENT ID = "+current_id);
        console.log("-- TAB LENGTH = "+tab_travaux.length);

        if(count >= 0 && current_id >= 0) { // si au moins une form existe && que l'id de la forme current est au moins égale à 0

            // on supprime la form actuelle
            console.log("suppression de la form= " + current_form_id);
            let elem = document.getElementById(current_form_id);
            console.log("parentnode= "+elem.parentNode.localName);
            elem.parentNode.removeChild(elem);

            // suppression de la form dans le tableau
            delete tab_travaux[current_id];

            // mise à jour des infos
            count--;        // nombre total de form mise à jour
            current_id--;   // current id mis à jour
            current_form_id=(current_id>=0)?"form"+current_id.toString():undefined; // current form id mis à jour

            // on affiche la form précédente si current_id >=0
            if(current_id >=0) {
                console.log("affichage de la form= " + current_form_id);
                document.getElementById(current_form_id).style.display = "block";
            }
            else {
                console.log("il n'existe aucune form à afficher après suppression");
            }
        }
        else {
            alert("aucune form a supprimer");
            current_id = -1;
        }
    });
    // onclick bouton precedent ~OK (ne fonctionne pas après suppression)
    $(prev_button).click(function(e) {
        console.log("\n# precedent clicked");
        console.log("-- COUNT = "+count);
        console.log("-- CURRENT FORM ID = "+current_form_id);
        console.log("-- CURRENT ID = "+current_id);
        console.log("-- TAB LENGTH = "+tab_travaux.length);

        if(count > 0 && current_id > 0) {
            // on cache la form actuelle
            document.getElementById(current_form_id).style.display = "none";
            // on affiche la form précédente
            var a = current_id;
            try {
                document.getElementById("form" + (a - 1).toString()).style.display = "block";
                current_form_id = "form" + (a - 1);
                current_id--;
            }
            catch(e) {
                console.log("erreur fct precedent= form"+(a-1)+" n'existe pas\n"+e);
            }
        }
        else {
            if(count === 0) {
                alert("c'est le premier travaux, on ne peux pas revenir en arrière");
            }
            else {
                alert("aucun travaux n'est présent, comment voulez vous aller au précédent ???");
            }
        }
    });
    // onclick bouton suivant ~OK (ne fonctionne pas après suppression)
    $(nxt_button).click(function(e) {
        console.log("\n# suivant clicked");
        console.log("-- COUNT = "+count);
        console.log("-- CURRENT FORM ID = "+current_form_id);
        console.log("-- CURRENT ID = "+current_id);
        console.log("-- TAB LENGTH = "+tab_travaux.length);

        console.log("opération= "+(current_id+1)+"\n");

        var a = current_id;
        var current = a+1;

        // si l'id de la form courante +1 est inférieure ou égale à la taille du tableau
        if(count >= 0 && current <= count) {
            let form_trouve = false;
            // on cache la form actuelle
            document.getElementById(current_form_id).style.display = "none";

            while(!form_trouve && current <= count) {
                try {
                    // on affiche la form suivante
                    document.getElementById("form" + current.toString()).style.display = "block";

                    // mise à jour des informations
                    current_form_id = "form" + current.toString();
                    current_id++;
                    form_trouve = true;
                }
                catch (e) {
                    console.log("erreur fct suivante= form" + current + " n'existe pas\n" + e);
                    current++;
                    console.log("form_trouve= "+form_trouve);
                    console.log("current= "+current);
                    console.log("count= "+count);
                }
            }
        }
        else {
            if(current_id === count && count !== -1/*count === tab_travaux.length*/) {
                alert("vous êtes déjà sur le dernier travaux");
            }
            else {
                alert("aucun travaux n'est présent, comment voulez vous aller au suivant ???");
            }
        }
    });
    // onclick bouton reset
    $(rst_button).click(function(e) {
        console.log("\n# reset clicked");

        count = -1;
        current_form_id = undefined;
        current_id = -1;
        tab_travaux = [];

        var placeholder     = $("#form-placeholder");
        placeholder.innerHTML = "";

        updateDiv();

        console.log("-- COUNT = "+count);
        console.log("-- CURRENT FORM ID = "+current_form_id);
        console.log("-- CURRENT ID = "+current_id);
        console.log("-- TAB LENGTH = "+tab_travaux.length);
    });
    /* onclick bouton switch view
    $(button_ope_switch).click(function (e) {
        if(presentation ===0) {
            presentation = 1;
            console.log("prez changed "+presentation);
            remove_style(0);
            init_style_op();
        }
        else {
            presentation = 0;
            console.log("prez changed "+presentation);
            remove_style(1);
            init_style_op();
        }
    });*/

}
/* Permet de stocker les données du travaux actuel lors d'un ajout d'un nouveau travaux*/
function store_actual_travaux_form() {
    console.log("\n# store_actual_travaux_form() called");

    let travaux = {};

    /*
    travaux['montant_marche'] = "montant"+count.toString();
    travaux['honoraire'] = "hono"+count.toString();
    travaux['date_debut'] = "year_picker_deb_travaux"+count.toString();
    travaux['date_fin'] = "year_picker_fin_travaux"+count.toString();
    travaux['duree'] = "duree"+count.toString();*/

    travaux['montant_marche'] = document.getElementById("montant"+count.toString()).value;
    travaux['honoraire'] = document.getElementById("hono"+count.toString()).value;
    travaux['date_debut'] = document.getElementById("year_picker_deb_travaux"+count.toString()).value;
    travaux['date_fin'] = document.getElementById("year_picker_fin_travaux"+count.toString()).value;
    travaux['duree'] = document.getElementById("duree"+count.toString()).value;
    travaux['list_entreprise'] = $("#mselect"+count.toString()).val();
    if(!tab_travaux.includes(travaux)) { // si le travaux n'est pas déjà stocké dans le tableau
        tab_travaux.push(travaux);
        console.log("new travaux stored="+tab_travaux);
    }
    console.log("tab_travaux now= "+tab_travaux);
}
/* Permet de cacher le travaux précédents */
function hide_prev_travaux_form() {
    console.log("\n# hide_prev_travaux_form() called ");
    console.log("FORM N° "+current_form_id+" hidded"+"\n");

    document.getElementById(current_form_id).style.display = "none";
}
/* Permet de rafraichir le div après opération suppression dans notre cas */
function updateDiv() {
    $( "#form-placeholder" ).load(window.location.href + " #form-placeholder" );
}
/* Affiche le select d'input chekboxe */
function showCheckboxes() {
    var checkboxes = document.getElementById("checkboxes");
    if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
    } else {
        checkboxes.style.display = "none";
        expanded = false;
    }
}
/* Initialise les options de la checkbox a 'non coché' */
function init_style_op() {
    // fenêtre F1 M1 opération insert
    var btn_nxt_forma    = $("#btn_nxt_a");
    var btn_prev_forme   = $("#btn_prev_e");
    var btn_nxt_forme    = $("#btn_nxt_e");
    var btn_prev_formt   = $("#btn_prev_t");
    let master_container = $("#i_master_container");
    let container_form = $("#i_container_form");
    let sub_container_form = $("#i_sub_container_form");
    let step1 = $("#step1");
    let step2 = $("#step2");
    let step3 = $("#step3");

    // on applique le theme d'affichage pour la fenêtre F1 M1 opération insert
    if(presentation === 1) { // mode compact

        /* on affecte le css */
        master_container.css({
        });
        container_form.css({
            // 'border-style': 'solid',
            // 'border-color': 'green',
            'color': 'black',
            'letter-spacing': '1px'
        });
        sub_container_form.css({
            //'border-style': 'solid',
            // 'border-color': 'red',
            'display': 'block',
            'width':'500px'
        });
        step1.css({
            'position': 'absolute',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'10%'
        });
        step2.css({
            'position': 'absolute',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'10%'
        });
        step3.css({
            'position': 'absolute',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'10%'
        });

        // onclick bouton navigation precedente form
        // onclick bouton navigation suivante form
        $(btn_nxt_forma).click(function(e) {
            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");

            if(step1_v) { // si form1 active
                // on active la step2
                step2.show();
                step2_v = true;
                // on cache les step1 et step 3
                step1.hide();
                step1_v = false;
                step3.hide();
                step3_v = false;
            }
            else if(step2_v) { // si form2 active
                // on active la step3
                step3.show();
                step3_v = true;
                // on cache les step1 et step2
                step1.hide();
                step1_v = false;
                step2.hide();
                step2_v = false;
            }
            else {
                console.log("pas de form suivante après la step3");
                // on ne fait rien car la step3 est active et aucune step après
            }
        });
        // onclick bouton navigation precedente form
        $(btn_prev_forme).click(function(e) {
            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");
            if(step2_v) { // si step2 active
                // on active la step1
                step1.show();
                step1_v = true;
                // on cache la step2 et step3
                step2.hide();
                step2_v = false;
                step3.hide();
                step3_v = false;

            }
            else if(step3_v) { // si step3 active
                // on active la step2
                step2.show();
                step2_v = true;
                // on cache la step1 et step3
                step1.hide();
                step1_v = false;
                step3.hide();
                step3_v = false;
            }
            else {
                console.log("pas de form précédente après la step1");
                // on ne fait rien car la step1 est active et aucune step avant
            }
        });
        // onclick bouton navigation suivante form
        $(btn_nxt_forme).click(function(e) {
            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");

            if(step1_v) { // si form1 active
                // on active la step2
                step2.show();
                step2_v = true;
                // on cache les step1 et step 3
                step1.hide();
                step1_v = false;
                step3.hide();
                step3_v = false;
            }
            else if(step2_v) { // si form2 active
                // on active la step3
                step3.show();
                step3_v = true;
                // on cache les step1 et step2
                step1.hide();
                step1_v = false;
                step2.hide();
                step2_v = false;
            }
            else {
                console.log("pas de form suivante après la step3");
                // on ne fait rien car la step3 est active et aucune step après
            }
        });
        // onclick bouton navigation precedente form
        $(btn_prev_formt).click(function(e) {
            let step1 = $("#step1");
            let step2 = $("#step2");
            let step3 = $("#step3");
            if(step2_v) { // si step2 active
                // on active la step1
                step1.show();
                step1_v = true;
                // on cache la step2 et step3
                step2.hide();
                step2_v = false;
                step3.hide();
                step3_v = false;

            }
            else if(step3_v) { // si step3 active
                // on active la step2
                step2.show();
                step2_v = true;
                // on cache la step1 et step3
                step1.hide();
                step1_v = false;
                step3.hide();
                step3_v = false;
            }
            else {
                console.log("pas de form précédente après la step1");
                // on ne fait rien car la step1 est active et aucune step avant
            }
        });

        // on affiche les boutons suivant et précédents
        btn_nxt_forma.show();
        btn_prev_forme.show();
        btn_nxt_forme.show();
        btn_prev_formt.show();

        // on met la step 1 active
        step1.show();
        step2.hide();
        step3.hide();
    }
    else { // mode non compacte

        /* on affecte le css*/
        master_container.css({
            // 'border-style': 'solid',
            // 'border-color': 'red',
            'overflow-x': 'scroll',
            'overflow-y': 'scroll',
            'white-space': 'nowrap',
            'padding':'1%'
        });

        container_form.css({
            // 'border-style': 'solid',
            // 'border-color': 'green',
            'width': 'auto',
            'color': 'black',
            'letter-spacing': '1px'
        });

        step1.css({
            // 'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': 'inline-block',
            'vertical-align':'top',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'2.5%'
        });
        step2.css({
            //'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': 'inline-block',
            'vertical-align':'top',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'2.5%'
        });
        step3.css({
            // 'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': 'inline-block',
            'vertical-align':'top',
            'width': '500px',
            'border-radius': '10px',
            'background': 'white',
            'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)',
            'margin-right':'2.5%'
        });

        // on met la step 1 active
        step1.show();
        step2.show();
        step3.show();

        // on cache les boutons suivant et précédents
        btn_nxt_forma.hide();
        btn_prev_forme.hide();
        btn_nxt_forme.hide();
        btn_prev_formt.hide();

        $('#step1 input').css({
            'color':'#1110b4',
            'font-weight':'bold',
            'font-size':'20px',
            'border':'solid #1110b4 1px'
        });
        $('#step2 input').css({
            'width:':'14em',
            'max-width':'20em',
            'color':'#1110b4',
            'font-weight':'bold',
            'font-size':'20px',
            'border':'solid #1110b4 1px'
        });
        $('#step3 input').css({
            'color':'#1110b4',
            'font-weight':'bold',
            'font-size':'20px',
            'border':'solid #1110b4 1px'
        });
        $('#year_picker').css({
            'color':'#000000',
            'font-weight':'normal',
            'font-size':'16px',
            'border':'solid 1px black'
        });
    }
}
function init_style_op_suppression() {
    // fenpetre F1 M2 opération suppression
    let crit_value_num_arch = $("#crit_value_num_arch");
    let crit_value_nom_arch = $("#crit_value_nom_arch");
    let crit_value_nom_edi = $("#crit_value_nom_edi");
    let crit_value_prop_select = $("#crit_value_prop_select");
    let crit_value_prim_select = $("#crit_value_prim_select");
    let crit_value_sec_select = $("#crit_value_sec_select");
    let crit_value_mselect_ter = $("#crit_value_mselect_ter");
    let crit_value_year_picker = $("#crit_value_year_picker");

    // on applique le css pour la fenêtre F1 opération M2 insert
    crit_value_num_arch.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_nom_arch.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_nom_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_prop_select.css({
    });
    crit_value_prim_select.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_sec_select.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_mselect_ter.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_year_picker.css({
        'visibility': 'hidden',
        'display':'none'
    });

    $('#content_f1_supp input').css({
        'color':'#1110b4',
        'font-weight':'bold',
        'font-size':'20px',
        'border':'solid #FFFFFF 1px'
    });
}
function init_style_recherche() {
    // fenêtre F2 recherche
    let master_container_F2 = $("#i_master_container_F2");
    let container_form_F2 = $("#i_container_form_F2");
    let sub_container_form_F2 = $("#i_sub_container_form_F2");
    let step1_F2 = $("#step1_F2");

    let crit_value_type_edi = $("#crit_value_type_edi_select");
    let crit_value_commu_edi = $("#crit_value_commu_edi");
    let crit_value_dep_edi = $("#crit_value_dep_edi");
    let crit_value_prop_edi = $("#crit_value_prop_edi");

    let crit_value_nom_arch = $("#crit_value_nom_arch");
    let crit_value_nom_edi = $("#crit_value_nom_edi");
    let crit_value_prop_select = $("#crit_value_prop_select");
    let crit_value_prim_select = $("#crit_value_prim_select");
    let crit_value_sec_select = $("#crit_value_sec_select");
    let crit_value_mselect_ter = $("#crit_value_mselect_ter");
    let crit_value_year_picker = $("#crit_value_year_picker");

    // on applique le style pour la fenêtre F2 recherche
    /* on affecte le css*/
    master_container_F2.css({
        'white-space': 'nowrap',
        'width':'auto',
        'padding':'1%'
    });
    container_form_F2.css({
        /*'border-style': 'solid',
        'border-color': 'yellow',*/
        'width': 'auto',
        'color': 'black',
        'letter-spacing': '1px'
    });
    sub_container_form_F2.css({
        /*'border-style': 'solid',
        'border-color': 'green',*/
        'width': 'auto',
        'color': 'black'
    });
    step1_F2.css({
        /*'border-style': 'solid',
        'border-color': 'blue',*/
        'vertical-align':'top',
        'border-radius': '10px',
        'background': 'white',
        'box-shadow': '0 27px 55px 0 rgba(0, 0, 0, 0.3), 0 17px 17px 0 rgba(0, 0, 0, 0.15)'
    });

    crit_value_type_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_commu_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_dep_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_prop_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });

    crit_value_nom_arch.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_nom_edi.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_prop_select.css({});
    crit_value_prim_select.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_sec_select.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_mselect_ter.css({
        'visibility': 'hidden',
        'display':'none'
    });
    crit_value_year_picker.css({
        'visibility': 'hidden',
        'display':'none'
    });

    $('#content_f2 input').css({
        'color':'#1110b4',
        'font-weight':'bold',
        'font-size':'20px',
        'max-width': '20em',
        'border':'solid #1110b4 1px'
    });

    $('#table_r').bootstrapTable({
    });

    $('#table_r_t').bootstrapTable({
    });
}
function init_style_informations() {
    $('#table_i').bootstrapTable({
    });

    $('#content_f3 input').css({
        'max-width':'12em',
        'color':'#1211ee',
        'font-weight':'bold',
        'font-size':'15px'
    });
}
/* Remet les styles à 0 des différents thèmes */
function remove_style(mode) {
    let btn_prev_form   = $("#btn_prev");
    let btn_nxt_form    = $("#btn_nxt");
    let master_container = $("#i_master_container");
    let container_form = $("#i_container_form");
    let sub_container_form = $("#i_sub_container_form");
    let step1 = $("#step1");
    let step2 = $("#step2");
    let step3 = $("#step3");

    if(mode === 1) { // mode compacte
        // on met tous les attributs csss à NULL du mode compacte
        container_form.css({
            // 'border-style': 'solid',
            // 'border-color': 'green',
            'color': '',
            'letter-spacing': ''
        });
        sub_container_form.css({
            //'border-style': 'solid',
            // 'border-color': 'red',
            'display': '',
            'width':''
        });
        btn_prev_form.css({
            'margin-left':''
        });
        step1.css({
            'position': '',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });
        step2.css({
            'position': '',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });
        step3.css({
            'position': '',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });

    }
    else { // mode non compacte

        // on met tous les attributs csss à NULL du mode non compacte
        master_container.css({
            // 'border-style': 'solid',
            // 'border-color': 'red',
            'overflow-x': '',
            'overflow-y': '',
            'white-space': '',
            'padding':''
        });

        container_form.css({
            // 'border-style': 'solid',
            // 'border-color': 'green',
            'width': '',
            'color': '',
            'letter-spacing': ''
        });

        step1.css({
            // 'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': '',
            'vertical-align':'',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });
        step2.css({
            //'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': '',
            'vertical-align':'',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });
        step3.css({
            // 'border-style': 'solid',
            // 'border-color': 'yellow',
            'display': '',
            'vertical-align':'',
            'width': '',
            'border-radius': '',
            'background': '',
            'box-shadow': '',
            'margin-right':''
        });

        btn_prev_form.show();
        btn_nxt_form.show();
    }
}
/* Initialise l'interface pour de la recherche d'archive
 *
 * */
function init_interface_recherche() {
    var content_f2 = document.getElementById('content_f2');
    // content_f2.innerHTML = "Recherches spécifiques sur les archives";

    content_f2.innerHTML =''+
        '<div id="i_master_container_F2" class="master_container">'+
        '<div id="i_container_form_F2" class="container_form">'+
        '<div id="i_sub_container_form_F2" class="sub_container_form">'+

        '<div id="step1_F2" class="op-form">'+
        '<form class="op-form-name" >'+
        '<div class="form-title">Critère de recherche d\'archive' +
        '<div class="crit_box_div">'+
        '<select id="crit-select" required="true">'+
        '<option value="">--votre choix--</option>'+
        '<option value="c_edi">par commune</option>'+
        '<option value="d_edi">par département</option>'+
        '<option value="n_arch">par nom d\'archive</option>'+
        '<option value="n_edi">par nom d\'édifice</option>'+
        '<option value="p_arch" selected>par propriétaire d\'archive</option>'+
        '<option value="p_edi">par propriétaire d\'édifice</option>'+
        '<option value="t1_arch">par type primaire</option>'+
        '<option value="t2_arch">par type secondaire</option>'+
        '<option value="t3_arch">par type tertiaire</option>'+
        '<option value="t_edi">par type d\'édifice</option>'+
        '<option value="an">par annee</option>'+
        '</select>'+
        '</div>'+
        '</div>'+
        '<div class="form-body" >'+

        '<div class="row_s">'+
        '<div id="i_crit_box" class="crit_box">'+

        '<div class="crit_box_value">'+

            '<div id="crit_value_commu_edi">'+
            '<input id="r_data_commu_edi" minlength="3" maxlength="19" size="19" type="text" placeholder="nom de la commune">'+
            '</div>'+

            '<div id="crit_value_dep_edi">'+
            '<input id="r_data_dep_edi" minlength="3" maxlength="19" size="19" type="text" placeholder="nom du département">'+
            '</div>'+

            '<div id="crit_value_nom_arch">'+
            '<input id="r_data_nom_arch" minlength="3" maxlength="19" size="19" type="text" placeholder="nom de l\'archive">'+
            '</div>'+

            '<div id="crit_value_nom_edi">'+
            '<input id="r_data_nom_edi" minlength="3" maxlength="50" size="50" type="text" placeholder="nom de l\'édifice">'+
            '</div>'+

            '<div id="crit_value_prop_select">'+
            '<label class="r_label_point" for="r_prop-select" >Choississez un propriétaire:</label><!-- id à récupérer pour enregistrement -->'+
            '<select id="r_prop-select" required="true">'+
            '<option value="">--votre choix--</option>'+
            '</select>'+
            '</div>'+

            '<div id="crit_value_prop_edi">'+
            '<input id="r_data_prop_edi" minlength="3" maxlength="50" size="50" type="text" placeholder="nom du propriétaire d\'édifice">'+
            '</div>'+

            '<div id="crit_value_prim_select">'+
            '<label class="r_label_point" for="r_prim-select">Choississez un type primaire:</label><!-- id à récupérer pour enregistrement -->'+
            '<select id="r_prim-select" required="true">'+
            '<option value="">--votre choix--</option>'+
            '</select>'+
            '</div>'+

            '<div id="crit_value_sec_select">'+
            '<label class="r_label_point" for="r_sec-select">Choississez un type secondaire:</label><!-- id à récupérer pour enregistrement -->'+
            '<select id="r_sec-select" required="true">'+
            '<option value="" >--votre choix--</option>'+
            '</select>'+
            '</div>'+

            '<div id="crit_value_type_edi_select">'+
            '<label class="r_label_point" for="r_tedi-select">Choississez un type d\'édifice:</label>'+
            '<select id="r_tedi-select" required="true">'+
            '<option value="" >--votre choix--</option>'+
            '</select>'+
            '</div>'+

            '<div id="crit_value_mselect_ter">'+
            '<div class="r_container_mselect_ter">' +
            '<label class="label_point_ter">Selectionner les types tertiaires</label>'+
            '<select id="r_m_select_type_ter" multiple="multiple" required="true">' +
            '</select>'+
            '<i id="i_r_btn_ter_refresh" class="fas fa-sync-alt"></i>'+
            '</div>'+
            '</div>'+

            '<div id="crit_value_year_picker">'+
            '<input id="r_year_picker" minlength="3" maxlength="50" size="50" type="text" placeholder="annee de l\'archive">'+
            '</div>'+

        '</div>'+

        '<a id="r_btn_search" href="#" class="btn_search">RECHERCHER</a>'+

        '</div>'+
        '</div>'+
        '<div class="row_t" id="content_all_tab_result">'+

            '<div class="table-responsive-sm">'+
                '<p class="label_tab_title">Caractéristiques de l\'archive et de son édifice<input type="text" id="search_a" class="input_search" onkeyup="search_table_r()" placeholder="chercher une valeur ..."></p>'+
                /*<table id="table_r" class="table table-striped w-auto" data-toggle="table" data-show-columns="true" data-search="true" data-show-toggle="true" data-pagination="true" data-resizable="true" data-height="500"> class="content-table"*/
                '<table id="table_r" class="table table-striped w-auto">'+
                    '<thead>'+
                    '<tr>'+
                        '<th>N°</th>'+
                        '<th>archive</th>'+
                        '<th>Propriétaire</th>'+
                        '<th>Primaire</th>'+
                        '<th>Secondaire</th>'+
                        '<th>Tertiaire</th>'+
                        '<th>Année</th>'+
                        '<th>Archivage</th>'+
                        '<th>Physique</th>'+
                        '<th>Com physique</th>'+
                        '<th>Virtuelle</th>'+
                        '<th>Com virtuelle</th>'+
                        '<th>Nom d\'édifice</th>'+
                        '<th>Type</th>'+
                        '<th>Propriétaire</th>'+
                        '<th>Statut propriétaire</th>'+
                        '<th>Commune</th>'+
                        '<th>Département</th>'+
                    '</tr>'+
                    '</thead>'+
                    '<tbody id="tbody_arch_result">'+
                    '</tbody>'+
                '</table>'+
            '</div>'+

            '<br/>'+

            '<div class="table-responsive-sm">'+
                '<p class="label_tab_title">Listes des travaux<input type="text" id="search_at" class="input_search" onkeyup="search_table_rt()" placeholder="chercher une valeur ..."></p>'+
                /*<table id="table_r_t" class="table table-striped w-auto" data-toggle="table" data-show-columns="true" data-search="true" data-show-toggle="true" data-pagination="true" data-resizable="true" data-height="500"> class="content-table" */
                '<table id="table_r_t" class="table table-striped w-auto">'+
                    '<thead>'+
                    '<tr>'+
                        '<th>ID d\'archive liée</th>'+
                        '<th>N° travaux</th>'+
                        '<th>Montant de marché</th>'+
                        '<th>Honoraire</th>'+
                        '<th>Date de début</th>'+
                        '<th>Date de fin</th>'+
                        '<th>Duree</th>'+
                        '<th>Entreprises</th>'+
                    '</tr>'+
                    '</thead>'+
                    '<tbody id="tbody_trav_result">'+
                    '</tbody>'+
                '</table>'+
            '</div>'+

        '</div>'+

        '</div>'+
        '</form>'+
        '</div>'+

        '</div>'+
        '</div>'+
        '</div>';

        init_style_recherche();
        init_form_event_recherche();

}
/* Initialise l'interface pour la recherche d'informations
 *
 * */
function init_interface_infos() {
    var content_f3 = document.getElementById('content_f3');
    content_f3.style.color ="white";
    content_f3.innerHTML = ''+
        '<div class="expand-collapse">'+

        '<p>Ensemble des requêtes</p>'+
        '<h3 id="first_h3_q1">1. Recherche des informations d\'édifices ayant comme critères :<i id="help_q1" class="fas fa-info-circle" style="margin-left:1em;" title="données renvoyées : propriétaire édifice, propriétaire archive, nom d\'édifice, type d\'édifice, commune, département, date de début de travaux, date de fin de travaux, commentaire virtuel" ></i></h3>'+
        '<div>'+
            '<div id="request_1" class="request_box" class="expand-collapse">'+
                '<br/>'+
                '<div class="request_header_box">'+
                    '&nbsp;&nbsp;<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="i_prop-select">propriétaire d\'archive:</label>'+
                            '<select id="i_op_parch_select" required="true" >'+
                            '<option value="eq" selected>est égal à</option>'+
                            '<option value="neq" >n\'est pas égal à</option>'+
                            '</select>'+
                            '<select id="i_prop-select" required="true" class="ci_prop_select" style="margin-right: 1em;">'+
                            '<option value="">--votre choix--</option>'+
                            '</select>'+

                            '<div class="request_join">'+
                                '<select id="i_join_prop_prop_edi_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+

                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+

                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_pedi_q1">propriétaire d\'édifice:</label>'+
                            '<select id="i_op_pedi_select" required="true">'+
                            '<option value="eq" selected>est égal à</option>'+
                            '<option value="neq" >n\'est pas égal à</option>'+
                            '</select>'+
                            '<input name="prop_edi" id="input_pedi_q1" min="3" max="50" type="text" placeholder="propriétaire d\édifice" style="margin-right: 0.5em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_prop_edi_dep_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+

                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_dep_edi_q1">département:</label>'+
                            '<select id="i_op_dep_select" required="true">'+
                            '<option value="eq" selected>est égal à</option>'+
                            '<option value="neq" >n\'est pas égal à</option>'+
                            '</select>'+
                            '<input name="departement" id="input_dep_edi_q1" min="2" max="50" type="text" placeholder="département" style="margin-right: 0.5em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_dep_c_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+
                '</div>'+
                '<br/>'+
                '<div class="request_header_box">'+
                    '&nbsp;&nbsp;<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_nom_commu_q1">nom de commune:</label>'+
                            '<select id="i_op_commu_select" required="true">'+
                            '<option value="eq" selected>est égal à</option>'+
                            '<option value="neq" >n\'est pas égal à</option>'+
                            '</select>'+
                            '<input name="commune" id="input_nom_commu_q1" min="2" max="50" size="20" type="text" placeholder="commune" style="margin-right: 0.5em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_c_cp_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+
                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_com_phys_q1">commentaire physique:</label>'+
                            '<select id="i_op_comp_select" required="true">'+
                            '<option value="eq" selected>contient</option>'+
                            '<option value="neq" >ne contient pas</option>'+
                            '</select>'+
                            '<input name="com_phys" id="input_com_phys_q1" min="1" max="254" type="text" placeholder="commentaire physique" style="margin-right: 0.5em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_cp_cv_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+
                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_com_virt_q1">commentaire virtuel:</label>'+
                            '<select id="i_op_comv_select" required="true">'+
                            '<option value="eq" selected>contient</option>'+
                            '<option value="neq" >ne contient pas</option>'+
                            '</select>'+
                            '<input name="com_virt" id="input_com_virt_q1" min="1" max="254" type="text" placeholder="commentaire virtuel" style="margin-right: 0.5em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_cv_te_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+
                '</div>'+
                '<br/>'+
                '<div class="request_header_box">'+
                    '&nbsp;&nbsp;<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="i_tedi-select">type d\'édifice </label>'+
                            '<select id="i_op_tedi_select" required="true">'+
                            '<option value="eq" selected>est égal à</option>'+
                            '<option value="neq" >n\'est pas égal à</option>'+
                            '</select>'+
                            '<select id="i_tedi-select" required="true" class="ci_tedi-select" style="margin-right: 1em;">'+
                            '<option value="" >--votre choix--</option>'+
                            '</select>'+
                            '<div class="request_join">'+
                                '<select id="i_join_te_mm_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+
                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_mm_q1">montant de marché</label>'+
                            '<select id="i_op_mm_select" required="true">'+
                            '<option value="inf" selected>est inférieur à</option>'+
                            '<option value="sup">est supérieur à</option>'+
                            '<option value="eq">est égal à</option>'+
                            '</select>'+
                            '<input name="mm" id="input_mm_q1" min="0" max="50" type="number" placeholder="montant de marché" style="margin-right: 1em;">'+
                            '<div class="request_join">'+
                                '<select id="i_join_mm_h_select" required="true">'+
                                '<option value="et" selected>et</option>'+
                                '<option value="ou" >ou</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>&nbsp;&nbsp;&nbsp;&nbsp;'+

                    '<div class="request_header">'+
                        '<div class="div_fit_center">'+
                            '<label for="input_h_q1">montant des honoraires</label>'+
                            '<select id="i_op_h_select" required="true">'+
                            '<option value="inf" selected>est inférieur à</option>'+
                            '<option value="sup" >est supérieur à</option>'+
                            '<option value="eq" >est égal à</option>'+
                            '</select>'+
                            '<input name="mm" id="input_h_q1" min="0" type="number" placeholder="montant des honoraires">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<br/>'+
                '<div class="button_box">'+
                    '<br/>'+
                    '<button id="btn_q1" type="button" class="btn btn-primary">Envoyer</button>'+
                '</div>'+
                '<br/>'+
                '<br/>'+
                '<div class="expand-collapse">'+
                    '<h3 class="q_title_result">RÉSULTATS</h3>'+
                    '<div>'+
                    '<div id="result_q1" class="result_box" class="expand-collapse">'+
                    '<input type="text" id="search_i" class="input_search_i" onkeyup="search_table_i()" placeholder="chercher une valeur ...">'+
                    '<div class="table-responsive-sm">'+
                        '<table id="table_i" class="table table-striped w-auto" >'+
                        '<thead>'+
                        '<tr>'+
                        '<th>N° archive</th>'+
                        '<th>Propriétaire archive</th>'+
                        '<th>N° édifice</th>'+
                        '<th>Propriétaire edifice</th>'+
                        '<th>Nom d\'édifice</th>'+
                        '<th>Type</th>'+
                        '<th>Département</th>'+
                        '<th>Commune</th>'+
                        '<th>Com physique</th>'+
                        '<th>Com virtuelle</th>'+
                        '<th>Date de début</th>'+
                        '<th>Date de fin</th>'+
                        '<th>Montant de marché total</th>'+
                        '<th>Honoraire total</th>'+
                        '</tr>'+
                        '</thead>'+
                        '<tbody id="tbody_info_result">'+
                        '</tbody>'+
                        '</table>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                '</div>'+

            '</div>'+
        '</div>'+

    '</div>';

    init_style_informations();
    init_form_event_infos();
}
/*
 * Intialise l'interface pour les résultats
 */
function init_interface_console() {
    let content_console = document.getElementById('content_console');
    content_console.innerText = "Résultat des opérations des 3 fenêtres";
}
/* Intialise les composants de l'interface opération mode insertion */
function init_form_event_insert() {
    /* met tous les checkbox à 0 */
    let id_multiple_select = $("#m_select_type_ter");
    let btn_ter_refresh = $('#i_btn_ter_refresh');
    let checkbox_link_edi = $("#link_edi");
    let checkbox_link_trav = $("#link_trav");
    let btn_nxt_forma    = $("#btn_nxt_a");
    let btn_prev_forme    = $("#btn_prev_e");
    let btn_nxt_forme    = $("#btn_nxt_e");
    let btn_prev_formt    = $("#btn_nxt_t");

    id_multiple_select.multiselect({
        includeSelectAllOption: false,
        disableIfEmpty: true,
        numberDisplayed: 1,
        nonSelectedText: '--vos choix--', // modifier le texte lorsqu'aucune option n'est selectionnée
        /*includeResetOption: true,  un bouton reset est affiché pour déselectioner toutes les options*/
        /*includeResetDivider: true,  inclue une barre de séparation en dessous le bouton reset*/
        /*enableFiltering: true,  active la barre de recherche pr le filtrage des noms d'options*/
    });
    $("#prop-select").on("click", function() {
        get_prop_form();
        $('#prop-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#prop-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#prim-select").on("click", function() {
        get_type_prim_form();
        $('#prim-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#prim-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#sec-select").on("click", function() {
        get_type_sec_form();
        $('#sec-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#sec-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    btn_ter_refresh.on("click", function() {
        console.log("le bouton devient vert");
        // on recupère l'icone du bouton
        btn_ter_refresh.css({
            'color':'#3bcb15',
            'background-color':"#3bcb15",
            'border-radius':"1em"
        });
        setTimeout(function(){
            // on enlève la couleur verte au bout de 3sec
            btn_ter_refresh.css({
                'color':'#000',
                'background-color':"#ffffff"
            });
        },3000); // 1 second delay
        // on change la couleur du bouton
        get_type_ter_form();
    });
    $("#tedi-select").on("click", function() {
        get_type_edi_form();
        $('#tedi-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#tedi-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    checkbox_link_edi.change(function() {
        console.log("checkbox_link_edi change called");
        let edi_form = $("#step2");
        let edi_trav = $("#step3");
        if(checkbox_link_edi.prop("checked") === true) {
            console.log("on active la forme");
            edi_form.attr('disabled',false);
        }
        else if(checkbox_link_edi.prop("checked") === false) {
            console.log("on disable la forme");
            edi_form.attr('disabled','disabled');
            edi_trav.attr('disabled','disabled'); // on désactive aussi travaux
        }
        else {
            console.log("checkbox_link_edi ne possède ni true ni false");
        }

    });
    checkbox_link_trav.change(function() {
        console.log("checkbox_link_trav change called");
        let edi_trav = $("#step3");
        if(checkbox_link_trav.prop("checked") === true) {
            console.log("on active la forme");
            edi_trav.attr('disabled',false);
            // on reset la forme travaux pour éviter le bug de non affichage des formes déjà ajoutés
            $("#reset").click();
        }
        else if(checkbox_link_trav.prop("checked") === false) {
            console.log("on disable la forme");
            edi_trav.attr('disabled','disabled');
        }
        else {
            console.log("checkbox_link_edi ne possède ni true ni false");
        }
    });
}
/* Intialise les composants de l'interface opération suppression */
function init_form_event_suppression() {
    /* met tous les checkbox à 0 */
    let select_crit = $("#crit-select_s");
    let id_multiple_select = $("#s_m_select_type_ter");
    let btn_ter_refresh = $('#i_s_btn_ter_refresh');

    let crit_value_num_arch = $("#crit_value_num_arch");
    let crit_value_nom_arch = $("#crit_value_nom_arch");
    let crit_value_nom_edi = $("#crit_value_nom_edi");
    let crit_value_prop_select = $("#crit_value_prop_select");
    let crit_value_prim_select = $("#crit_value_prim_select");
    let crit_value_sec_select = $("#crit_value_sec_select");
    let crit_value_mselect_ter = $("#crit_value_mselect_ter");
    let crit_value_year_picker = $("#crit_value_year_picker");

    let btn_del = $("#s_btn_del");

    select_crit.change(function() {
        switch($(this).children("option:selected").val()) {
            case "num_arch":
                crit_value_num_arch.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "num_arch";
                break;
            case "n_arch":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "n_arch";
                break;
            case "n_edi":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "n_edi";
                break;
            case "p_arch":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "p_arch";
                break;
            case "t1_arch":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "t1_arch";
                break;
            case "t2_arch":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_del = "t2_arch";
                break;
            case "an":
                crit_value_num_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_del = "an";
                break;
        }
    });
    id_multiple_select.multiselect({
        includeSelectAllOption: false,
        disableIfEmpty: true,
        numberDisplayed: 1,
        nonSelectedText: '--vos choix--', // modifier le texte lorsqu'aucune option n'est selectionnée
        /*includeResetOption: true,  un bouton reset est affiché pour déselectioner toutes les options*/
        /*includeResetDivider: true,  inclue une barre de séparation en dessous le bouton reset*/
        /*enableFiltering: true,  active la barre de recherche pr le filtrage des noms d'options*/
    });
    $("#s_prop-select").on("click", function() {
        get_prop_form();
        $('#s_prop-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#s_prop-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#s_prim-select").on("click", function() {
        get_type_prim_form();
        $('#s_prim-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#s_prim-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#s_sec-select").on("click", function() {
        get_type_sec_form();
        $('#s_sec-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#s_sec-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    btn_del.on("click", function() {
        var content_f1_supp = $('#content_f1_supp');
        console.log("on supprime l'archive");
        del_archive();
        btn_del.disabled = true;
        setTimeout(function(){
            btn_del.disabled = false;
        },3000); // 3 second delay
    });
}
/* Intialise les composants de l'interface recherche*/
function init_form_event_recherche() {
    /* met tous les checkbox à 0 */
    let select_crit = $("#crit-select");
    let id_multiple_select = $("#r_m_select_type_ter");
    let btn_ter_refresh = $('#i_r_btn_ter_refresh');

    let crit_value_type_edi = $("#crit_value_type_edi_select");
    let crit_value_commu_edi = $("#crit_value_commu_edi");
    let crit_value_dep_edi = $("#crit_value_dep_edi");
    let crit_value_prop_edi = $("#crit_value_prop_edi");

    let crit_value_nom_arch = $("#crit_value_nom_arch");
    let crit_value_nom_edi = $("#crit_value_nom_edi");
    let crit_value_prop_select = $("#crit_value_prop_select");
    let crit_value_prim_select = $("#crit_value_prim_select");
    let crit_value_sec_select = $("#crit_value_sec_select");
    let crit_value_mselect_ter = $("#crit_value_mselect_ter");
    let crit_value_year_picker = $("#crit_value_year_picker");

    let btn_search = $("#r_btn_search");

    select_crit.change(function() {
        switch($(this).children("option:selected").val()) {
            case "t_edi":
                crit_value_type_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "t_edi";
                break;
            case "c_edi":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "c_edi";
                break;
            case "d_edi":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "d_edi";
                break;
            case "n_arch":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "n_arch";
                break;
            case "n_edi":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "n_edi";
                break;
            case "p_arch":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "p_arch";
                break;
            case "p_edi":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                })
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "p_edi";
                break;
            case "t1_arch":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "t1_arch";
                break;
            case "t2_arch":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "t2_arch";
                break;
            case "t3_arch":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_value_year_picker.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_search = "t3_arch";
                break;
            case "an":
                crit_value_type_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_commu_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_dep_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_arch.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_nom_edi.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prop_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_prim_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_sec_select.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_mselect_ter.css({
                    'visibility': 'hidden',
                    'display':'none'
                });
                crit_value_year_picker.css({
                    'visibility': 'visible',
                    'display':'block'
                });
                crit_search = "an";
                break;
        }
    });
    id_multiple_select.multiselect({
        includeSelectAllOption: false,
        disableIfEmpty: true,
        numberDisplayed: 1,
        nonSelectedText: '--vos choix--', // modifier le texte lorsqu'aucune option n'est selectionnée
        /*includeResetOption: true,  un bouton reset est affiché pour déselectioner toutes les options*/
        /*includeResetDivider: true,  inclue une barre de séparation en dessous le bouton reset*/
        /*enableFiltering: true,  active la barre de recherche pr le filtrage des noms d'options*/
    });
    $("#r_prop-select").on("click", function() {
        get_prop_form();
        $('#r_prop-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#r_prop-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#r_prim-select").on("click", function() {
        get_type_prim_form();
        $('#r_prim-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#r_prim-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    $("#r_sec-select").on("click", function() {
        get_type_sec_form();
        $('#r_sec-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#r_sec-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    btn_ter_refresh.on("click", function() {
        console.log("le bouton devient vert");
        // on recupère l'icone du bouton
        btn_ter_refresh.css({
            'color':'#3bcb15',
            'background-color':"#3bcb15",
            'border-radius':"1em"
        });
        setTimeout(function(){
            // on enlève la couleur verte au bout de 3sec
            btn_ter_refresh.css({
                'color':'#000',
                'background-color':"#ffffff"
            });
        },3000); // 1 second delay
        get_type_ter_form();
    });
    $("#r_tedi-select").on("click", function() {
        get_type_edi_form();
        $('#r_tedi-select').prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            $('#r_tedi-select').prop('disabled',false);
        },3000); // 1 second delay
    });
    btn_search.on("click", function() {
        get_archive_searched();
        btn_search.disabled = true;
        setTimeout(function(){
            btn_search.disabled = false;
        },3000); // 3 second delay
    });

}
/* Intialise les composants de l'interface infos*/
function init_form_event_infos() {

    var btn_q1 = $("#btn_q1");
    let select_p_arch = $("#i_prop-select");
    let select_t_edi = $("#i_tedi-select");

    btn_q1.on("click", function() {
        console.log("query called");
        valid_data_form_infos_query_1();
        btn_q1.prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            btn_q1.prop('disabled',false);
        },3000); // 1 second delay
    });
    select_p_arch.on("click", function() {
        get_prop_form();
        select_p_arch.prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            select_p_arch.prop('disabled',false);
        },3000); // 1 second delay
    });
    select_t_edi.on("click", function() {
        get_type_edi_form();
        select_t_edi.prop('disabled',true);
        setTimeout(function(){
            // enable click after 1 second
            select_t_edi.prop('disabled',false);
        },3000); // 1 second delay
    });

    $('.expand-collapse h3').each(function() {
        var tis = $(this), state = false, answer = tis.next('div').slideUp();
        tis.click(function() {
            state = !state;
            answer.slideToggle(state);
            tis.toggleClass('active',state);
        });
    });
}
/** Vérification des formulaires insert (arch, edi, trav) **/
function valid_data_form(link_edi, link_trav) {

    let valid_data = true;

    // on récupère la console pour écrire les résultats dedans
    let content_console = document.getElementById('content_console');

    // on récupère les éléments à enregistrer contenant des values
    let data_nom_archive = $('#data_nom_arch').val();
    let data_proprio_archive = $('#prop-select').val();
    let data_type_primaire_archive = $('#prim-select').val();
    let data_type_secondaire_archive = $('#sec-select').val();
    let data_type_tertiaire_archive = $('#m_select_type_ter').val();
    let data_annee_archive = $('#year_picker').val();
    let data_annee_archive_unknow = $('#year_inconnu').prop("checked");

    let data_est_physique_archive = $('#phys').prop("checked");
    let phys_com = $('#phys_com').val();
    let data_com_physique_archive = (data_est_physique_archive === true)?((phys_com === "")?"vide":phys_com):"vide";
    let data_est_virtuelle_archive = $('#virt').prop("checked");
    let virt_com =$('#virt_com').val();
    let data_com_virtuelle_archive = (data_est_virtuelle_archive === true)?((virt_com === "")?"vide":virt_com):"vide";

    let data_nom_edifice = $('#nom_edi').val();
    let data_type_edifice = $('#tedi-select').val();
    let data_commune_edifice = $('#commu_edi').val();
    let data_departement_edifice = $('#dep_edi').val();
    let data_nom_proprio_edifice = $('#prop_edi').val();
    let data_est_particulier_proprio_edifice = $('#est_part_prop').prop("checked");
    let data_est_commune_proprio_edifice = $('#est_commu_prop').prop("checked");
    let data_tab_travaux = tab_travaux;

    content_console.innerHTML = "<p class='title_mode'># opération archive</p>";
    content_console.innerHTML += "<p class='title_point'>- Traitement de la demande d'insertion</p>";
    content_console.innerHTML += "<p class='title_point'>- Vérification du formulaire en cours ....</p>";

    // VERIFICATIONS ARCHIVES
    content_console.innerHTML += "<li class='title_point_error'> Archive</li>";
    // console.log("data_nom_archive= "+data_nom_archive);
    if(data_nom_archive === "" && data_nom_archive.length < 3 || data_nom_archive > 20) {
        // console.log("nom d'archive incorrecte");
        // css erreur
        content_console.innerHTML += "<li class='title_error'>le nom de l'archive est incorrecte</li>";
        valid_data = false;
    }
    else {
        //console.log("nom d'archive est correct\n\n");
    }
    //console.log("data_proprio_archive= "+data_proprio_archive);
    if(data_proprio_archive === "") {
        //console.log("propriétaire incorrecte\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de propriétaire d'archive</li>";
        valid_data = false;
    }
    else {
        //console.log("propriétaire correct\n\n");
    }
    //console.log("data_type_primaire_archive= "+data_type_primaire_archive);
    if(data_type_primaire_archive === "") {
        //console.log("type primaire incorrecte\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de type primaire</li>";
        valid_data = false;
    }
    else {
        //console.log("type primaire correct\n\n");
    }
    //console.log("data_type_secondaire_archive= "+data_type_secondaire_archive);
    if(data_type_secondaire_archive === "") {
        //console.log("type secondaire incorrect\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de type secondaire</li>";
        valid_data = false;
    }
    else {
        //console.log("type secondaire incorrect\n\n");
    }
    //console.log("data_type_tertiaire_archive= "+data_type_tertiaire_archive);
    if(data_type_tertiaire_archive === "" || data_type_tertiaire_archive === null || data_type_tertiaire_archive === undefined) {
        //console.log("types tertiaires incorrects\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de types tertiaires</li>";
        valid_data = false;
    }
    else {
        //console.log("les types tertiaires sont correct\n\n");
    }
    //console.log("data_annee_archive= "+data_annee_archive);
    if(data_annee_archive === "" && data_annee_archive_unknow === false) {
        console.log("date d'archive incorrect\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné d'année d'archive</li>";
        valid_data = false;
    }
    else {
        //console.log("date d'archive correct\n\n");
    }
    date_archivage_archive = getDate();
    //console.log("date_archivage_archive= "+date_archivage_archive);
    if(date_archivage_archive === undefined) {
        //console.log("date d'archivage(auto) \"undefined\" donc incorrecte\n\n");
        content_console.innerHTML += "<li class='title_error'>la date d'archivage(auto) ne peut être calculée</li>";
        valid_data = false;
    }
    else {
        //console.log("date d'archivage(auto) correcte\n\n");
    }
    //console.log("data_est_physique_archive= "+data_est_physique_archive);
    //console.log("data_est_virtuelle_archive= "+data_est_virtuelle_archive);
    if(data_est_physique_archive === false && data_est_virtuelle_archive === false) {
        //console.log("état physique ET/OU virtuelle incorrect, l'archive doit être physique ET/OU virtuelle\n\n");
        content_console.innerHTML += "<li class='title_error'>vous n'avez pas indiqué si l'archive était physique ET/OU virtuelle</li>";
        valid_data = false;
    }
    else {
        //console.log("état physique ET/OU virtuelle correct\n\n");
    }
    //console.log("data_com_physique_archive= "+data_com_physique_archive);
    //console.log("data_com_virtuelle_archive= "+data_com_virtuelle_archive);
    if((data_est_physique_archive === false && data_com_physique_archive !== "" && data_com_physique_archive !== "vide") ||
        (data_est_virtuelle_archive === false && data_com_virtuelle_archive !== "" && data_com_virtuelle_archive !== "vide")) {
        //console.log("commentaires incorrects, si il existe des commentaires pr phys ou virt alors l'archive doit être de ce type\n\n");
        content_console.innerHTML += "<li class='title_error'>Si vous </li>";
        valid_data = false;
    }
    else {
        //console.log("commentaires corrects\n\n");
    }

    // si un édifice est lié on le vérifie
    if(link_edi) {
        /* VERIFICATION EDIFICE */
        content_console.innerHTML += "<li class='title_point_error'> Édifice</li>";
        //console.log("data_nom_edifice= "+data_nom_edifice);
        if(data_nom_edifice === "") {
            //console.log("nom édifice incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de nom d'édifice</li>";
            valid_data = false;
        }
        else {
            //console.log("nom édifice correcte\n\n");
        }
        //console.log("data_type_edifice= "+data_type_edifice);
        if(data_type_edifice === "") {
            //console.log("type d'édifice incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de type d'édifice</li>";
            valid_data = false;
        }
        else {
            //console.log("type d'édifice correcte\n\n");
        }
        //console.log("data_commune_edifice= "+data_commune_edifice);
        if(data_commune_edifice === "") {
            //console.log("commune édifice incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de commune pour l'édifice</li>";
            valid_data = false;
        }
        else {
            //console.log("commune édifice correcte\n\n");
        }
        //console.log("data_departement_edifice= "+data_departement_edifice);
        if(data_departement_edifice === "") {
            //console.log("département édifice incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de département pour l'édifice</li>";
            valid_data = false;
        }
        else {
            //console.log("département édifice correcte\n\n");
        }
        //console.log("data_nom_proprio_edifice= "+data_nom_proprio_edifice);
        if(data_nom_proprio_edifice === "") {
            //console.log("propriétaire édifice incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>vous n'avez pas renseigné de propriétaire pour l'édifice</li>";
            valid_data = false;
        }
        else {
            //console.log("propriétaire édifice correcte\n\n");
        }
        //console.log("data_est_particulier_proprio_edifice= "+data_est_particulier_proprio_edifice);
        //console.log("data_est_commune_proprio_edifice= "+data_est_commune_proprio_edifice);
        if(data_est_particulier_proprio_edifice === false && data_est_commune_proprio_edifice === false) {
            // console.log("état du propriétaire incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>état du propriétaire d'édifice \"particulier\" OU \"commune\" incorrecte </li>";
            valid_data = false;
        }
        else if(data_est_particulier_proprio_edifice === true && data_est_commune_proprio_edifice === true) {
            //console.log("état du propriétaire incorrecte\n\n");
            content_console.innerHTML += "<li class='title_error'>état du propriétaire d'édifice \"particulier\" OU \"commune\" incorrecte </li>";
            valid_data = false;
        }
        else {
            //console.log("état du propriétaire correcte\n\n");
        }

        // si des travaux sont liés, on les vérifie
        if(link_trav) {
            /* VERIFICATION TRAVAUX */
            content_console.innerHTML += "<li class='title_point_error'> Travaux</li>";
            if(data_tab_travaux.length > 0) {
                // console.log("data_tab_travaux= "+JSON.stringify(data_tab_travaux));
                for (let i = 0; i < data_tab_travaux.length; i++) {
                    //console.log("travaux N°" + i);
                    //console.log("travail " + JSON.stringify(data_tab_travaux[i]));
                    if(data_tab_travaux[i].montant_marche === "") {
                        //console.log("travaux N°" + i+" montant_marché incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné de montant de travaux</li>";
                        valid_data = false;
                    }
                    else {
                        //console.log("travaux N°" + i+" montant_marché correcte");
                    }
                    if(data_tab_travaux[i].honoraire === "") {
                        //console.log("travaux N°" + i+" honoraire incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné d'honoraire</li>";
                        valid_data = false;
                    }
                    else {
                        //console.log("travaux N°" + i+" honoraire correcte");
                    }
                    if(data_tab_travaux[i].date_debut === "") {
                        //console.log("travaux N°" + i+" date_debut incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné de date de début</li>";
                        valid_data = false;
                    }
                    else {
                        //console.log("travaux N°" + i+" date_debut correcte");
                    }
                    /*if(data_tab_travaux[i].date_fin === "") {
                        //console.log("travaux N°" + i+" date_fin incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné de montant de travaux</li>";
                        valid_data = false;
                    }
                    else {
                        //console.log("travaux N°" + i+" date_fin correcte")
                    }*/
                    if(data_tab_travaux[i].duree === "") {
                        //console.log("travaux N°" + i+" duree incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné de durée de travaux</li>";
                        valid_data = false;
                    }
                    else {
                        // console.log("travaux N°" + i+" duree correcte");
                    }
                    if(data_tab_travaux[i].list_entreprise === null) {
                        //console.log("travaux N°" + i+" list_entreprise incorrecte");
                        content_console.innerHTML += "<li class='title_error'>travaux N° "+ i + " vous n'avez pas renseigné de liste d'entreprise</li>";
                        valid_data = false;
                    }
                    else {
                        //console.log("travaux N°" + i+" list_entreprise correcte");
                    }
                }
            }
            else {
                //console.log("travaux correctes\n\n");
            }
        }
    }



    let s = (valid_data)?"correcte":"incorrecte";
    content_console.innerHTML += "<p class='title_point' style='margin-top:0.5em;' >- Vérification terminée -> formulaire d'insertion "+s+"</p>";

    return valid_data;
}
/* reset les champs et valeurs de la form insert */
function reset_insert_form(link_edi, link_trav) {

    /* on récupère les éléments de la form */
    let nom_archive = $('#data_nom_arch');
    let proprio_archive = $('#prop-select');
    let type_primaire_archive = $('#prim-select');
    let type_secondaire_archive = $('#sec-select');
    let type_tertiaire_archive = $('#m_select_type_ter');
    let annee_archive = $('#year_picker');
    let annee_archive_unknow = $('#year_inconnu');
    let est_physique_archive = $('#phys');
    let phys_com = $('#phys_com');
    let est_virtuelle_archive = $('#virt');
    let virt_com =$('#virt_com');

    let checkbox_link_edi =$('#link_edi');
    let checkbox_link_trav =$('#link_trav');

    let step2_form = $('#step2');
    let step3_form = $('#step3');

    /* on affiche la valeur avant reset */
    console.log("\n\nVALEUR AVANT RESET\n\n");
    console.log("nom_archive= "+nom_archive.val());
    console.log("proprio_archive= "+proprio_archive.val());
    console.log("type_primaire_archive= "+type_primaire_archive.val());
    console.log("type_secondaire_archive= "+type_secondaire_archive.val());
    console.log("type_tertiaire_archive= "+type_tertiaire_archive.val());
    console.log("annee_archive= "+annee_archive.val());
    console.log("annee_archive_unknow= "+annee_archive_unknow.prop("checked"));
    console.log("est_physique_archive= "+est_physique_archive.prop("checked"));
    console.log("com_physique_archive= "+phys_com.val());
    console.log("est_virtuelle_archive= "+est_virtuelle_archive.prop("checked"));
    console.log("com_virtuel_archive= "+virt_com.val());

    // on reset les valeurs de ces éléments
    nom_archive.val("");
    proprio_archive.val("");
    type_primaire_archive.val("");
    type_secondaire_archive.val("");
    type_tertiaire_archive.val("");
    type_tertiaire_archive.multiselect('refresh');
    annee_archive.prop( "disabled", false );
    annee_archive.val("");
    annee_archive_unknow.removeAttr('checked');
    est_physique_archive.removeAttr('checked');
    phys_com.val("");
    est_virtuelle_archive.removeAttr('checked');
    virt_com.val("");

    checkbox_link_edi.removeAttr('checked');
    checkbox_link_trav.removeAttr('checked');

    step2_form.attr('disabled',true);
    step3_form.attr('disabled',true);

    /* on réaffiche ces élements pr vérif */
    console.log("\n\nVALEUR APRES RESET\n\n");
    console.log("nom_archive= "+nom_archive.val());
    console.log("proprio_archive= "+proprio_archive.val());
    console.log("type_primaire_archive= "+type_primaire_archive.val());
    console.log("type_secondaire_archive= "+type_secondaire_archive.val());
    console.log("type_tertiaire_archive= "+type_tertiaire_archive.val());
    console.log("annee_archive= "+annee_archive.val());
    console.log("annee_archive_unknow= "+annee_archive_unknow.prop("checked"));
    console.log("est_physique_archive= "+est_physique_archive.prop("checked"));
    console.log("com_physique_archive= "+phys_com.val());
    console.log("est_virtuelle_archive= "+est_virtuelle_archive.prop("checked"));
    console.log("com_virtuel_archive= "+virt_com.val());

    if(link_edi) {
        /* on récupère les éléments de la form */
        let nom_edifice = $('#nom_edi');
        let type_edifice = $('#tedi-select');
        let commune_edifice = $('#commu_edi');
        let departement_edifice = $('#dep_edi');
        let nom_proprio_edifice = $('#prop_edi');
        let est_particulier_proprio_edifice = $('#est_part_prop');
        let est_commune_proprio_edifice = $('#est_commu_prop');

        /* on affiche la valeur avant reset */
        console.log("\n\nVALEUR AVANT RESET\n\n");
        console.log("nom_edifice= "+nom_edifice.val());
        console.log("type_edifice= "+type_edifice.val());
        console.log("commune_edifice= "+commune_edifice.val());
        console.log("departement_edifice= "+departement_edifice.val());
        console.log("nom_proprio_edifice= "+nom_proprio_edifice.val());
        console.log("est_particulier_proprio_edifice= "+est_particulier_proprio_edifice.prop("checked"));
        console.log("est_commune_proprio_edifice= "+est_commune_proprio_edifice.prop("checked"));

        // on reset les valeurs de ces éléments
        nom_edifice.val("");
        type_edifice.val("");
        commune_edifice.val("");
        departement_edifice.val("");
        nom_proprio_edifice.val("");
        est_particulier_proprio_edifice.removeAttr('checked');
        est_commune_proprio_edifice.removeAttr('checked');

        /* on réaffiche ces élements pr vérif */
        console.log("\n\nVALEUR EDIFICE APRES RESET\n\n");
        console.log("nom_edifice= "+nom_edifice.val());
        console.log("type_edifice= "+type_edifice.val());
        console.log("commune_edifice= "+commune_edifice.val());
        console.log("departement_edifice= "+departement_edifice.val());
        console.log("nom_proprio_edifice= "+nom_proprio_edifice.val());
        console.log("est_particulier_proprio_edifice= "+est_particulier_proprio_edifice.prop("checked"));
        console.log("est_commune_proprio_edifice= "+est_commune_proprio_edifice.prop("checked"));

    }

    if(link_trav) {
        /* on affiche la valeur avant reset */
        console.log("tab_travaux= "+tab_travaux);

        // on reset les valeurs de ces éléments
        tab_travaux = [];

        /* on réaffiche ces élements pr vérif */
        console.log("tab_travaux= "+tab_travaux);

        /* la forme travaux est de toute façon reset à la fin de l'enregistrement */
    }











}
/** Vérification du formulaire de la requête d'information 1 **/
function valid_data_form_infos_query_1() {

    let valid_data = true;

    // on récupère la console pour écrire les résultats dedans
    let content_console = document.getElementById('content_console');

    // on récupère tous les champs
    let crit_p_arch = $('#i_op_parch_select').val();
    let p_arch = $('#i_prop-select').val();
        let join_p_arch = $('#i_join_prop_prop_edi_select').val();
    let crit_p_edi = $('#i_op_pedi_select').val();
    let p_edi = $('#input_pedi_q1').val();
        let join_p_edi = $('#i_join_prop_edi_dep_select').val();
    let crit_dep = $('#i_op_dep_select').val();
    let dep = $('#input_dep_edi_q1').val();
        let join_dep = $('#i_join_dep_c_select').val();
    let crit_commu = $('#i_op_commu_select').val();
    let commu = $('#input_nom_commu_q1').val();
        let join_commu = $('#i_join_c_cp_select').val();
    let crit_com_phys = $('#i_op_comp_select').val();
    let com_phys = $('#input_com_phys_q1').val();
        let join_comp = $('#i_join_cp_cv_select').val();
    let crit_com_virt = $('#i_op_comv_select').val();
    let com_virt = $('#input_com_virt_q1').val();
        let join_comv = $('#i_join_cv_te_select').val();
    let crit_t_edi = $('#i_op_tedi_select').val();
    let t_edi = $('#i_tedi-select').val();
        let join_t_edi = $('#i_join_te_mm_select').val();
    let crit_mm = $('#i_op_mm_select').val();
    let mm = $('#input_mm_q1').val();
        let join_mm = $('#i_join_mm_h_select').val();
    let crit_hono = $('#i_op_h_select').val();
    let hono = $('#input_h_q1').val();

    let p_arch_ar = {};
    let p_edi_ar = {};
    let dep_ar = {};
    let commu_ar = {};
    let com_phys_ar = {};
    let com_virt_ar = {};
    let t_edi_ar = {};
    let crit_mm_ar = {};
    let hono_ar = {};

    let request_ar = {};

    let p_arch_filled=(p_arch !== "" && p_arch !== undefined && p_arch != null);            // si nom de propriétaire spécifié
    if(p_arch_filled) {
        p_arch_ar['crit_p_arch'] = crit_p_arch;
        p_arch_ar['p_arch'] = p_arch;
        p_arch_ar['join_p_arch'] = join_p_arch;
        request_ar['p_arch'] = p_arch_ar;
    }
    let p_edi_filled=(p_edi !== "" && p_edi !== undefined && p_edi != null);                // si nom d'édifice spécifié
    if(p_edi_filled) {
        p_edi_ar['crit_p_edi'] = crit_p_edi;
        p_edi_ar['p_edi'] = p_edi;
        p_edi_ar['join_p_edi'] = join_p_edi;
        request_ar['p_edi'] = p_edi_ar;
    }
    let dep_filled=(dep !== "" && dep !== undefined && dep != null);                        // si département spécifié
    if(dep_filled) {
        dep_ar['crit_dep'] = crit_dep;
        dep_ar['dep'] = dep;
        dep_ar['join_dep'] = join_dep;
        request_ar['dep'] = dep_ar;
    }
    let commu_filled=(commu !== "" && commu !== undefined && commu != null);                // si commune spécifié
    if(commu_filled) {
        commu_ar['crit_commu'] = crit_commu;
        commu_ar['commu'] = commu;
        commu_ar['join_commu'] = join_commu;
        request_ar['commu'] = commu_ar;
    }
    let com_phys_filled=(com_phys !== "" && com_phys !== undefined && com_phys != null);    // si com_phys spécifié
    if(com_phys_filled) {
        com_phys_ar['crit_com_phys'] = crit_com_phys;
        com_phys_ar['com_phys'] = com_phys;
        com_phys_ar['join_com_phys'] = join_comp;
        request_ar['com_phys'] = com_phys_ar;
    }
    let com_virt_filled=(com_virt !== "" && com_virt !== undefined && com_virt != null);    // si com_virt spécifié
    if(com_virt_filled) {
        com_virt_ar['crit_com_virt'] = crit_com_phys;
        com_virt_ar['com_virt'] = com_virt;
        com_virt_ar['join_com_virt'] = join_comv;
        request_ar['com_virt'] = com_virt_ar;
    }
    let t_edi_filled=(t_edi !== "" && t_edi !== undefined && t_edi != null);                // si t_edi spécifié
    if(t_edi_filled) {
        t_edi_ar['crit_t_edi'] = crit_t_edi;
        t_edi_ar['t_edi'] = t_edi;
        t_edi_ar['join_t_edi'] = join_t_edi;
        request_ar['t_edi'] = t_edi_ar;
    }
    let mm_filled=(mm !== "" && mm !== undefined && mm != null);                            // si mm spécifié
    if(mm_filled) {
        crit_mm_ar['crit_mm'] = crit_mm;
        crit_mm_ar['mm'] = mm;
        crit_mm_ar['join_mm'] = join_mm;
        request_ar['mm'] = crit_mm_ar;
    }
    let hono_filled=(hono !== "" && hono !== undefined && hono != null);                    // si hono spécifié
    if(hono_filled) {
        hono_ar['crit_hono'] = crit_hono;
        hono_ar['hono'] = hono;
        request_ar['hono'] = hono_ar;
    }

    content_console.innerHTML = "<p class='title_mode'># opération recherches d'information</p>";
    content_console.innerHTML += "<p class='title_point'>- Traitement de la requête 1</p>";
    content_console.innerHTML += "<p class='title_point'>- Vérification du formulaire en cours ....</p>";

    console.log("request size= "+Object.keys(request_ar).length);

    // ordre des paramètre à respectés p_arch | p_edi | dep | commu | com_phys | com_virt | t_edi | mm | hono
    for (let key0 in request_ar) {
        console.log("index "+key0+" ");
        for (let key in request_ar[key0]) {
            console.log("val["+key0+"]["+key+"]= "+request_ar[key0][key]);
        }
    }
    console.log("last key request_ar= "+request_ar[Object.keys(request_ar).length-1]);
    console.log("request_ar JSON= "+JSON.stringify(request_ar));

    if(Object.keys(request_ar).length === 0 || request_ar === {} || request_ar === null || request_ar === undefined) {
        valid_data = false;
    }
    else {
        valid_data = true;
        let parameter="id_request="+1+"&request_data="+JSON.stringify(request_ar);

        //console.log("parameter= "+parameter);

        query_1(parameter);
    }
    let s = (valid_data)?"correcte":"incorrecte";
    content_console.innerHTML += "<p class='title_point' style='margin-top:0.5em;' >- Vérification terminée -> formulaire requête 1 "+s+"</p>";
    if(!valid_data) {
        alert("Vérification du formulaire terminé \nétat "+s);
    }

    /*console.log("crit_pr_arch= "+crit_pr_arch);
    console.log("p_arch= "+p_arch);
    console.log("join_pa_pe= "+join_pa_pe);
    console.log("crit_p_edi= "+crit_p_edi);
    console.log("p_edi= "+p_edi);
    console.log("join_pe_d= "+join_pe_d);
    console.log("crit_dep= "+crit_dep);
    console.log("dep= "+dep);
    console.log("join_d_c= "+join_d_c);
    console.log("crit_commu= "+crit_commu);
    console.log("commu= "+commu);
    console.log("join_c_cp= "+join_c_cp);
    console.log("crit_com_phys= "+crit_com_phys);
    console.log("com_phys= "+com_phys);
    console.log("join_cp_cv= "+join_cp_cv);
    console.log("crit_com_virt= "+crit_com_virt);
    console.log("com_virt= "+com_virt);
    console.log("join_cv_te= "+join_cv_te);
    console.log("crit_t_edi= "+crit_t_edi);
    console.log("t_edi= "+t_edi);
    console.log("join_te_mm= "+join_te_mm);
    console.log("crit_mm= "+crit_mm);
    console.log("mm= "+mm);
    console.log("join_mm_h= "+join_mm_h);
    console.log("crit_hono= "+crit_hono);
    console.log("hono= "+hono);*/
}
/** Support fonctions **/
function addEvent(element, evnt, funct){
    if (element.attachEvent)
        return element.attachEvent('on'+evnt, funct);
    else
        return element.addEventListener(evnt, funct, false);
}
/** requêtes AJAX **/
/* enregistre l'archive en base de données
 * argument 1 pour juste l'archive
 * argument 2 pour l'archive et un édifice lié
 * argument 3 pour l'archive et un édifice et des travaux lié
 * */
function insert_form(link_edi, link_trav) {

    console.log("link_edi= "+link_edi);
    console.log("link_trav= "+link_trav);
        /* A FAIRE */
        let type_insert="1";
        if(link_trav) {
            type_insert = "3";
        }
        else if(link_edi && !link_trav) {
            type_insert = "2";
        }
        else {
            type_insert = "1";
        }

        console.log("type_insert="+type_insert);
        // on récupère la console pour écrire les résultats dedans
        let content_console = document.getElementById('content_console');

        // on récupère les données de l'archive
        let data_nom_archive = $('#data_nom_arch').val();
        let data_proprio_archive = $('#prop-select').val();
        let data_type_primaire_archive = $('#prim-select').val();
        let data_type_secondaire_archive = $('#sec-select').val();
        let data_type_tertiaire_archive = $('#m_select_type_ter').val();
        let data_annee_archive = $('#year_picker').val();
        let data_annee_archive_unknow = $('#year_inconnu').prop("checked");
        let data_date_archivage = getDate();

        let data_est_physique_archive = $('#phys').prop("checked");
        let phys_com = $('#phys_com').val();
        let data_com_physique_archive = (data_est_physique_archive === true)?((phys_com === "")?"vide":phys_com):"vide";
        let data_est_virtuelle_archive = $('#virt').prop("checked");
        let virt_com =$('#virt_com').val();
        let data_com_virtuelle_archive = (data_est_virtuelle_archive === true)?((virt_com === "")?"vide":virt_com):"vide";
        data_annee_archive =(data_annee_archive_unknow)?"0000-00-00":data_annee_archive;
        console.log("date_anne_archive= "+data_annee_archive);
        let post_parameter =
                    "type_insert="+type_insert+
                    "&nom_archive="+data_nom_archive+
                    "&proprio_archive="+data_proprio_archive+
                    "&type_primaire_archive="+data_type_primaire_archive+
                    "&type_secondaire_archive="+data_type_secondaire_archive+
                    "&type_tertiaire_archive="+data_type_tertiaire_archive+
                    "&annee_archive="+data_annee_archive+
                    "&date_archivage="+data_date_archivage+
                    "&est_physique_archive="+data_est_physique_archive+
                    "&com_physique_archive="+data_com_physique_archive+
                    "&est_virtuelle_archive="+data_est_virtuelle_archive+
                    "&com_virtuelle_archive="+data_com_virtuelle_archive;



        if(link_edi) {// on récupère les données de l'édifice si un édifice est lié
            console.log("link_edi true, enregistrement avec édifice");
            let data_nom_edifice = $('#nom_edi').val();
            let data_type_edifice = $('#tedi-select').val();
            let data_commune_edifice = $('#commu_edi').val();
            let data_departement_edifice = $('#dep_edi').val();
            let data_nom_proprio_edifice = $('#prop_edi').val();
            let data_est_particulier_proprio_edifice = $('#est_part_prop').prop("checked");
            let data_est_commune_proprio_edifice = $('#est_commu_prop').prop("checked");
            /*let est_part = $('#est_part_prop').prop("checked");
            let data_est_particulier_proprio_edifice = (est_part)?1:0;
            let est_commu = $('#est_commu_prop').prop("checked");
            let data_est_commune_proprio_edifice = (est_commu)?1:0;*/

            post_parameter+=
                "&nom_edifice="+data_nom_edifice+
                "&type_edifice="+data_type_edifice+
                "&commune_edifice="+data_commune_edifice+
                "&dep_edifice="+data_departement_edifice+
                "&nom_proprio_edifice="+data_nom_proprio_edifice+
                "&est_part="+data_est_particulier_proprio_edifice+
                "&est_commu="+data_est_commune_proprio_edifice;

            console.log("post parameter= "+post_parameter);

            if(link_trav) { // on récupère les données des travaux si des travaux sont liés
                let data_tab_travaux = tab_travaux;
                document.getElementById('reset').click();
                post_parameter += "&trav_json="+JSON.stringify(data_tab_travaux);
            }
            else {
                console.log("aucun travaux ne seront enregistrés");
            }
        }
        else {
            console.log("aucun édifice ne sera enregistré");
        }

        console.log("enregistrement de l'archive en base de données... ");
        content_console.innerHTML = "<p>enregistrement de l'archive en base de données... </p>";

        requeteHTTP_insert_form.open("POST", url_base_insertions+"insert_archive.php", true);
        requeteHTTP_insert_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        requeteHTTP_insert_form.send(post_parameter);

        /*console.log("data_nom_archive= "+data_nom_archive);
        console.log("data_type_primaire_archive= "+data_type_primaire_archive);
        console.log("data_proprio_archive= "+data_proprio_archive);
        console.log("data_annee_archive= "+data_annee_archive);
        console.log("date_archivage_archive= "+date_archivage_archive);
        console.log("data_est_physique_archive= "+data_est_physique_archive);
        console.log("data_com_physique_archive= "+data_com_physique_archive);
        console.log("data_est_virtuelle_archive= "+data_est_virtuelle_archive);
        console.log("data_com_virtuelle_archive= "+data_com_virtuelle_archive);

        console.log("data_nom_edifice= "+data_nom_edifice);
        console.log("data_type_edifice= "+data_type_edifice);
        console.log("data_commune_edifice= "+data_commune_edifice);
        console.log("data_departement_edifice= "+data_departement_edifice);
        console.log("data_nom_proprio_edifice= "+data_nom_proprio_edifice);
        console.log("data_est_particulier_proprio_edifice= "+data_est_particulier_proprio_edifice);
        console.log("data_est_commune_proprio_edifice= "+data_est_commune_proprio_edifice);

        console.log("data_tab_travaux= "+JSON.stringify(data_tab_travaux));*/
}
function get_prop_form() {
    requeteHTTP_get_prop_form.open("POST", url_base_recuperations+"get_prop_form.php", true);
    requeteHTTP_get_prop_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_prop_form.send();
}
function get_type_prim_form() {
    requeteHTTP_get_type_prim_form.open("POST", url_base_recuperations+"get_type_prim_form.php", true);
    requeteHTTP_get_type_prim_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_type_prim_form.send();
}
function get_type_sec_form() {
    requeteHTTP_get_type_sec_form.open("POST", url_base_recuperations+"get_type_sec_form.php", true);
    requeteHTTP_get_type_sec_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_type_sec_form.send();
}
function get_type_ter_form() {
    requeteHTTP_get_type_ter_form.open("POST", url_base_recuperations+"get_type_ter_form.php", true);
    requeteHTTP_get_type_ter_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_type_ter_form.send();
}
function get_type_edi_form() {
    requeteHTTP_get_type_edi_form.open("POST", url_base_recuperations+"get_type_edi_form.php", true);
    requeteHTTP_get_type_edi_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_type_edi_form.send();
}
function get_list_ent_form(mselect_id) {
    console.log("id= "+mselect_id);
    requeteHTTP_get_list_ent_form.open("POST", url_base_recuperations+"get_list_ent_form.php", true);
    requeteHTTP_get_list_ent_form.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_get_list_ent_form.send("id="+mselect_id.toString());
}
function get_archive_searched() {
    console.log("get_archive_searched called with crit= "+crit_search);
    requeteHTTP_get_archive_searched.open("POST", url_base_recuperations+"get_archive_searched.php", true);
    requeteHTTP_get_archive_searched.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let parameter;
    switch(crit_search) {
        case "t_edi":
            parameter = "t_edi="+$("#r_tedi-select").val();
            break;
        case "c_edi":
            parameter = "c_edi="+$("#r_data_commu_edi").val();
            break;
        case "d_edi":
            parameter = "d_edi="+$("#r_data_dep_edi").val();
            break;
        case "p_edi":
            parameter = "p_edi="+$("#r_data_prop_edi").val();
            break;
        case "n_arch":
            parameter = "nom_arch="+$("#r_data_nom_arch").val();
            break;
        case "n_edi":
            parameter = "nom_edi="+$("#r_data_nom_edi").val();
            break;
        case "p_arch":
            parameter = "prop_arch="+$("#r_prop-select").val();
            break;
        case "t1_arch":
            parameter = "t1_arch="+$("#r_prim-select").val(); // id du type
            break;
        case "t2_arch":
            parameter = "t2_arch="+$("#r_sec-select").val();
            break;
        case "t3_arch":
            parameter = "t3_arch="+$("#r_m_select_type_ter").val(); // 1,2
            break;
        case "an":
            parameter = "an_arch="+$("#r_year_picker").val();
            break;
    }
    console.log("parameter search= "+parameter);
    requeteHTTP_get_archive_searched.send(parameter+"&crit_name="+crit_search);
}
function del_archive() {
    console.log("del_archive called with crit= "+crit_del);
    requeteHTTP_del_archive.open("POST", url_base_suppressions+"del_archive.php", true);
    requeteHTTP_del_archive.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let parameter;
    switch(crit_del) {
        case "num_arch":
            parameter = "num_arch="+$("#s_data_num_arch").val();
            break;
        case "n_arch":
            parameter = "nom_arch="+$("#s_data_nom_arch").val();
            break;
        case "n_edi":
            parameter = "nom_edi="+$("#s_data_nom_edi").val();
            break;
        case "p_arch":
            parameter = "prop_arch="+$("#s_prop-select").val();
            break;
        case "t1_arch":
            parameter = "t1_arch="+$("#s_prim-select").val(); // id du type
            break;
        case "t2_arch":
            parameter = "t2_arch="+$("#s_sec-select").val();
            break;
        case "an":
            parameter = "an_arch="+$("#s_year_picker").val();
            break;
    }
    console.log("parameter del= "+parameter);
    requeteHTTP_del_archive.send(parameter+"&crit_name="+crit_del);
}
function query_1(parameter_request) {
    console.log("query_1 called with crit= "+parameter_request);
    requeteHTTP_query_1.open("POST", url_base_recuperations+"query_1.php", true);
    requeteHTTP_query_1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    requeteHTTP_query_1.send(parameter_request);
    console.log("query_1 called");
}
function handler_insert_form() {
    console.log("handler_insert_form called");
    console.log("responseText= "+requeteHTTP_insert_form.responseText);
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_insert_form.readyState === 4) && (requeteHTTP_insert_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_insert_form.responseText);
        console.log("docjson= "+JSON.stringify(docJSON));
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                console.log("L'\archive a bien été enregistrée en BDD");
                alert("L'\archive a bien été enregistrée en BDD");
                content_console.innerHTML += "<p>"+"L'\archive a bien été enregistrée en BDD"+"</p>";
            }
            else {
                console.log('L\'archive n\'a pas été insérée en BDD');
                if(docJSON['message'] !== "" || docJSON['message'] !== undefined || docJSON['message'] !== null) {
                    console.log("message= "+docJSON['message']);
                    content_console.innerHTML += '<p>'+docJSON['message']+'</p>';
                    alert(docJSON['message']);
                }
                content_console.innerHTML += '</p>';

            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerHTML += '<p>Problème de résultats de requête AJAX, insertion de l\'archive échouée !\n'+e+'</p>';
        }
    }
    else {
        content_console.innerHTML += '<p>Problème de requête AJAX, insertion de l\'archive échouée</p>';
    }
}
function handler_get_prop_form() {
    console.log("handler_get_prop_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_prop_form.readyState === 4) && (requeteHTTP_get_prop_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_prop_form.responseText);
        console.log("docjson= "+JSON.stringify(docJSON));
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list= '<option value="">--votre choix--</option>';
                for(var i = 0; i < docJSON['list'].length; i++) {
                    //console.log(JSON.stringify(docJSON['list'][i])+"\n\n");
                    list+="<option value="+docJSON['list'][i]['ID_PROPRIETAIRE']+">"+docJSON['list'][i]['NOM_PROPRIETAIRE']+"</option>";
                }

                let prop_select = document.getElementById('prop-select');
                let r_prop_select = document.getElementById('r_prop-select');
                let s_prop_select = document.getElementById('s_prop-select');
                let i_prop_select = document.getElementById('i_prop-select');

                if(prop_select != null && prop_select !== undefined) {
                    prop_select.innerHTML = list;
                }
                if(r_prop_select != null && r_prop_select !== undefined) {
                    r_prop_select.innerHTML = list;
                }
                if(s_prop_select != null && s_prop_select !== undefined) {
                    s_prop_select.innerHTML = list;
                }
                if(i_prop_select != null && i_prop_select !== undefined) {
                    i_prop_select.innerHTML = list;
                }
            }
            else {
                console.log('Aucun propriétaire enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des propriétaires échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des propriétaires échoués';
    }
}
function handler_get_type_prim_form() {
    console.log("handler_get_type_prim_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_type_prim_form.readyState === 4) && (requeteHTTP_get_type_prim_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_type_prim_form.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON))
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list= '<option value="">--votre choix--</option>';
                for(var i = 0; i < docJSON['list'].length; i++) {
                    //console.log(JSON.stringify(docJSON['list'][i])+"\n\n");
                    list+="<option value="+docJSON['list'][i]['ID_TYPE_PRIMAIRE_ARCHIVE']+">"+docJSON['list'][i]['NOM_TYPE_PRIMAIRE_ARCHIVE']+"</option>";
                }
                let prim_select = document.getElementById('prim-select');
                let r_prim_select = document.getElementById('r_prim-select');
                let s_prim_select = document.getElementById('s_prim-select');

                if(prim_select != null && prim_select !== undefined) {
                    prim_select.innerHTML = list;
                }
                if(r_prim_select != null && r_prim_select !== undefined) {
                    r_prim_select.innerHTML = list;
                }
                if(s_prim_select != null && s_prim_select !== undefined) {
                    s_prim_select.innerHTML = list;
                }
            }
            else {
                console.log('Aucun type primaire enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des types primaires échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des types primaires échoués';
    }
}
function handler_get_type_sec_form() {
    console.log("handler_get_type_sec_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_type_sec_form.readyState === 4) && (requeteHTTP_get_type_sec_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_type_sec_form.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON))
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list= '<option value="">--votre choix--</option>';
                for(var i = 0; i < docJSON['list'].length; i++) {
                    //console.log(JSON.stringify(docJSON['list'][i])+"\n\n");
                    list+="<option value="+docJSON['list'][i]['ID_TYPE_SECONDAIRE_ARCHIVE']+">"+docJSON['list'][i]['NOM_TYPE_SECONDAIRE_ARCHIVE']+"</option>";
                }
                let sec_select = document.getElementById('sec-select');
                let r_sec_select = document.getElementById('r_sec-select');
                let s_sec_select = document.getElementById('s_sec-select');
                if(sec_select != null && sec_select !== undefined) {
                    sec_select.innerHTML = list;
                }
                if(r_sec_select != null && r_sec_select !== undefined) {
                    r_sec_select.innerHTML = list;
                }
                if(s_sec_select != null && s_sec_select !== undefined) {
                    s_sec_select.innerHTML = list;
                }
            }
            else {
                console.log('Aucun type secondaire enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des types secondaires échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des types secondaires échoués';
    }
}
function handler_get_type_ter_form() {
    console.log("handler_get_type_ter_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_type_ter_form.readyState === 4) && (requeteHTTP_get_type_ter_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_type_ter_form.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON))
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var options = [];
                for(var i = 0; i < docJSON['list'].length; i++) {
                    //console.log(JSON.stringify(docJSON['list'][i])+"\n\n");
                    //list+="<option value="+docJSON['list'][i]['ID_TYPE_TERTIAIRE_ARCHIVE']+">"+docJSON['list'][i]['NOM_TYPE_TERTIAIRE_ARCHIVE']+"</option>";
                    options.push({label: docJSON['list'][i]['NOM_TYPE_TERTIAIRE_ARCHIVE'], title: docJSON['list'][i]['NOM_TYPE_TERTIAIRE_ARCHIVE'], value:docJSON['list'][i]['ID_TYPE_TERTIAIRE_ARCHIVE']})
                }

                let id_multiple_select = $("#m_select_type_ter");
                id_multiple_select.multiselect();
                id_multiple_select.multiselect('dataprovider', options);
                id_multiple_select.multiselect('rebuild');

                let id_multiple_select_r = $("#r_m_select_type_ter");
                id_multiple_select_r.multiselect();
                id_multiple_select_r.multiselect('dataprovider', options);
                id_multiple_select_r.multiselect('rebuild');
            }
            else {
                console.log('Aucun type tertiaire enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des types tertiaires échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des types tertiaires échoués';
    }
}
function handler_get_type_edi_form() {
    console.log("handler_get_type_edi_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_type_edi_form.readyState === 4) && (requeteHTTP_get_type_edi_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_type_edi_form.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON))
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true) {
                var list= '<option value="">--votre choix--</option>';
                for(var i = 0; i < docJSON['list'].length; i++) {
                    //console.log(JSON.stringify(docJSON['list'][i])+"\n\n");
                    list+="<option value="+docJSON['list'][i]['ID_TYPE_EDIFICE']+">"+docJSON['list'][i]['NOM_TYPE_EDIFICE']+"</option>";
                }
                let tedi_select = document.getElementById('tedi-select');
                let s_tedi_select = document.getElementById('s_tedi-select');
                let r_tedi_select = document.getElementById('r_tedi-select');
                let i_tedi_select = document.getElementById('i_tedi-select');
                if(tedi_select != null && tedi_select !== undefined) {
                    tedi_select.innerHTML = list;
                }
                if(s_tedi_select != null && s_tedi_select !== undefined) {
                    s_tedi_select.innerHTML = list;
                }
                if(r_tedi_select != null && r_tedi_select !== undefined) {
                    r_tedi_select.innerHTML = list;
                }
                if(i_tedi_select != null && i_tedi_select !== undefined) {
                    i_tedi_select.innerHTML = list;
                }
            }
            else {
                console.log('Aucun type d\'édifice enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des types d\'édifice échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des types d\'édifice échoués';
    }
}
function handler_get_list_ent_form() {
    console.log("handler_get_list_ent_form called");
    let content_console = document.getElementById('content_console');
    if((requeteHTTP_get_list_ent_form.readyState === 4) && (requeteHTTP_get_list_ent_form.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_list_ent_form.responseText);
        console.log("docjson= "+JSON.stringify(docJSON));
        try {
            //console.log("docjson= "+docJSON['result']);
            if (docJSON['result'] === true && docJSON['id'] != null && docJSON['id'] !== undefined && docJSON['id'] !== "") {
                var options = [];
                for(var i = 0; i < docJSON['list'].length; i++) {
                    console.log("entreprise de nom= "+docJSON['list'][i]['NOM_ENTREPRISE']+" et d'ID= "+docJSON['list'][i]['ID_ENTREPRISE']);
                    options.push({label: docJSON['list'][i]['NOM_ENTREPRISE'], title: docJSON['list'][i]['NOM_ENTREPRISE'], value:docJSON['list'][i]['ID_ENTREPRISE']})
                }

                let id_multiple_select = $("#"+docJSON['id']);
                id_multiple_select.multiselect();
                id_multiple_select.multiselect('dataprovider', options);
                id_multiple_select.multiselect('rebuild');
            }
            else {
                console.log('Aucune entreprise enregistré en base de donnée');
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, mise à jour des entreprise échoués !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, mise à jour des entreprise échoués';
    }
}
function handler_get_archive_searched() {
    console.log("handler_get_archive_searched called");
    var content_console = document.getElementById('content_console');
    var content_arch_result = document.getElementById('tbody_arch_result');
    var content_trav_result = document.getElementById('tbody_trav_result');
    var archive_result = "";
    var travaux_result = "";
    console.log("requesttat= "+requeteHTTP_get_archive_searched.responseText);
    if((requeteHTTP_get_archive_searched.readyState === 4) && (requeteHTTP_get_archive_searched.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_get_archive_searched.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON));
        try {
            if (docJSON['result'] === true) {
                content_console.innerText ='Les résultats de recherches ont été affichées';
                //console.log("archive[0]= "+docJSON['archive'][0]['ID_ARCHIVE']);
                console.log("docJSON['archive']= "+docJSON['archive']['ID_ARCHIVE']);

                for (var key0 in docJSON['archive']) { // key = index du tableau d'archive
                    if (docJSON['archive'].hasOwnProperty(key0)) {

                        if(docJSON['archive'][key0]['is_edi'] === undefined) { // cas 1.1 archives sans édifice
                            //console.log(key0 + " -> " + docJSON['archive'][key0]);
                            archive_result += '<tr class="line_arch"><td>' + docJSON['archive'][key0]['ID_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_PROPRIETAIRE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_PRIMAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_SECONDAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_TERTIAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['ANNEE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['DATE_ARCHIVAGE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_VIRTUELLE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_VIRTUELLE_ARCHIVE'] + '</td></tr>';
                            content_arch_result.innerHTML = archive_result;
                        }
                        else if (docJSON['archive'][key0]['is_edi'] === true && docJSON['archive'][key0]['is_travaux'] === undefined) { // cas 1.2 archive avec edifice sans travaux
                            archive_result += '<tr class="line_arch"><td>' + docJSON['archive'][key0]['ID_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_PROPRIETAIRE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_PRIMAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_SECONDAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_TERTIAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['ANNEE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['DATE_ARCHIVAGE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_VIRTUELLE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_VIRTUELLE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_TYPE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_PROPRIETAIRE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + "nada" + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['COMMUNE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['DEPARTEMENT_EDIFICE'] + '</td></tr>';
                        }
                        else if (docJSON['archive'][key0]['is_edi'] === true && docJSON['archive'][key0]['is_travaux'] === true) { // cas 1.3 archive avec edifice et travaux
                            archive_result += '<tr class="line_arch"><td>' + docJSON['archive'][key0]['ID_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_PROPRIETAIRE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_PRIMAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_SECONDAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['TYPE_TERTIAIRE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['ANNEE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['DATE_ARCHIVAGE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['EST_VIRTUELLE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_PHYSIQUE_ARCHIVE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['REFERENCE_VIRTUELLE_ARCHIVE'] + '</td>';

                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_TYPE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['NOM_PROPRIETAIRE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + "nada" + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['COMMUNE_EDIFICE'] + '</td>';
                            archive_result += '<td>' + docJSON['archive'][key0]['DEPARTEMENT_EDIFICE'] + '</td></tr>';


                            if (docJSON['archive'][key0]['is_travaux'] === true) {
                                for (var key1 in docJSON['archive'][key0]['travaux']) {
                                    if (docJSON['archive'][key0]['travaux'].hasOwnProperty(key1)) {
                                        travaux_result += '<tr class="line_trav"><td>' + docJSON['archive'][key0]['ID_ARCHIVE'] + '</td>';
                                        travaux_result += '<td>' + key1 + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['MONTANT_MARCHE_TRAVAUX'] + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['HONORAIRE_TRAVAUX'] + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['DATE_DEBUT_TRAVAUX'] + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['DATE_FIN_TRAVAUX'] + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['DUREE_TRAVAUX'] + '</td>';
                                        travaux_result += '<td>' + docJSON['archive'][key0]['travaux'][key1]['LIST_ENTREPRISE'] + '</td></tr>';
                                    }
                                }
                            }
                        }
                        else {
                            console.log("les archives de la requête ne correspondent à aucun des cas");
                        }
                    }
                }
                content_arch_result.innerHTML = archive_result;
                content_trav_result.innerHTML = (travaux_result === "")?'<p class="msg_in_tab">Aucun travaux n\'est liés aux archives trouvées</p>':travaux_result;


                // on définit les clicks sur les éléments du tableau pour les sélectionner
                $(".line_arch").click(function(e){
                    console.log("line_arch clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });
                $(".line_edi").click(function(e){
                    console.log("line_edi clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });
                $(".line_trav").click(function(e){
                    console.log("line_trav clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });

            }
            else {
                content_arch_result.innerHTML = '<p class="msg_in_tab">Aucune données correspondante à votre requête</p>';
                content_trav_result.innerHTML = '<p class="msg_in_tab">Aucune données correspondante à votre requête</p>';
                console.log('Aucune archive correspondante en base de donnée<br/>'+docJSON['message']);
                content_console.innerHTML ='Aucune archive correspondante en base de donnée<br/>'+docJSON['message'];
                alert(docJSON['message']);
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, aucune correspondance trouvée !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, le serveur ne retourne pas un résultat correcte';
    }
}
function handler_del_archive() {
    console.log("handler_del_archive called");
    let content_console = document.getElementById('content_console');
    console.log("responsetext= "+requeteHTTP_del_archive.responseText);
    if((requeteHTTP_del_archive.readyState === 4) && (requeteHTTP_del_archive.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_del_archive.responseText);
        console.log("docjson= "+JSON.stringify(docJSON));
        try {
            if (docJSON['result'] === true) {
                content_console.innerText = docJSON['message'];
                alert(docJSON['message']);
            }
            else {
                content_console.innerText = docJSON['message'];
                alert(docJSON['message']);
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, suppression échouées !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, suppression échoués';
    }
}
function handler_query_1() {
    console.log("handler_query_1 called");
    var content_console = document.getElementById('content_console');
    var content_result = document.getElementById('tbody_info_result');
    var infos_result = "";
    console.log("requesttat= "+requeteHTTP_query_1.responseText);
    if((requeteHTTP_query_1.readyState === 4) && (requeteHTTP_query_1.status === 200)) {
        let docJSON = JSON.parse(requeteHTTP_query_1.responseText);
        //console.log("docjson= "+JSON.stringify(docJSON));
        try {
            if (docJSON['result'] === true) {
                content_console.innerText ='Les résultats de la requêtes ont été affichées';
                content_console.innerText = docJSON['message'];
                console.log("docJSON['content']= "+docJSON['content']);

                for (var key0 in docJSON['content']) { // key = index du tableau d'archive
                    if (docJSON['content'].hasOwnProperty(key0)) {
                        infos_result += '<tr class="line_infos"><td>' + docJSON['content'][key0]['id_arch'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['prop_arch'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['id_edi'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['prop_edi'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['nom_edi'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['type_edi'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['dep'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['commu'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['com_phys'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['com_virt'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['dd'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['df'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['mm'] + '</td>';
                        infos_result += '<td>' + docJSON['content'][key0]['hono'] + '</td></tr>';
                        //content_result.innerHTML = archive_result;
                    }
                }
                content_result.innerHTML = infos_result;

                alert("Des résultats pour votre requête ont été trouvés, \ncliquez sur \"RÉSULTATS\" ci dessous pour les visualiser");

                /*!// on définit les clicks sur les éléments du tableau pour les sélectionner
                $(".line_arch").click(function(e){
                    console.log("line_arch clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });
                $(".line_edi").click(function(e){
                    console.log("line_edi clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });
                $(".line_trav").click(function(e){
                    console.log("line_trav clicked");
                    if($(this).hasClass('active-row')) { // si class présente on supprime la class
                        $(this).removeClass('active-row');
                    }
                    else { // sinon on l'ajoute
                        $(this).addClass('active-row');
                    }
                });*/
            }
            else {
                content_result.innerHTML = '<p class="msg_in_tab">Aucune données correspondante à votre requête</p>';
                console.log('Aucune donnée correspondante en base de donnée<br/>'+docJSON['message']);
                content_console.innerHTML ='Aucune donnée correspondante à la requête 1 n\'a été trouvé en base de donnée<br/>'+docJSON['message'];
                alert(docJSON['message']);
            }
        }
        catch(e) {
            console.log("Problème de résultat de requête "+e);
            content_console.innerText = 'Problème de résultats de requête AJAX, aucune correspondance trouvée !\n'+e;
        }
    }
    else {
        content_console.innerText = 'Problème de requête AJAX, le serveur ne retourne pas un résultat correcte';
    }
}

