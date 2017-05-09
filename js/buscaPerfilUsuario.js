$(document).ready(function ( ) {

    $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_perfil_usuario.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencial').val(data.id);
                    $('#descricao').val(data.descricao);

                } else {
                    alert("Sequencial inválido.");
                }
            }
        });

        return false;

    });

});