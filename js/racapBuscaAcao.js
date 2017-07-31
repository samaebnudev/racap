/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {

    $('#buscaAcaoRacap').on('change', function () {
        var sequencial = $('#selectAcaoRacap');
        console.log ("Chamou Busca Ação");
        $.ajax({
            url: 'racap_busca_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    console.log ("Sucesso");
                    $('#sequencialAcao').val(data.id);
                    $('#idRacap').val(data.id_racap);
                    $('#selectStatusAcao').val(data.status_acao);
                    $('#selectResponsavel').val(data.responsavel_acao);
                    $('#descricaoAcao').val(data.descricao_acao);
                    
                    var dateBuffer = data.prazo.replace(" ", "T");
                    $('#prazo_acao').val(dateBuffer);
                    
                } else {
                    console.log ("Falha");
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                }
            }
        });

        return false;
    });

    $('#idRacap').on('change', function () {
        var sequencial = $('#idRacap');
        
        $.ajax({
            url: 'busca_prazo_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    var dateBuffer = data.prazo.replace(" ", "T");
                    $('#prazo_acao').val(dateBuffer);
                } else {
                    $('#prazo_acao').val("");
                }
            }
        });

        return false;


    });

});