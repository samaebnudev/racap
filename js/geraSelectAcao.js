/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {
    
    $('#buscaBanco').on('change', function () {
        var sequencial = $('#selectbuscaBanco');
        
        $.ajax({
            url: 'gera_select_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                   // $("#formAcaoFieldset").children().attr('disabled', '');
                    $("#selectAcaoRacap").empty();
                    $("#selectAcaoRacap").append(data.tableData);
                    

                } else {
                    //$("#formAcaoFieldset").children().attr('disabled', 'true');
                    $("#selectAcaoRacap").empty();
                }
            }
        });

        return false;
    });
    
});


