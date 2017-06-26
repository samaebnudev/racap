/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function ( ) {
    
    $('.noClick').click(function(){
    return false;
    });

    $('#idRacap').change(function (){
        var sequencial = $('#cadFechaRacap').serialize();
        
        $.ajax({
            url: 'busca_prazo_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $("#racapPrazoSim").prop("checked", true);

                } else {
                    $("#racapPrazoNao").prop("checked", true);
                }
            }
        });

        return false;
    });
});