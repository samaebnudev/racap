function mostraSenha (campoSenha){
	
	var senhaAtual = document.getElementById("senhaAtual");
	var novaSenha = document.getElementById("novaSenha");
	var novaSenha2 = document.getElementById("novaSenha2");
	
	if (campoSenha == 0){
		if (senhaAtual.getAttribute("type")=="password"){
			senhaAtual.setAttribute ("type","text");
		} else{
			senhaAtual.setAttribute ("type","password");
		}
	}
	
	if (campoSenha == 1){
		if (novaSenha.getAttribute("type")=="password"){
			novaSenha.setAttribute ("type","text");
		} else{
			novaSenha.setAttribute ("type","password");
		}
	}
	
	if (campoSenha == 2){
		if (novaSenha2.getAttribute("type")=="password"){
			novaSenha2.setAttribute ("type","text");
		} else{
			novaSenha2.setAttribute ("type","password");
		}
	}
	
	return 0;
}

function verificaSenha (){
	var novaSenha = document.getElementById("novaSenha");
	var novaSenha2 = document.getElementById("novaSenha2");
	
	if (novaSenha.value != novaSenha2.value){
		alert ("PREENCHA OS CAMPOS DE NOVA SENHA COM OS MESMOS VALORES.");
		return false;
	}
	
}