$(document).ready(function( ) {
	
	$('#buscaTipoGlosa').on('change',function(){
		
		var sequencial = $('#buscaOcor');
		
		$.ajax({
		url: 'busca_tipo_glosa.php',
		dataType:"json",
		type: 'POST',
		data: sequencial,
		success: function(data){
				if (data.success==true){
					$('#ocorSeq').val(data.idTipoGlosa);
					$('#perfilOcor').val(data.tipoPrivilegio);
					$('#descResumida').val(data.descRes);
					$('#descDetalhada').val(data.descDet);
					$('#vinculo').val(data.vinculo);
					
				}else {
					$('#cadOcor').each(function(){
						this.reset();
						});
					}
			}
		});
		
	return false;
		
	});
    
});