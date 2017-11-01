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

if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "0";
}

if ($sequencial != 0) {

    $nomeSetor = $_POST['nomeSetor'];

    $query = "DELETE FROM racap_setor WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Excluiu Setor: " . $sequencial . " - " . $nomeSetor;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Setor excluído com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na Exclusão. Setor não pôde ser Excluído."
                . "\n Verifique se o Setor Escolhido está vinculado a uma RACAP.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
} else {
    $message = "<script> alert ('AVISO: Nenhum Setor Válido foi informado.');</script>";
    echo $message;
    $urlBack = "<script>voltar ();</script>";
    echo $urlBack;
}