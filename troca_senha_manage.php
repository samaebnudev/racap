<script type="text/javascript">
function voltar (){
		window.location.href = 'troca_senha.php';
	}
</script>

<?php
session_start();
date_default_timezone_set('Brazil/East');
include "conecta_banco.inc";
include "getIP.php";

//$dados = array('success' => false, 'failMessage'=>"");
$password = $_POST['senhaAtual'];
$newPassword = $_POST['novaSenha'];
$newPassword2 = $_POST['novaSenha2'];
$id = $_SESSION['id'];
$nomeUsuario = $_SESSION ['nomeUsuario'];

$query = "SELECT senha FROM racap_usuario WHERE id = '$id'";
$sql = mysqli_query($conexao,$query);
$row = mysqli_fetch_assoc($sql);

if (password_verify ($password , $row['senha'])){
	
	if ($newPassword == $newPassword2){
		$passwordHash = password_hash ($newPassword, PASSWORD_DEFAULT);
		$query = "UPDATE racap_usuario SET senha='$passwordHash' WHERE id = '$id'";
		$sql = mysqli_query($conexao,$query);
		
		if ($sql){
				//$dados['success']=true;
				$dataRegistro = date ("Y-m-d H:i:s");
				$ocorrencia = "Troca de senha do usuário";
				$ip = get_client_ip_env();
				$query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
				VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
				$sql = mysqli_query ($conexao, $query);
				
				$message = "<script> alert ('SENHA ALTERADA COM SUCESSO.');</script>";
				echo $message;
				$urlBack = "<script>voltar ();</script>";
				echo $urlBack;
			}
		
	} else {
		$message = "<script> alert ('PREENCHA OS CAMPOS DE NOVA SENHA COM OS MESMOS VALORES.');</script>";
		echo $message;
		$urlBack = "<script>voltar ();</script>";
		echo $urlBack;
	}
} else{
	$message = "<script> alert ('SENHA INCORRETA. VERIFIQUE SUA SENHA E TENTE NOVAMENTE.');</script>";
	echo $message;
	$urlBack = "<script>voltar ();</script>";
	echo $urlBack;
}
?>