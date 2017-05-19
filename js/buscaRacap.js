$(document).ready(function ( ) {

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
                    
                    if (data.prazoRacap){
                        var dateBuffer = data.prazoRacap.replace (" ","T");
                        $('#prazoRacap').val(dateBuffer);
                    }
                    
                    $('#historicoRACAP').val(data.historicoRACAP);
                    

                } else {
                    $('#cadRACAP').each(function (){
                        this.reset();
                    });
                }
            }
        });

        return false;

    });

});