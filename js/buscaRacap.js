$(document).ready(function ( ) {
    $("#racapAcaoRacap :input").prop("disabled", true);
    $('#cadFechaRacap :input').prop("disabled",true);
    $("#tabelaAnexos :input").prop("disabled", true);
    $('#autorRacap').html("");
    $('#autorRacap').html($('#autorRacapHidden').val());
    
    $('#cadRACAP').submit(function () {
        $('#statusRacap').prop('disabled', false);
    });

    /*$('#cadRACAP').change(function () {
     var checkDisable = $('#statusRacap').prop('disabled');
     console.log(checkDisable);
     if (!checkDisable){
     $('#statusRacap').prop('disabled', true);
     }
     });*/
    
    if ($('#privilegioRACAP').val()=='3'){
      $("#cadRACAP :input").prop("disabled", true);
    }

    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencial').val(data.id);
                    $('#statusRacap').val(data.statusRacap);
                    $('#autorRacap').html("");
                    $('#autorRacap').html("Autor: "+data.autorRacap);

                    if (data.statusRacap > '3') {
                        $("#cadRACAP :input").prop("disabled", true);
                        $("#racapAcaoRacap :input").prop("disabled", true);
                        $("#tabelaAnexos :input").prop("disabled", true);
                        $('#cadFechaRacap :input').prop("disabled",true);
                        $('#sequencialAcao').val("");

                        $('#racapAcaoRacap').each(function () {
                            this.reset();
                        });

                    } else {
                        
                        if ($('#privilegioRACAP').val()!="3"){
                            $("#cadRACAP :input").prop("disabled", false);
                            $('#statusRacap').prop('disabled', true);
                            $('#cadFechaRacap :input').prop("disabled",false);
                        }else{
                            $("#cadRACAP :input").prop("disabled", true);
                            $('#cadFechaRacap :input').prop("disabled",true);
                        }
                        
                        $("#racapAcaoRacap :input").prop("disabled", false);
                        $("#tabelaAnexos :input").prop("disabled", false);
                        $('#sequencialAcao').val("");

                        $('#racapAcaoRacap').each(function () {
                            this.reset();
                        });
                    }
                    
                    $('#idRacap').val(data.id);
                    //Coloca o sequencial da RACAP no Form de anexos.
                    $('#numRACAPFormAnexo').val(data.id);
                    //Coloca o sequencial da RACAP no Form de Fechamento.
                    $('#numRACAP').val(data.id);
                    $('#tipoRacap').val(data.tipoRacap);
                    $('#motivoAbertura').val(data.motivoAbertura);
                    $('#motivoDescricao').val(data.motivoDescricao);
                    $('#setorRacap').val(data.setorRacap);
                    $('#causaRacap').val(data.causaRacap);

                    if (data.prazoRacap) {
                        var dateBuffer = data.prazoRacap.replace(" ", "T");
                        $('#prazoRacap').val(dateBuffer);
                        //$('#prazo_acao').val(dateBuffer);
                    }

                    if (data.dataAbertura) {
                        dateBuffer = data.dataAbertura.replace(" ", "T");
                        $('#dataAbertura').val(dateBuffer);
                        $('#dataAcao').prop('min', dateBuffer);
                    }

                    $('#historicoRACAP').val(data.historicoRACAP);


                } else {
                    
                    $('#autorRacap').html("");
                    $('#autorRacap').html($('#autorRacapHidden').val());
                    
                    $('#cadRACAP').each(function () {
                        this.reset();
                    });

                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });

                    $("#tabelaAnexos").each(function () {
                        this.reset();
                    });

                    if ($('#privilegioRACAP').val()=="3"){
                        $("#cadRACAP :input").prop("disabled", true);
                    }else{
                        $("#cadRACAP :input").prop("disabled", false);
                    }
                    
                    $("#tabelaAnexos :input").prop("disabled", true);
                    $("#racapAcaoRacap :input").prop("disabled", true);
                    $("#cadFechaRacap :input").prop("disabled", true);
                    $('#dataAcao').prop('min', "");
                    $('#statusRacap').prop('disabled', true);
                    $('#sequencialAcao').val("");
                    $('#idRacap').val("");

                }
            }
        });

        return false;

    });

});