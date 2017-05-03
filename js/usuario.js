function mudaFormBusca (tipoBusca){
	
	var buscaUsuario = document.getElementById ("buscaUsuario");
	var buscaTipoUsuario = document.getElementById ("buscaTipoUsuario");
	
	if (tipoBusca == 0){
		buscaUsuario.style.display = "inline";
		buscaTipoUsuario.style.display = "none";
	}
	
	if (tipoBusca == 1){
		buscaUsuario.style.display = "none";
		buscaTipoUsuario.style.display = "inline";
	}
	
	return 0;
}

function mostraSenha (){
	
	var showPassword = document.getElementById("senhaUsuario");
	
	if (showPassword.getAttribute("type")=="password"){
		showPassword.setAttribute ("type","text");
	} else{
		showPassword.setAttribute ("type","password");
	}
	
	return 0;
}

function aparecerMensagem (){
	alert ("Apareceu uma mensagem.");
}
