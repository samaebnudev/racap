/*function mostraCompetencia (){
	var competencia = document.getElementById ("competencia").value + "-01T00:00";
	alert (competencia);
}
*/

function restringeDataFimGlosa (){
	var inicioGlosa = document.getElementById ("inicioGlosa");
	var fimGlosa = document.getElementById ("fimGlosa");
	
	if (inicioGlosa.value){
		fimGlosa.min = inicioGlosa.value;
	}
}


/*function restringeDataMinMax (){
	//Pega o mês da Competência
	var competencia = document.getElementById ("competencia").value;
	
	//Ajusta a data mínima e a máxima permitida de acordo com a competencia. 
	var dateMin = competencia + "-01T00:00";
	
	var dateSplit = competencia.split("-");
	var lastDay = new Date (dateSplit[0], dateSplit[1], 0).getDate ();
	var dateMax = competencia+"-"+lastDay+"T23:59";
	
	//Altera min e max dos campos de data
	var inicioGlosa = document.getElementById("inicioGlosa");
	var fimGlosa = document.getElementById("fimGlosa");
	
	inicioGlosa.min = dateMin;
	inicioGlosa.max = dateMax;
	
	fimGlosa.min = dateMin;
	fimGlosa.max = dateMax;
}*/

function calculaHoras(){
	var inicioGlosa = document.getElementById("inicioGlosa");
	var fimGlosa = document.getElementById("fimGlosa");
	var tempoGlosa = document.getElementById("tempoGlosa");
	
	var pegaData, pegaHora, pegaData2, pegaHora2, dataInicio, dataFim;
	var dateSplit, dateSplit2;
	
	if (inicioGlosa.value && fimGlosa.value){
		/*Quebra a data/hora do inicioGlosa e fimGlosa
		  e cria datas no formato do Javascript a fim de calcular as horas da Glosa.
		*/
		
		//Data Inicial
		dateSplit = inicioGlosa.value;
		dateSplit = dateSplit.split ("T");
		pegaData = dateSplit[0].split ("-");
		pegaHora = dateSplit [1].split(":");
		
		dataInicio = new Date (pegaData[0],pegaData[1]-1,pegaData[2],
		pegaHora[0],pegaHora[1]);
		
		//Data Final
		dateSplit2 = fimGlosa.value;
		dateSplit2 = dateSplit2.split ("T");
		pegaData2 = dateSplit2[0].split ("-");
		pegaHora2 = dateSplit2 [1].split(":");
		
		dataFim = new Date (pegaData2[0],pegaData2[1]-1,pegaData2[2],
		pegaHora2[0],pegaHora2[1]);
		
		/*Se a data final for maior que a inicial inicia o cálculo das horas.
		Em caso negativo, o campo das horas é zerado.*/
		
		if (dataFim > dataInicio){
			var tempo, horas, minutos, segundos;
			
			tempo = dataFim - dataInicio;
			tempo = tempo/1000;
			
			horas = Math.floor(tempo/3600);
			
			minutos = (tempo - (3600 * horas))/60;
			
			if (horas < 10){
				horas = "0"+horas;
				}
			
			if (minutos < 10){
				minutos = "0"+minutos;
				}
				
			tempoGlosa.value = horas+":"+minutos;
			
		} else {
			tempoGlosa.value = "";
		}
	} else {
		tempoGlosa.value = "";
	}
	
}

function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
  }
  
function parametrosRelatorio (opcao){

var glosaRel = document.getElementById("glosaRel");
var userRel = document.getElementById ("userRel");
var  glosaIdRel = document.getElementById ("glosaIdRel");

	if (opcao == 1){
		glosaRel.disabled = false;
		userRel.disabled = true;
		glosaIdRel.disabled = true;
		
		glosaRel.required = true;
		userRel.required = false;
		glosaIdRel.required = false;
		glosaRel.focus();
	}
	
	if (opcao == 2){
		glosaRel.disabled = true;
		userRel.disabled = true;
		glosaIdRel.disabled = true;
		
		glosaRel.required = false;
		userRel.required = false;
		glosaIdRel.required = false;
	}
	
	if (opcao == 3){
		glosaRel.disabled = true;
		userRel.disabled = false;
		glosaIdRel.disabled = true;
		
		glosaRel.required = false;
		userRel.required = true;
		glosaIdRel.required = false;
		userRel.focus();
	}
	
	if (opcao == 4){
		glosaRel.disabled = true;
		userRel.disabled = true;
		glosaIdRel.disabled = false;
		
		glosaRel.required = false;
		userRel.required = false;
		glosaIdRel.required = true;
		glosaIdRel.focus();
	}
}