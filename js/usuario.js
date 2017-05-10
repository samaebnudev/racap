function mostraSenha (){
	
	var showPassword = document.getElementById("senhaUsuario");
	
	if (showPassword.getAttribute("type")=="password"){
		showPassword.setAttribute ("type","text");
	} else{
		showPassword.setAttribute ("type","password");
	}
	
	return 0;
}
