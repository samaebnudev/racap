function setDateMinMax (){
	//Pega o ano e m�s da compet�ncia do formul�rio
	var mesCompetencia = document.getElementById ("mesCompetencia").value;
	
	//Ajusta o valor m�ximo a ser colocado na data inicial da compet�ncia.
	var dateMax = mesCompetencia+"-25";
	
	//Calcula o primeiro dia da compet�ncia e cria uma data v�lida.
	var dateSplit = mesCompetencia.split("-");
	if (dateSplit[1] - 1 == 0){
		dateSplit [0] -= 1;
		dateSplit [1] = 12;
	} else {
		dateSplit[1] -= 1;
	}
	
	if (dateSplit[1] < 10){
		var dateMin = dateSplit[0]+"-0"+dateSplit[1]+"-26";
	} else {
		var dateMin = dateSplit[0]+"-"+dateSplit[1]+"-26";
	}
	
	//Ajusta os valores de In�cio e Fim da Compet�ncia
	var dataInicio = document.getElementById ("dataInicio");
	var dataFim = document.getElementById ("dataFim");
	
	dataInicio.value = dateMin;
	dataFim.value = dateMax;
}

function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
  }