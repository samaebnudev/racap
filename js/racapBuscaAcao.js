/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {
    
    $('#acaoPrazoSim').hide();
    $('#acaoPrazoNao').hide();

    $('.noClick').click(function () {
        return false;
    });

    $('#buscaAcaoRacap').on('change', function () {
        var sequencial = $('#selectAcaoRacap');
        $.ajax({
            url: 'racap_busca_acao.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                    
                    $('#sequencialAcao').val(data.id);
                    $('#idRacap').val(data.idRacap);
                    $('#numeroAcao').val(data.numeroAcao);
                    $('#tituloAcao').val(data.tituloAcao);
                    $('#selectStatusAcao').val(data.selectStatusAcao);
                    
                    if ($('#selectStatusAcao').val() > 3){
                        $("#racapAcaoSubmit").prop("disabled", true);
                    } else {
                        $("#racapAcaoSubmit").prop("disabled", false);
                    }
                    
                    if (data.prazoExecucao != null) {
                        var dateBuffer = data.prazoExecucao.replace(" ", "T");
                        $('#prazoExecucao').val(dateBuffer);
                    } else {
                        $('#prazoExecucao').val("");
                    }
                    
                    if (data.dataAcao != null) {
                        dateBuffer = data.dataAcao.replace(" ", "T");
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
                    $('#racapAcaoRacap').each(function () {
                        this.reset();
                    });
                }
            }
        });

        return false;
    });

    $('#dataAcao').change(function () {
        
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
    
    $('#selectStatusAcao').change(function (){
        if ($('#selectStatusAcao').val()=='4'){
            $('#dataAcao').prop('required',true);
            $('#acaoEficienciaSim').prop('required',true);
            $('#dataEficiencia').prop('required',true);
        } else if ($('#selectStatusAcao').val()=='3'){
            $('#dataAcao').prop('required',true);
        } else {
            $('#dataAcao').prop('required',false);
            $('#acaoEficienciaSim').prop('required',false);
            $('#dataEficiencia').prop('required',false);
        }
    });
    
    $('#racapAcaoReset').click(function (){
        if ($('#selectStatusAcao').val() > '3'){
            $('#racapAcaoSubmit').prop('disabled',false);
            $('#sequencialAcao').val("");
        }
    });

});