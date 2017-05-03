function mudaFormLog (formIndex) {

//var logAll = document.getElementById ("logAll");
var logByDate = document.getElementById ("logByDate");
var logByEntrie = document.getElementById ("logByEntrie");
var logByUser = document.getElementById ("logByUser");
var logByLoginAttempt = document.getElementById ("logByLoginAttempt");

/*if (formIndex == 0){
	logAll.style.display = "block";
	logByDate.style.display = "none";
	logByEntrie.style.display = "none";
	logByUser.style.display = "none";
	logByLoginAttempt.style.display = "none";
}*/

if (formIndex == 1){
	//logAll.style.display = "none";
	logByDate.style.display = "block";
	logByEntrie.style.display = "none";
	logByUser.style.display = "none";
	logByLoginAttempt.style.display = "none";
}

if (formIndex == 2){
	//logAll.style.display = "none";
	logByDate.style.display = "none";
	logByEntrie.style.display = "block";
	logByUser.style.display = "none";
	logByLoginAttempt.style.display = "none";
}

if (formIndex == 3){
	//logAll.style.display = "none";
	logByDate.style.display = "none";
	logByEntrie.style.display = "none";
	logByUser.style.display = "block";
	logByLoginAttempt.style.display = "none";
}

if (formIndex == 4){
	//logAll.style.display = "none";
	logByDate.style.display = "none";
	logByEntrie.style.display = "none";
	logByUser.style.display = "none";
	logByLoginAttempt.style.display = "block";
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
  
function userActivityForm(opcao){
	
	var byUserDataInicio = document.getElementById("byUserDataInicio");
	var byUserDataFim = document.getElementById ("byUserDataFim");
	var byUserLastEntries = document.getElementById ("byUserLastEntries");
	
	if (opcao == "0"){
		// Muda atributo somente leitura dos campos.
		byUserDataInicio.readOnly = false;
		byUserDataFim.readOnly = false;
		byUserLastEntries.readOnly = true;
		
		//Muda atributo required dos campos.
		byUserDataInicio.required = true;
		byUserDataFim.required = true;
		byUserLastEntries.required = false;
	}
	
	if (opcao == "1"){
		// Muda atributo somente leitura dos campos.
		byUserDataInicio.readOnly = true;
		byUserDataFim.readOnly = true;
		byUserLastEntries.readOnly = false;
		
		//Muda atributo required dos campos.
		byUserDataInicio.required = false;
		byUserDataFim.required = false;
		byUserLastEntries.required = true;
	}
	
}

function loginAttemptForm(opcao){
	
	var loginAttemptNumber = document.getElementById ("loginAttemptNumber");
	var loginAttemptDateStart = document.getElementById ("loginAttemptDateStart");
	var loginAttemptDateEnd = document.getElementById ("loginAttemptDateEnd");
	
	if (opcao == "0"){
		//Muda readOnly.
		loginAttemptNumber.readOnly = false;
		loginAttemptDateStart.readOnly = true;
		loginAttemptDateEnd.readOnly = true;
		
		//Muda required.
		loginAttemptNumber.required = true;
		loginAttemptDateStart.required = false;
		loginAttemptDateEnd.required = false;
	}
	
	if (opcao == "1"){
		//Muda readOnly.
		loginAttemptNumber.readOnly = true;
		loginAttemptDateStart.readOnly = false;
		loginAttemptDateEnd.readOnly = false;
		
		//Muda required.
		loginAttemptNumber.required = false;
		loginAttemptDateStart.required = true;
		loginAttemptDateEnd.required = true;
	}
	
}

/*Funções de restrição das datas finais em diferentes forms.
 As funções abaixo garantem que as datas finais nos logs por Data, Usuário
 e tentativas de Login sempre sejam iguais ou maiores que as datas iniciais.
*/

//Restrição de datas em Log por data
function restringebyDateDataFim (){
	var byDateDataInicio = document.getElementById ("byDateDataInicio");
	var byDateDataFim = document.getElementById ("byDateDataFim");
	
	if (byDateDataInicio.value){
		byDateDataFim.min = byDateDataInicio.value;
	}
}

//Restrição de datas em Log por usuário
function restringebyUserDataFim (){
	var byUserDataInicio = document.getElementById ("byUserDataInicio");
	var byUserDataFim = document.getElementById ("byUserDataFim");
	
	if (byUserDataInicio.value){
		byUserDataFim.min = byUserDataInicio.value;
	}
}

//Restrição de datas em Log por tentativa de Login.
function restringeloginAttemptDateEnd (){
	var loginAttemptDateStart = document.getElementById ("loginAttemptDateStart");
	var loginAttemptDateEnd = document.getElementById ("loginAttemptDateEnd");
	
	if (loginAttemptDateStart.value){
		loginAttemptDateEnd.min = loginAttemptDateStart.value;
	}
}