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

if (isset($_POST['sequencialAcao'])) {
    $sequencial = $_POST ['sequencialAcao'];
} else {
    $sequencial = "0";
}

$idRacap = $_POST['idRacap'];
$selectStatusAcao = $_POST['selectStatusAcao'];
$selectResponsavel = $_POST['selectResponsavel'];
$observacaoAcao = $_POST['observacaoAcao'];

$query = "SELECT * FROM racap_perfil WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_acao SET id_racap='$idRacap', status_acao = '$selectStatusAcao',
             descricao_acao = '' WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    
    
}
elseif (mysqli_affected_rows($conexao) == 0) {
    
}