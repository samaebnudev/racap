$(document).ready(function( ) {
	
	$('#formBuscaGlosa').on('change',function(){
		
		var sequencial = $('#buscaGlosa');
		$.ajax({
		url: 'busca_glosa.php',
		dataType:"json",
		type: 'POST',
		data: sequencial,
		success: function(data){
				if (data.success==true){
					$('#sequencial').val(data.sequencial);
					$('#tipoGlosa').val(data.tipoGlosa);
					
					//Verificar a formatação dos campos a seguir.
					$('#qtdOcorrencia').val(data.qtdOcorrencia);
					
					if (data.numTicket){
						$('#numTicket').val(data.numTicket);
					} else {
						$('#numTicket').val(null);
					}
					
					//Fazer a edição do valor de inicioGlosa e fimGlosa antes de jogar no form
					var dateBuffer = data.inicioGlosa.replace (" ","T");
					$('#inicioGlosa').val(dateBuffer);
					$('#fimGlosa').attr({"min":dateBuffer});
						
					if (data.fimGlosa){
						dateBuffer = data.fimGlosa.replace (" ","T");
						$('#fimGlosa').val(dateBuffer);
						$('#tempoGlosa').val(data.tempoGlosa);
					}
					
					
					//Checa se data.procedeGlosa tem algum valor.
					var getProcede;
					if (data.procedeGlosa == "S"){
						getProcede = document.getElementById("procedeGlosa");
						getProcede.checked = true;
						getProcede = document.getElementById("naoProcedeGlosa");
						getProcede.checked = false;
					}
					if (data.procedeGlosa == "N"){
						getProcede = document.getElementById("naoProcedeGlosa");
						getProcede.checked = true;
						getProcede = document.getElementById("procedeGlosa");
						getProcede.checked = false;
					}
					if (data.procedeGlosa == ""){
						getProcede = document.getElementById("naoProcedeGlosa");
						getProcede.checked = false;
						getProcede = document.getElementById("procedeGlosa");
						getProcede.checked = false;
					}
					
					$('#observGlosa').val(data.observGlosa);
					$('#conclusaoGlosa').val(data.conclusaoGlosa);
					
				}else {
					$('#cadGlosa').each(function(){
						this.reset();
						var dateMin = $('#inicioGlosa').attr('min');
						$('#fimGlosa').attr({'min':dateMin});
						});
					}
			}
		});
		
	return false;
		
	});
    
});