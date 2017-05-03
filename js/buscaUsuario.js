$(document).ready(function( ) {
	
	$('#buscaUsuario').on('submit',function(){
		
		var sequencial = $('#barraBuscaUsuario');
		
		$.ajax({
		url: 'busca_usuario_manage.php',
		dataType:"json",
		type: 'POST',
		data: sequencial,
		success: function(data){
				if (data.success==true){
					$('#seqUsuario').val(data.idUsuario);
					$('#nomeUsuario').val(data.nomeUsuario);
					$('#tipoUsuario').val(data.tipoPrivilegio);
					
					if (data.flgAtivo == "S"){
						$('#flgAtivo').prop("checked",true);
					} else{
						$('#flgAtivo').prop("checked",false);
					}
					
				}else {
					alert ("Sequencial inválido.");
					}
			}
		});
		
	return false;
		
	});
    
});