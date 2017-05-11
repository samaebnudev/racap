$(document).ready(function ( ) {

    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_setores.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencial').val(data.id);
                    $('#codSetor').val(data.codSetor);
                    $('#nomeSetor').val(data.nomeSetor);

                } else {
                    alert("Sequencial inválido.");
                }
            }
        });

        return false;

    });

});