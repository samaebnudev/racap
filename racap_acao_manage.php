<script type="text/javascript">
    function mensagem(chamada,texto){
        alert(chamada+": "+texto);
    }
	
	function voltar (){
		window.location.href = 'racap.php';
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

$bufferSequencial = $_POST['sequencialAcao'];

if ($bufferSequencial != NULL) {
    $sequencial = $bufferSequencial;
} else {
    $sequencial = "0";
}

$idRacap = $_POST['idRacap'];

$buffer = $_POST['numeroAcao'];

if ($buffer != NULL){
    $numeroAcao = $buffer;
} elseif ($buffer == NULL){
    $query = "SELECT MAX(id_acao) as numeroAcao FROM racap_acao WHERE id_racap = '$idRacap'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);
    
    if (mysqli_affected_rows($conexao) == 1){
        $numeroAcao = $row['numeroAcao'] + 1;
    } else {
      $numeroAcao = 1;  
    }   
}

$selectStatusAcao = $_POST['selectStatusAcao'];
//$selectResponsavel = $_POST['selectResponsavel'];
$tituloAcao = $_POST['tituloAcao'];
$descricaoAcao = $_POST['descricaoAcao'];

if (isset($_POST['acaoPrazo'])){
    $acaoPrazo = $_POST['acaoPrazo'];
}else {
    $acaoPrazo = "";
}

if (isset($_POST['acaoEficiencia'])){
    $acaoEficiencia = $_POST['acaoEficiencia'];
}else {
    $acaoEficiencia = "";
}
if (isset($_POST['dataAcao'])) {
    $dateBuffer = explode("T", $_POST['dataAcao']);
    $dataAcao = implode(" ", $dateBuffer);
    $dataAcao = date('Y-m-d H:i:00', strtotime($dataAcao));
} else {
    $dataAcao = 'NULL';
}

if (isset($_POST['dataEficiencia'])) {
    $dateBuffer = explode("T", $_POST['dataEficiencia']);
    $dataEficiencia = implode(" ", $dateBuffer);
    $dataEficiencia = date('Y-m-d H:i:00', strtotime($dataAcao));
} else {
    $dataEficiencia = 'NULL';
}

$query = "SELECT * FROM racap_acao WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_acao SET id_racap = '$idRacap', id_acao = '$numeroAcao', status_acao = '$selectStatusAcao', titulo_acao = '$tituloAcao', descricao_acao = '$descricaoAcao', acao_no_prazo = '$acaoPrazo', data_acao = '$dataAcao', acao_eficaz = '$acaoEficiencia', data_eficacia = '$dataEficiencia' WHERE id = '$sequencial'";
    
    echo $query;
    
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
        $urlBack = "<script>voltar ();</script>";
        //echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na alteração. Ação da RACAP não pôde ser alterada.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        //echo $urlBack;
    }
}
elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_acao (id, id_racap, id_acao,
        status_acao, titulo_acao, descricao_acao, acao_no_prazo, data_acao,
        acao_eficaz, data_eficacia) VALUES ('0', '$idRacap', '$numeroAcao',
        '$selectStatusAcao', '$tituloAcao', '$descricaoAcao', '$acaoPrazo',
        '$dataAcao', '$acaoEficiencia', '$dataEficiencia')";
    
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu Ação da RACAP: " . $tituloAcao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Ação da RACAP incluída com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        //echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na inclusão. Ação da RACAP não pôde ser inserida.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        //echo $urlBack;
    }
}