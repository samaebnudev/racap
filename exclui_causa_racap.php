<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'causa_racap.php';
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

//Incluir ou Alterar privilegios de usuário.
if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "0";
}

if ($sequencial != 0) {
    $descricao = $_POST['descricao'];

    $query = "DELETE FROM racap_causa WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Excluiu Causa de RACAP: " . $sequencial . " - " . $descricao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
                         VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Causa de RACAP excluída com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na Exclusão. Causa não pôde ser Excluída."
                . "\n Verifique se a Causa Escolhida está vinculada a uma RACAP.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
} else {
    $message = "<script> alert ('AVISO: Nenhuma Causa Válida foi informada.');</script>";
    echo $message;
    $urlBack = "<script>voltar ();</script>";
    echo $urlBack;
}

