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


if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "0";
}

$statusRacap = $_POST['statusRacap'];
$tipoRacap = $_POST['tipoRacap'];
$motivoAbertura = $_POST['motivoAbertura'];
$motivoDescricao = utf8_encode($_POST['motivoDescricao']);
$setorRacap = $_POST['setorRacap'];

if (isset($_POST['causaRacap'])) {
    $causaRacap = $_POST['causaRacap'];
} else {
    $causaRacap = NULL;
}

$dataAbertura = date("Y-m-d H:i:s");

if (isset($_POST['prazoRacap'])) {
    $dateBuffer = explode("T", $_POST['prazoRacap']);
    $prazoRacap = implode(" ", $dateBuffer);
    $prazoRacap = date('Y-m-d H:i:00', strtotime($prazoRacap));
} else {
    $prazoRacap = NULL;
}

$históricoRACAP = $_POST['históricoRACAP'];

$query = "SELECT * FROM racap_racap WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {

    $query = "UPDATE racap_racap
            SET status_racap = '$statusRacap', tipo_racap = '$tipoRacap',
            prazo_racap = '$prazoRacap',
            motivo_abertura_racap_id = '$motivoAbertura', 
            motivo_racap = '$motivoDescricao', setor_racap = '$setorRacap',
            historico_racap = '$históricoRACAP', causa_racap = '$causaRacap'
            WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Alterou RACAP: " . $sequencial);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $racapMensagem = "RACAP alterada com sucesso. \n";
    } else {
        $racapMensagem = "Falha na alteração. RACAP não pôde ser alterada. \n";
    }
} elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_racap (id, status_racap, tipo_racap, data_racap
        prazo_racap, motivo_abertura_racap_id, motivo_racap, setor_racap,
        historico_racap, causa_racap)
	VALUES ('$sequencial', '$statusRacap', '$tipoRacap', '$dataAbertura',
        '$prazoRacap', '$motivoAbertura', '$motivoDescricao', '$setorRacap',
        '$históricoRACAP', '$causaRacap')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Incluiu RACAP: " . $motivoDescricao);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $racapMensagem = "RACAP incluída com sucesso. \n";
    } else {
        $racapMensagem = "Falha na inclusão. RACAP não pôde ser inserida. \n";
    }
}

if ($_FILES['anexoRacap']) {
    $anexoMensagem = anexaArquivo();
} else {
    $anexoMensagem = "";
}

if ($anexoMensagem == "Upload efetuado com sucesso!"){
    if ($sequencial == "0") {
        $query = "SELECT MAX(id) AS id FROM racap_racap;";
        $sql = mysqli_query($conexao, $query);
        $row = mysqli_fetch_assoc($sql);

        if ($sql) {
            $sequencial = $row['id'];
            $url = $_UP['pasta'] . $nome_final;

            $query = "INSERT INTO racap_anexo (id, racap_id, nome_arquivo, url)
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
    } elseif ($sequencial != 0) {
        $url = $_UP['pasta'] . $nome_final;

        $query = "INSERT INTO racap_anexo (id, racap_id, nome_arquivo, url)
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
}

$alertMessage = $racapMensagem . $anexoMensagem;

$message = "<script> alert ('$alertMessage');</script>";
echo $message;
/*$urlBack = "<script>voltar ();</script>";
echo $urlBack;*/