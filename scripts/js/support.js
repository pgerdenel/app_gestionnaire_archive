/* Fonction permetttant de renvoyer la date courante */
function getDate()
{
    let today = new Date();
    let today_format;
    let dd = today.getDate();
    let mm = today.getMonth()+1; //January is 0!
    let yyyy = today.getFullYear();
    if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm}
    today = yyyy+""+mm+""+dd;
    today_format = yyyy+"-"+mm+"-"+dd;
    document.getElementById("todayDate").value = today;

    return today_format;
}

function print() {
    console.log("print standard called");
    const filename  = 'ThisIsYourPDFFilename.pdf';

    html2canvas(document.querySelector('#content_all_tab_result')).then(canvas => {
        let pdf = new jsPDF('p', 'mm', 'a4');
        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 298);
        pdf.save(filename);
    });
}

// Variant
// This one lets you improve the PDF sharpness by scaling up the HTML node tree to render as an image before getting pasted on the PDF.
function print(quality = 1) {
    console.log("print quality called");
    const filename  = 'ThisIsYourPDFFilename.pdf';

    html2canvas(document.querySelector('#content_all_tab_result'),
        {scale: quality}
    ).then(canvas => {
        let pdf = new jsPDF('p', 'mm', 'a4');
        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 298);
        pdf.save(filename);
    });
}
/* pour le tableau de recherche archive trie les résultats selon critère */
function search_table_r() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search_a");
    filter = input.value.toUpperCase();
    table = document.getElementById("table_r");
    tr = table.getElementsByTagName("tr");

    let count_line = tr.length-1;
    console.log("nombre de ligne = "+count_line);
    let count_all_element = getBody($("#table_r"));
    let count_all_element_by_line = count_all_element/count_line;
    console.log("nb elem par ligne = "+count_all_element_by_line);

    // Loop through all table rows, and hide those who don't match the search query
    let line;
    for (i = 1; i < tr.length; i++) {
        line = tr[i].getElementsByTagName("td");
        for(let j=0;j<count_all_element_by_line-1;j++) {
            td = line[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                //console.log("elem value= "+txtValue);
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    console.log("valeur égale on laisse afficher");
                    tr[i].style.display = "";
                    break;
                } else {
                    console.log("valeur non égale on cache");
                    tr[i].style.display = "none";
                }
            }
        }
    }
}
/* pour le tableau de recherche travaux trie les résultats selon critère */
function search_table_rt() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search_at");
    filter = input.value.toUpperCase();
    table = document.getElementById("table_r_t");
    tr = table.getElementsByTagName("tr");

    let count_line = tr.length-1;
    console.log("nombre de ligne = "+count_line);
    let count_all_element = getBody($("#table_r_t"));
    let count_all_element_by_line = count_all_element/count_line;
    console.log("nb elem par ligne = "+count_all_element_by_line);

    // Loop through all table rows, and hide those who don't match the search query
    let line;
    for (i = 1; i < tr.length; i++) {
        line = tr[i].getElementsByTagName("td");
        for(let j=0;j<count_all_element_by_line-1;j++) {
            td = line[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                //console.log("elem value= "+txtValue);
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    console.log("valeur égale on laisse afficher");
                    tr[i].style.display = "";
                    break;
                } else {
                    console.log("valeur non égale on cache");
                    tr[i].style.display = "none";
                }
            }
        }
    }
}
/* pour le tableau de résultats requête 1 trie les résultats selon critère */
function search_table_i() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search_i");
    filter = input.value.toUpperCase();
    table = document.getElementById("table_i");
    tr = table.getElementsByTagName("tr");

    let count_line = tr.length-1;
    //console.log("nombre de ligne = "+count_line);
    let count_all_element = getBody($("#table_i"));
    let count_all_element_by_line = count_all_element/count_line;
    //console.log("nb elem par ligne = "+count_all_element_by_line);

    // Loop through all table rows, and hide those who don't match the search query
    let line;
    for (i = 1; i < tr.length; i++) {
        line = tr[i].getElementsByTagName("td");
        for(let j=0;j<count_all_element_by_line-1;j++) {
            td = line[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                //console.log("elem value= "+txtValue);
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    //console.log("valeur égale on laisse afficher");
                    tr[i].style.display = "";
                    break;
                } else {
                    //console.log("valeur non égale on cache");
                    tr[i].style.display = "none";
                }
            }
        }
    }
}

/* compte le nombre d'élément toute ligne(tr) et toutes élements par ligne (th) */
function getBody(element) {
    var divider = 2;
    var originalTable = element.clone();
    var tds = $(originalTable).children('tbody').children('tr').children('td').length;
    //alert(tds);
    return tds;
}