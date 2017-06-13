$(document).ready(function ( ) {

    $('#selectAcaoRacap').prop("disabled", true);
    $('#sequencialAcao').prop("disabled", true);
    $('#idRacap').prop("disabled", true);
    $('#selectStatusAcao').prop("disabled", true);
    $('#selectResponsavel').prop("disabled", true);
    $('#observacaoAcao').prop("disabled", true);
    $('#racapAcaoSubmit').prop("disabled", true);

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
                    $('#tipoRacap').val(data.tipoRacap);
                    $('#motivoAbertura').val(data.motivoAbertura);
                    $('#motivoDescricao').val(data.motivoDescricao);
                    $('#setorRacap').val(data.setorRacap);
                    $('#causaRacap').val(data.causaRacap);

                    if (data.prazoRacap) {
                        var dateBuffer = data.prazoRacap.replace(" ", "T");
                        $('#prazoRacap').val(dateBuffer);
                    }

                    $('#historicoRACAP').val(data.historicoRACAP);

                    $('#selectAcaoRacap').prop("disabled", false);
                    $('#sequencialAcao').prop("disabled", false);
                    $('#idRacap').prop("disabled", false);
                    $('#idRacap').val(sequencial.val());
                    $('#selectStatusAcao').prop("disabled", false);
                    $('#selectResponsavel').prop("disabled", false);
                    $('#observacaoAcao').prop("disabled", false);
                    $('#racapAcaoSubmit').prop("disabled", false);


                } else {
                    $('#cadRACAP').each(function () {
                        this.reset();
                    });

                    $('#selectAcaoRacap').prop("disabled", true);
                    $('#sequencialAcao').prop("disabled", true);
                    $('#idRacap').prop("disabled", true);
                    $('#idRacap').val("");
                    $('#selectStatusAcao').prop("disabled", true);
                    $('#selectResponsavel').prop("disabled", true);
                    $('#observacaoAcao').prop("disabled", true);
                    $('#racapAcaoSubmit').prop("disabled", true);

                }
            }
        });

        return false;

    });

});