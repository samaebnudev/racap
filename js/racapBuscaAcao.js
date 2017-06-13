/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function ( ) {
    
    $('#buscaAcaoRacap').on('change', function () {
        var sequencial = $('#selectAcaoRacap');
        
        $.ajax({
            url: '',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                   
                    

                } else {
                    
                }
            }
        });

        return false;
    });
    
});