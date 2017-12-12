/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {

    $('#periodoRacapInicio').change(function () {
        var minDate = $('#periodoRacapInicio').val();
        $('#periodoRacapFim').attr({'min': minDate});
    });

    var dataLimite = $('#dataHoje').val();
    $('#dataLimite').attr({'min': dataLimite});
    
    $('#statusCheck').on('change',function () {
        if ($('#statusCheck').is(':checked')){
            $(':input[name=statusRacap]').prop('disabled',false);
            $(':input[name=statusRacap]').prop('required',true);
        }else {
            $(':input[name=statusRacap]').prop('disabled',true);
            $(':input[name=statusRacap]').prop('required',false);
        }
        
        return false;
    });
    
    $('#checkPeriodo').on('change',function () {
        if ($('#checkPeriodo').is(':checked')){
            $('#periodoRacapInicio').prop('disabled',false);
            $('#periodoRacapInicio').prop('required',true);
            $('#periodoRacapFim').prop('disabled',false);
            $('#periodoRacapFim').prop('required',true);
        }else {
            $('#periodoRacapInicio').prop('disabled',true);
            $('#periodoRacapInicio').prop('required',false);
            $('#periodoRacapFim').prop('disabled',true);
            $('#periodoRacapFim').prop('required',false);
        }
        
        return false;
    });
    
    $('#motivoAberturaCheck').on('change',function () {
        if ($('#motivoAberturaCheck').is(':checked')){
            $(':input[name=motivoRacap]').prop('disabled',false);
            $(':input[name=motivoRacap]').prop('required',true);
        }else {
            $(':input[name=motivoRacap]').prop('disabled',true);
            $(':input[name=motivoRacap]').prop('required',false);
        }
        
        return false;
    });
    
    $('#secaoCheck').on('change',function () {
        if ($('#secaoCheck').is(':checked')){
            $('#setorRacap').prop('disabled',false);
            $('#setorRacap').prop('required',true);
        }else {
            $('#setorRacap').prop('disabled',true);
            $('#setorRacap').prop('required',false);
        }
        
        return false;
    });

});
