$(document).ready(function( ) {
	
	$('#buscaCompetencia').on('change',function(){
		
		var sequencial = $('#buscaMesCompetencia');
		console.log (sequencial.val());
		
		$.ajax({
		url: 'busca_competencia_manage.php',
		dataType:"json",
		type: 'POST',
		data: sequencial,
		success: function(data){
				if (data.success==true){
					console.log ("Encontrou resultado");
					$('#seqCompetencia').val(data.idCompetencia);
					$('#mesCompetencia').val(data.mesCompetencia);
					console.log (data.dataInicio);
					$('#dataInicio').val(data.dataInicio);
					$('#dataFim').val(data.dataFim);
					$('#diasUteis').val(data.diasUteis);
					
					
					if (data.flgAtivo == "S"){
						$('#flgAtivo').prop("checked",true);
					} else{
						$('#flgAtivo').prop("checked",false);
					}
					
				}else {
					$ ('#cadCompetencia').each(function(){
						this.reset();
						});
					}
			}
		});
		
	return false;
		
	});
    
});

function mostraValor (){
	var mostrar = document.getElementById ("buscaMesCompetencia");
	
	alert (mostrar.value);
}