/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function ( ) {

    $('.noClick').click(function () {
        return false;
    });

    $('#idRacap').change(function () {
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

    $('#buscaBanco').change(function () {
        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_fecha_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencial').val(data.id);
                    $('#idRacap').val(data.idRacap);
                    /*TODO: Colocar Aqui controle para o checkbox do Prazo*/

                    if (data.prazoRacap == "S") {
                        $("#racapPrazoSim").prop("checked", true);
                        $("#racapPrazoNao").prop("checked", false);
                    }
                    else if (data.prazoRacap == "N") {
                        $("#racapPrazoNao").prop("checked", true);
                        $("#racapPrazoSim").prop("checked", false);
                    }

                    $('#dataFechamento').val(data.dataFechamento);
                    
                    /*TODO: Colocar Aqui controle para o checkbox da Eficiência*/
                    
                    if (data.eficaciaRacap == "S") {
                        $("#racapEficienciaSim").prop("checked", true);
                        $("#racapEficienciaNao").prop("checked", false);
                    }
                    else if (data.eficaciaRacap == "N") {
                        $("#racapEficienciaNao").prop("checked", true);
                        $("#racapEficienciaSim").prop("checked", false);
                    }
                    
                    $('#observacaoRACAP').val(data.observacaoRACAP);
                } else {
                    $('#cadFechaRacap').each(function(){
                        this.reset();
                    });
                }
            }
        });

        return false;
    });

});