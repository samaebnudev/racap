$(document).ready(function ( ) {

    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');
        var multiSelector = $('.responsavel').multipleSelect();
        //var i = 0;

        $.ajax({
            url: 'busca_responsavel_racap.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    multiSelector.multipleSelect('setSelects', [data.id_responsavel]);
                    multiSelector.multipleSelect('refresh');
                    //$('select').multipleSelect('setSelects', [1, 3]);

                } else {
                    //alert("Sequencial inválido.");
                }
            }
        });

        return false;

    });

});