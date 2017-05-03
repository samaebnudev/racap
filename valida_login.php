<script type="text/javascript">

function voltar (){
	window.location.href = 'login.php';
}

</script>

<?php

session_start();
date_default_timezone_set('Brazil/East');

// Fix for removed Session functions
  function fix_session_register(){
    function session_register(){
        $args = func_get_args();
        foreach ($args as $key){
            $_SESSION[$key]=$GLOBALS[$key];
        }
    }
    function session_is_registered($key){
        return isset($_SESSION[$key]);
    }
    function session_unregister($key){
        unset($_SESSION[$key]);
    }
  }

  if (!function_exists('session_register')){
	  fix_session_register();
  }
  
include "conecta_banco.inc";
include "getIP.php";

$login = $_POST['usuario'];
$password = $_POST['senha'];

$query = "SELECT * FROM glosa_usuario WHERE nomeUsuario = '$login'";
$sql = mysqli_query ($conexao,$query);

if (mysqli_affected_rows($conexao) == 1){
	
	$row = mysqli_fetch_assoc ($sql);
	
	/*Código antigo: 
		if (password_verify ($password , $row['senha']) and $row['flgAtivo'] == "S"){
			
	 Código Locaweb:
		if (crypt ($password , $row['senha']) == $row['senha'] and $row['flgAtivo'] == "S"){
	*/
	
	if (password_verify ($password , $row['senha']) and $row['flgAtivo'] == "S"){
		
		$idUsuario = $row ['id'];
		$tipoPrivilegio = $row ['tipoPrivilegio'];
		$flgAtivo = $row ['flgAtivo'];
		
		$_SESSION ['id'] = $idUsuario;
		$_SESSION ['tipoPrivilegio'] = $tipoPrivilegio;
		$_SESSION ['nomeUsuario'] = $login;
		$_SESSION ['senha'] = $password;
		$_SESSION ['flgAtivo'] = $flgAtivo;
		
		$dataRegistro = date ("Y-m-d H:i:s");
		$ocorrencia = "Entrou no Sistema.";
		$ip = get_client_ip_env();
		$query = "INSERT INTO glosa_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
		$sql = mysqli_query ($conexao, $query);
		
		mysqli_close($conexao);
        header("Location:index2.php");
		
	}
	
	/*
	  Código Locaweb:
		elseif (crypt ($password , $row['senha']) == $row['senha'] and $row['flgAtivo'] == "N"){
	*/
	elseif (password_verify ($password , $row['senha']) and $row['flgAtivo'] == "N"){
		
		$dataRegistro = date ("Y-m-d H:i:s");
		$ocorrencia = utf8_encode("Tentativa de login com usuário inativo.");
		$ip = get_client_ip_env();
		$query = "INSERT INTO glosa_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
		$sql = mysqli_query ($conexao, $query);
		
		echo "<script> alert ('ESTE USUÁRIO FOI DESATIVADO PELO ADMINISTRADOR DO SISTEMA.');</script>";
		mysqli_close($conexao);
		echo "<script>voltar ();</script>";		
	}
	
	/*
	  Código Locaweb:
		elseif (crypt ($password , $row['senha']) != $row['senha']){
	*/
	elseif (!password_verify($password , $row['senha'])){
		
		$dataRegistro = date ("Y-m-d H:i:s");
		$ocorrencia = "Tentativa de login. Senha Incorreta.";
		$ip = get_client_ip_env();
		$query = "INSERT INTO glosa_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
		$sql = mysqli_query ($conexao, $query);
		
		echo "<script> alert ('SENHA INCORRETA.');</script>";
		mysqli_close($conexao);
		echo "<script>voltar ();</script>";
	}
} else {
	
	$dataRegistro = date ("Y-m-d H:i:s");
	$ocorrencia = utf8_encode("Tentativa de login. Usuário e/ou senha incorreto(s).");
	$ip = get_client_ip_env();
	$query = "INSERT INTO glosa_log (id, dataRegistro, ocorrencia, usuario, ip) 
	VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
	$sql = mysqli_query ($conexao, $query);
	
	echo "<script> alert ('USUÁRIO E/OU SENHA INCORRETOS.');</script>";
	mysqli_close($conexao);
	echo "<script>voltar ();</script>";
}
?>