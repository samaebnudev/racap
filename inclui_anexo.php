<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'racap.php';
    }
</script>

<?php
session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");
include "conecta_banco.inc";
include "getIP.php";
include 'racap_anexo.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$nome_final = $_FILES['anexoRacap']['name'];
//echo $nome_final."<br/>";
$sequencial = $_POST['numRACAPFormAnexo'];
$pasta = "uploads/";
$anexoMensagem = anexaArquivo();
$anexoMensagemCount = count($anexoMensagem);

if ($anexoMensagemCount == 2) {
    $nome_final = $anexoMensagem [1];
}


if ($anexoMensagem [0] == "Upload efetuado com sucesso!") {

    $url = $pasta . $nome_final;

    $query = "INSERT INTO racap_anexo (id, id_racap, nome_arquivo, url)
                            VALUES ('0','$sequencial','$nome_final','$url')";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu Anexo na RACAP " . $sequencial . " " . $motivoDescricao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
                            VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);
    }
}


$alertMessage = $anexoMensagem [0];

echo '<script type="text/javascript">alert("' . $alertMessage . '");</script>';

$urlBack = "<script>voltar ();</script>";
echo $urlBack;
