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

$codSetor = $_POST['codSetor'];
$nomeSetor = $_POST['nomeSetor'];

$query = "SELECT * FROM racap_setor WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    
    $query = "UPDATE racap_setor SET codSetor='$codSetor', nomeSetor = '$nomeSetor' WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Alterou Setor: " . $sequencial);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Setor alterado com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na alteração. Setor não pôde ser alterado.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
}  elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_setor (id, codSetor, nomeSetor)
		VALUES ('$sequencial', '$codSetor', '$nomeSetor')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Incluiu Setor: " . $nomeSetor);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Setor incluído com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na inclusão. Setor não pôde ser inserido.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
}