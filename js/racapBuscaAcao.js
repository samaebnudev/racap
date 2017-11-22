/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {

    $('.noClick').click(function () {
        return false;
    });

    $('#buscaAcaoRacap').on('change', function () {
        var sequencial = $('#selectAcaoRacap');
        console.log("Chamou Busca Ação");
        console.log(sequencial.val());
        $.ajax({
            url: 'racap_busca_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    console.log("Sucesso");
                    
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                    
                    $('#sequencialAcao').val(data.id);
                    $('#idRacap').val(data.idRacap);
                    $('#numeroAcao').val(data.numeroAcao);
                    $('#tituloAcao').val(data.tituloAcao);
                    $('#selectStatusAcao').val(data.selectStatusAcao);

                    if (data.dataAcao != null) {
                        var dateBuffer = data.dataAcao.replace(" ", "T");
                        $('#dataAcao').val(dateBuffer);
                    } else {
                        $('#dataAcao').val("");
                    }

                    if (data.acaoPrazo == "S") {
                        $('#acaoPrazoSim').prop("checked", true);
                        $('#acaoPrazoNao').prop("checked", false);
                    } else if (data.acaoPrazo == "N") {
                        $('#acaoPrazoSim').prop("checked", false);
                        $('#acaoPrazoNao').prop("checked", true);
                    } else if (data.acaoPrazo == "") {
                        $('#acaoPrazoSim').prop("checked", false);
                        $('#acaoPrazoNao').prop("checked", false);
                    }

                    if (data.acaoEficiencia == "S") {
                        $('#acaoEficienciaSim').prop("checked", true);
                        $('#acaoEficienciaNao').prop("checked", false);
                    } else if (data.acaoEficiencia == "N") {
                        $('#acaoEficienciaSim').prop("checked", false);
                        $('#acaoEficienciaNao').prop("checked", true);
                    } else if (data.acaoEficiencia == "") {
                        $('#acaoEficienciaSim').prop("checked", false);
                        $('#acaoEficienciaNao').prop("checked", false);
                    }

                    if (data.dataEficiencia != null) {
                        dateBuffer = data.dataEficiencia.replace(" ", "T");
                        $('#dataEficiencia').val(dateBuffer);
                    } else {
                        $('#dataEficiencia').val("");
                    }

                    $('#descricaoAcao').val(data.descricaoAcao);

                } else {
                    console.log("Falha");
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                }
            }
        });

        return false;
    });

    /*$('#idRacap').on('change', function () {
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
     
     
     });*/

    $('#dataAcao').change(function () {
        console.log($('#dataAcao').val());
        
        if ($('#dataAcao').val() != "") {
            var sequencial = $('#racapAcaoRacap').serialize();

            $.ajax({
                url: 'busca_prazo_racap.php',
                dataType: "json",
                type: 'POST',
                data: sequencial,
                success: function (data) {
                    if (data.success == true) {
                        $("#acaoPrazoSim").prop("checked", true);

                    } else {
                        $("#acaoPrazoNao").prop("checked", true);
                    }
                }
            });
        } else {
            $('#acaoPrazoSim').prop('checked',false);
            $('#acaoPrazoNao').prop('checked',false);
        }

        return false;
    });

});