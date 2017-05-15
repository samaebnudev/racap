<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'setores.php';
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


if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "999999";
}

$statusRacap = $_POST['statusRacap'];
$tipoRacap = $_POST['tipoRacap'];
$motivoAbertura = $_POST['motivoAbertura'];
$motivoDescricao = $_POST['motivoDescricao'];
$setorRacap = $_POST['setorRacap'];

if (isset($_POST['causaRacap'])){
    $causaRacap = $_POST['causaRacap'];
}else{
    $causaRacap = NULL;
}

$dataAbertura = date ("Y-m-d H:i:s");

if (isset($_POST['prazoRacap'])){
    $dateBuffer = explode ("T", $_POST['prazoRacap']);
    $prazoRacap = implode(" ", $dateBuffer);
    $prazoRacap = date('Y-m-d H:i:00',strtotime($prazoRacap));
} else {
    $prazoRacap = NULL;
}

if (isset($_POST['anexoRacap'])){
    $message = "<script> alert ('Arquivo Anexado');</script>";
    echo $message;
}

$históricoRACAP = $_POST['históricoRACAP'];
