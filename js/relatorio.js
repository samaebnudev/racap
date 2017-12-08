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

});
