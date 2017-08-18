$(document).ready(function ( ) {
    
    $('#selectAcaoRacap').prop("disabled", true);
    $("#racapAcaoRacap :input").prop("disabled", true);
    
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

                    if (data.statusRacap != '1') {
                        $("#cadRACAP :input").prop("disabled", true);
                        $('#selectAcaoRacap').prop("disabled", false);
                        $("#racapAcaoRacap :input").prop("disabled", true);
                    } else {
                        $('#selectAcaoRacap').prop("disabled", false);
                        $("#cadRACAP :input").prop("disabled", false);
                        $("#racapAcaoRacap :input").prop("disabled", false);
                        $('#statusRacap').prop('disabled', true);
                    }
                    
                    $('#idRacap').val(data.id);
                    $('#tipoRacap').val(data.tipoRacap);
                    $('#motivoAbertura').val(data.motivoAbertura);
                    $('#motivoDescricao').val(data.motivoDescricao);
                    $('#setorRacap').val(data.setorRacap);
                    $('#causaRacap').val(data.causaRacap);

                    if (data.prazoRacap) {
                        var dateBuffer = data.prazoRacap.replace(" ", "T");
                        $('#prazoRacap').val(dateBuffer);
                        $('#prazo_acao').val(dateBuffer);
                    }

                    $('#historicoRACAP').val(data.historicoRACAP);


                } else {
                    $('#cadRACAP').each(function () {
                        this.reset();
                    });
                    
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                    
                    $("#cadRACAP :input").prop("disabled", false);
                    $('#selectAcaoRacap').prop("disabled", true);
                    $("#racapAcaoRacap :input").prop("disabled", true);
                    $('#statusRacap').prop('disabled', true);

                }
            }
        });

        return false;

    });

});