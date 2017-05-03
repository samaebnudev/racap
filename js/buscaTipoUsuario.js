$(document).ready(function( ) {
	
	$('#buscaTipoUsuario').on('submit',function(){
		
		var sequencial = $('#barraBuscaTipoUsuario');
		
		$.ajax({
		url: 'busca_tipo_usuario_manage.php',
		dataType:"json",
		type: 'POST',
		data: sequencial,
		success: function(data){
				if (data.success==true){
					$('#seqTipoUsuario').val(data.idPrivilegio);
					$('#nomeTipoUsuario').val(data.tipoPrivilegio);
					
				}else {
					alert ("Sequencial inválido.");
					}
			}
		});
		
	return false;
		
	});
    
});