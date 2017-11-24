/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function ( ) {

    /*$('.noClick').click(function () {
        return false;
    });

    $('#cadFechaRacap').submit(function () {
        $('#idRacap').prop('disabled', false);
    });
    
    $('#limpaForm').click(function () {
        $('#idRacap').prop('disabled', false);
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
    });*/

    $('#buscaBanco').change(function () {
        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_fecha_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#idFechamento').val(data.id);
                    $('#numRACAP').val(data.idRacap);
                    
                    if (data.dataFechamento) {
                        dateBuffer = data.dataFechamento.replace(" ", "T");
                        $('#dataFechamento').val(dateBuffer);
                    }

                    //$('#dataFechamento').val(data.dataFechamento);

                    $('#observacaoRACAP').val(data.observacaoRACAP);
                    $('#statusRacapPos').val(data.statusPos);
                    
                } else {
                    $('#cadFechaRacap').each(function () {
                        this.reset();
                    });
                    
                    //$('#numRACAP').val("");
                    $('#idFechamento').val("");
                }
            }
        });

        return false;
    });

});