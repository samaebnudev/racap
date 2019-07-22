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
include 'checar_acao.php';
include "calculaPrazoRacap.php";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$urlBack = "<script>voltar ();</script>";

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
$tituloAcao = $_POST['tituloAcao'];
$descricaoAcao = addslashes($_POST['descricaoAcao']);

if (isset($_POST['acaoPrazo'])) {
    $acaoPrazo = $_POST['acaoPrazo'];
}else {
    $acaoPrazo = "";
}


if (isset($_POST['acaoEficiencia'])) {
    $acaoEficiencia = $_POST['acaoEficiencia'];
}else {
    $acaoEficiencia = "";
}


$buffer = $_POST['prazoExecucao'];

if ($buffer != NULL) {
    $dateBuffer = explode("T", $buffer);
    $prazoExecucao = implode(" ", $dateBuffer);
    $prazoExecucao = date('Y-m-d H:i:00', strtotime($prazoExecucao));
} else {
    $prazoExecucao = NULL;
}

$buffer = $_POST['dataAcao'];

if ($buffer != NULL) {
    $dateBuffer = explode("T", $buffer);
    $dataAcao = implode(" ", $dateBuffer);
    $dataAcao = date('Y-m-d H:i:00', strtotime($dataAcao));
} else {
    $dataAcao = NULL;
}

/*$buffer = $_POST['dataEficiencia'];

if ($buffer != NULL) {
    $dateBuffer = explode("T", $buffer);
    $dataEficiencia = implode(" ", $dateBuffer);
    $dataEficiencia = date('Y-m-d H:i:00', strtotime($dataEficiencia));
} else {
    $dataEficiencia = NULL;
}*/

$query = "SELECT * FROM racap_acao WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_acao SET id_racap = '$idRacap', id_acao = '$numeroAcao', "
            . "status_acao = '$selectStatusAcao', titulo_acao = '$tituloAcao', "
            . "descricao_acao = '$descricaoAcao', acao_no_prazo = '$acaoPrazo', "
            . "data_acao = '$dataAcao', acao_eficaz = '$acaoEficiencia',"
            . "prazo_execucao = '$prazoExecucao' WHERE id = '$sequencial'";
    
    $sql = mysqli_query($conexao, $query);
    
    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Alterou Ação de RACAP: " . $sequencial;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "Ação da RACAP alterada com sucesso.";
    } else {
        $message = "Falha na alteração. Ação da RACAP não pôde ser alterada.";
    }
}
elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_acao (id, id_racap, id_acao,
        status_acao, titulo_acao, descricao_acao, acao_no_prazo, data_acao,
        acao_eficaz, data_eficacia, prazo_execucao) VALUES ('0', '$idRacap', '$numeroAcao',
        '$selectStatusAcao', '$tituloAcao', '$descricaoAcao', '$acaoPrazo',
        '$dataAcao', '$acaoEficiencia', '$prazoExecucao')";
    
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu Ação da RACAP: " . $tituloAcao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "Ação da RACAP incluída com sucesso.";
        
    } else {
        $message = "Falha na inclusão. Ação da RACAP não pôde ser inserida.";
    }
}

$checaFechamento = false;

if ($selectStatusAcao >= "3"){
    $checaAcao = checarStatus($conexao);
    $message = $message."\\n".$checaAcao;
    
    if ($checaAcao == "Todas as Ações Foram Executadas ou Canceladas. Já é possível Fechar a RACAP."){
        $checaFechamento = true;
    }
}

if ($checaFechamento == false){
 $calculaPrazo = calculaPrazoRacap($conexao);   
}

echo "<script> alert ('".$message."');</script>";
echo $urlBack;