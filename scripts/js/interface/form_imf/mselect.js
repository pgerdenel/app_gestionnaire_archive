$(function() {

    $('#test').multiselect({

        includeSelectAllOption: true

    });

    $('#btnget').click(function() {

        alert($('#test').val());

    })

});