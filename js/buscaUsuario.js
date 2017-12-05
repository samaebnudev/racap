$(document).ready(function ( ) {

     $('#buscaBanco').on('change', function () {

        var sequencial = $('#selectbuscaBanco');

        $.ajax({
            url: 'busca_usuario.php',
            dataType: "json",
            type: 'POST',
            data: sequencial,
            success: function (data) {
                if (data.success == true) {
                    $('#sequencial').val(data.id);
                    $('#matUsuario').val(data.matUsuario);
                    $('#nomeUsuario').val(data.nomeUsuario);
                    $('#setorUsuario').val(data.setorUsuario)
                    $('#perfilUsuario').val(data.perfilUsuario);

                    if (data.flgAtivo == "S") {
                        $('#flgAtivo').prop("checked", true);
                    } else {
                        $('#flgAtivo').prop("checked", false);
                    }

                } else {
                    alert("Sequencial inválido.");
                }
            }
        });

        return false;

    });
    
    $('#flgAtivo').on('change', function(){
        if ($('#flgAtivo').is(':checked')){
            $('#senhaUsuario').prop('required',true);
        }else {
           $('#senhaUsuario').prop('required',false); 
        }
    });

});