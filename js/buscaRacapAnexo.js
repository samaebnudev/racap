/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {

    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_racap_anexo.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                   // $("#formAcaoFieldset").children().attr('disabled', '');
                    $("#listaAnexos").empty();
                    $("#listaAnexos").append(data.tableData);

                } else {
                    //$("#formAcaoFieldset").children().attr('disabled', 'true');
                    $("#listaAnexos").empty();
                }
            }
        });

        return false;

    });

    $("#thing").click(function (e) {
        e.preventDefault();
    });
    
    $("#thing2").click(function (e) {
        e.preventDefault();
    });

});


