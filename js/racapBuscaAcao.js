/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {
    
    $('#buscaAcaoRacap').on('change', function () {
        var sequencial = $('#selectAcaoRacap');
        
        $.ajax({
            url: 'racap_busca_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencialAcao').val(data.id);
                    $('#idRacap').val(data.id_racap);
                    $('#selectStatusAcao').val(data.status_acao);
                    $('#selectResponsavel').val(data.responsavel_acao);
                    $('#observacaoAcao').val(data.observacao_acao);
                } else {
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                }
            }
        });

        return false;
    });
    
});