<script type="text/javascript">
    function mensagem(chamada,texto){
        alert(chamada+": "+texto);
    }
	
	function voltar (){
		window.location.href = 'motivo_abertura_racap.php';
	}
</script>

<?php
session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");
include "conecta_banco.inc";
include "getIP.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Incluir ou Alterar Motivo de Abertura de RACAP.
if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "0";
}

$descricao = $_POST['descricao'];

$query = "SELECT * FROM racap_motivo_abertura WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {

    $query = "UPDATE racap_motivo_abertura SET descricao='$descricao' WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Alterou Motivo de Abertura de RACAP: " . $sequencial;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Motivo de Abertura de RACAP alterado com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na alteração. Motivo de Abertura de RACAP não pôde ser alterado.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
} elseif (mysqli_affected_rows($conexao) == 0) {

    $query = "INSERT INTO racap_motivo_abertura (id, descricao)
		VALUES ('$sequencial', '$descricao')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu Motivo de Abertura de RACAP: " . $descricao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Motivo de Abertura de RACAP incluído com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na inclusão. Motivo de Abertura de RACAP não pôde ser inserido.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
}
