$(document).ready(function ( ) {
    var multiSelector = $('.responsavel').multipleSelect();
    
    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');
        //var multiSelector = $('.responsavel').multipleSelect();
        //var i = 0;

        $.ajax({
            url: 'busca_responsavel_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    multiSelector.multipleSelect('uncheckAll');
                    multiSelector.multipleSelect('setSelects', data.id_responsavel);
                    
                    if ($('#statusRacap').val()>3){
                        multiSelector.multipleSelect('disable');
                    } else {
                        multiSelector.multipleSelect('enable');
                    }
                    
                    multiSelector.multipleSelect('refresh');

                } else {
                    multiSelector.multipleSelect('uncheckAll');
                    multiSelector.multipleSelect('refresh');
                }
            }
        });

        return false;

    });
    
    $('#racapResetForm').on('click',function(){
        multiSelector.multipleSelect('uncheckAll');
    });

});