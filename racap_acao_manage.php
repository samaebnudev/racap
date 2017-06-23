<script type="text/javascript">
    function mensagem(chamada,texto){
        alert(chamada+": "+texto);
    }
	
	function voltar (var e){
		window.location.href = e;
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

if (isset($_POST['sequencialAcao'])) {
    $sequencial = $_POST ['sequencialAcao'];
} else {
    $sequencial = "0";
}

$idRacap = $_POST['idRacap'];
$selectStatusAcao = $_POST['selectStatusAcao'];
$selectResponsavel = $_POST['selectResponsavel'];
$descricaoAcao = $_POST['descricaoAcao'];
$urlVoltar = $_POST ['urlBack'];

$query = "SELECT * FROM racap_perfil WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_acao SET id_racap='$idRacap', status_acao = '$selectStatusAcao',
             descricao_acao = '$descricaoAcao', responsavel_acao = '$selectResponsavel' WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    
    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Alterou Ação de RACAP: " . $sequencial;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Ação da RACAP alterada com sucesso.');</script>";
        echo $message;
        //$urlBack = "<script>window.location.href = '.$urlVoltar.';</script)";
        //echo $urlBack;
        header("url:".$urlVoltar);
    } else {
        $message = "<script> alert ('Falha na alteração. Ação da RACAP não pôde ser alterada.');</script>";
        echo $message;
        //$urlBack = "<script>window.location.href = '.$urlVoltar.';</script)";
        //echo $urlBack;
        header("url:".$urlVoltar);
    }
}
elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_acao (id, id_racap, status_acao, 
        descricao_acao, responsavel_acao)
	VALUES ('$sequencial', '$idRacap', '$selectStatusAcao',
        '$descricaoAcao', '$selectResponsavel')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Incluiu Ação da RACAP: " . $descricaoAcao);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Ação da RACAP incluída com sucesso.');</script>";
        echo $message;
        //$urlBack = "<script>window.location.href = '.$urlVoltar.';</script)";
        //echo $urlBack;
        header("url:".$urlVoltar);
    } else {
        $message = "<script> alert ('Falha na inclusão. Ação da RACAP não pôde ser inserida.');</script>";
        echo $message;
        //$urlBack = "<script>window.location.href = '.$urlVoltar.';</script)";
        //echo $urlBack;
        header("url:".$urlVoltar);
    }
}