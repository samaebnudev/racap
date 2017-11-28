<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'racap.php';
    }
</script>

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");
include "conecta_banco.inc";
include "getIP.php";

$buffer = $_POST['idFechamento'];

if ($buffer != NULL) {
    $sequencial = $_POST ['idFechamento'];
} else {
    $sequencial = "0";
}

$idRacap = $_POST ['numRACAP'];
$dataFechamento = date('Y-m-d H:i:s');
$observacaoRACAP = $_POST['observacaoRACAP'];
$statusPos = $_POST['statusRacapPos'];

if ($statusPos == "4") {
    $query = "SELECT id, id_racap, status_acao FROM racap_acao WHERE id_racap = '$idRacap'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $statusAcao = $row['status_acao'];
        if ($statusAcao < 4) {
            $message = "<script> alert ('ESSA RACAP NÃO PODE SER FECHADA POIS EXISTEM AÇÕES PENDENTES PARA ELA.\\nFECHE OU CANCELE AS AÇÕES PENDENTES ANTES DE FECHAR A RACAP');</script>";
            echo $message;
            $urlBack = "<script>voltar ();</script>";
            echo $urlBack;
            exit();
        }

        while ($row = mysqli_fetch_array($sql)) {

            $statusAcao = $row['status_acao'];
            if ($statusAcao < 4) {
                $message = "<script> alert ('ESSA RACAP NÃO PODE SER FECHADA POIS EXISTEM AÇÕES PENDENTES PARA ELA.\\nFECHE OU CANCELE AS AÇÕES PENDENTES ANTES DE FECHAR A RACAP');</script>";
                echo $message;
                $urlBack = "<script>voltar ();</script>";
                echo $urlBack;
                exit();
            }
        }
    } elseif (mysqli_affected_rows($conexao) == 0) {
        $message = "<script> alert ('ESSA RACAP NÃO PODE SER FECHADA POIS NENHUMA AÇÃO FOI TOMADA PARA ELA.\\nCADASTRE E/OU EXECUTE AÇÕES NECESSÁRIAS ANTES DE FECHAR A RACAP.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
        exit();
    }
}


$query = "SELECT * FROM racap_fecha_racap WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {

    $query = "UPDATE racap_fecha_racap SET id_racap = '$idRacap', data_fechamento = '$dataFechamento',
             observacao_racap = '$observacaoRACAP', status_racap_pos = '$statusPos'
             WHERE id = '$sequencial'";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $query = "UPDATE racap_racap 
                SET status_racap = '$statusPos' WHERE id = '$idRacap'";
        $sql = mysqli_query($conexao, $query);

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Alterou Fechamento da RACAP: " . $idRacap;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Fechamento da RACAP alterado com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na alteração. Fechamento da RACAP não pôde ser alterado.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
} elseif (mysqli_affected_rows($conexao) == 0) {

    $query = "SELECT status_racap FROM racap_racap WHERE id = '$idRacap'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if ($row['status_racap'] == 4 || $row['status_racap'] == 5) {
        $message = "<script> alert ('A OPERAÇÃO ATUAL NÃO PODE SER CONCLUÍDA.\\nA RACAP JÁ FOI FECHADA OU CANCELADA ANTERIORMENTE.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
        exit();
    }

    $query = "INSERT INTO racap_fecha_racap (id, id_racap, data_fechamento, 
        observacao_racap, status_racap_pos) VALUES ('$sequencial', '$idRacap',
        '$dataFechamento', '$observacaoRACAP', '$statusPos')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $query = "UPDATE racap_racap 
                SET status_racap = '$statusPos' WHERE id = '$idRacap'";
        $sql = mysqli_query($conexao, $query);

        if ($statusPos == 5) {
            $query = "SELECT id, id_racap, status_acao FROM racap_acao WHERE id_racap = '$idRacap'";
            $sql = mysqli_query($conexao, $query);
            $row = mysqli_fetch_assoc($sql);

            if (mysqli_affected_rows($conexao) > 0) {
                $query = "UPDATE racap_acao SET status_acao = '5' WHERE id_racap='$idRacap'";
                $sql = mysqli_query($conexao, $query);
                //$row = mysqli_fetch_assoc($sql);
            }
        }


        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");

        if ($statusPos == 4) {
            $ocorrencia = "Encerramento da RACAP: " . $idRacap .
                    " - \n Descrição do Fechamento: " . $observacaoRACAP;
        } elseif ($statusPos == 5) {
            $ocorrencia = "Cancelamento da RACAP: " . $idRacap .
                    " - \n Descrição do Fechamento: " . $observacaoRACAP;
        }

        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Fechamento da RACAP incluído com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na inclusão. Fechamento da RACAP não pôde ser inserido.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
}    