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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$bufferSequencial = $_POST['sequencial'];
$sequencial = "0";

if ($bufferSequencial != NULL) {
    $sequencial = $bufferSequencial;
}


$statusRacap = $_POST['statusRacap'];
$tipoRacap = $_POST['tipoRacap'];
$motivoAbertura = $_POST['motivoAbertura'];
$motivoDescricao = $_POST['motivoDescricao'];
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

$historicoRACAP = $_POST['historicoRACAP'];

$selectResponsavel = $_POST['selectResponsavel'];

$autor = $_SESSION['nomeUsuario'];

$query = "SELECT * FROM racap_racap WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_racap
            SET status_racap = '$statusRacap', tipo_racap = '$tipoRacap',
            prazo_racap = '$prazoRacap',
            motivo_abertura_racap_id = '$motivoAbertura', 
            motivo_racap = '$motivoDescricao', setor_racap = '$setorRacap',
            historico_racap = '$historicoRACAP', causa_racap = '$causaRacap',
            autor_racap = '$autor'
            WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Alterou RACAP: " . $sequencial;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $racapMensagem = "RACAP alterada com sucesso.";

        $query = "UPDATE racap_acao SET prazo_acao = '$prazoRacap' WHERE id_racap = '$sequencial'";
        $sql = mysqli_query($conexao, $query);
    } else {
        $racapMensagem = "Falha na alteração. RACAP não pôde ser alterada.";
    }
} elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_racap (id, status_racap, tipo_racap, data_racap,
        prazo_racap, motivo_abertura_racap_id, motivo_racap, setor_racap,
        historico_racap, causa_racap, autor_racap)
	VALUES ('$sequencial', '$statusRacap', '$tipoRacap', '$dataAbertura',
        '$prazoRacap', '$motivoAbertura', '$motivoDescricao', '$setorRacap',
        '$historicoRACAP', '$causaRacap','$autor')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu RACAP: " . $motivoDescricao;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $racapMensagem = "RACAP incluída com sucesso.";
    } else {
        $racapMensagem = "Falha na inclusão. RACAP não pôde ser inserida.";
    }
}


$novaRacap = FALSE;
//Se a RACAP foi inserida com sucesso começa a inserir os responsáveis.
if ($racapMensagem == "RACAP incluída com sucesso." || $racapMensagem == "RACAP alterada com sucesso.") {

    /* Caso o sequencial seja 0, seleciona o id da última RACAP inserida
     * ,atualiza o sequencial e seta novaRAcap como TRUE. 
     * Senão deixa o sequencial no valor atual e novaRACAP como FALSE.
     */
    if ($sequencial == "0") {
        $query = "SELECT MAX(id) AS id FROM racap_racap;";
        $sql = mysqli_query($conexao, $query);
        $row = mysqli_fetch_assoc($sql);
        $sequencial = $row['id'];
        $novaRacap = TRUE;
    }
    

    if ($novaRacap == FALSE) {
        //Busca responsáveis pela RACAP específica
        $query = "SELECT * FROM racap_responsavel_racap WHERE id_racap = '$sequencial' ORDER BY id_responsavel;";
        $sql = mysqli_query($conexao, $query);
        $row = mysqli_fetch_assoc($sql);
        $result = array();
        $result [0] = $row['id_responsavel'];
        $i = 1;

        while ($row = mysqli_fetch_array($sql)) {
            $result[$i] = $row['id_responsavel'];
            $i++;
        }

        $elementosDiferentesResult = array();
        $elementosDiferentesForm = array();

        $controleArray = FALSE;

        //Compara os Arrays de Responsáveis por RACAP para fazer os devidos INSERTS e DELETES
        //Compara Responsáveis pela RACAP no Banco com o Select do Form
        $difIndex = 0;
        
        for ($i = 0; $i < count($result); $i++) {
            for ($j = 0; $j < count($selectResponsavel); $j++) {
                if ($result[$i] == $selectResponsavel[$j]) {
                    $controleArray = TRUE;
                }
            }

            if ($controleArray == FALSE) {
                $elementosDiferentesResult [$difIndex] = $result[$i];
                $difIndex ++;
            } elseif ($controleArray == TRUE) {
                $controleArray = FALSE;
            }
        }

        $controleArray = FALSE;

        for ($i = 0; $i < count($elementosDiferentesResult); $i++) {
            $query = "DELETE FROM racap_responsavel_racap "
                    . "WHERE id_racap = '$sequencial' "
                    . "AND id_responsavel = '$elementosDiferentesResult[$i]'";
            $sql = mysqli_query($conexao, $query);

            if ($sql) {

                $query = "SELECT nomeServidor FROM racap_usuario WHERE id='$elementosDiferentesResult[$i]]'";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_array($sql);
                $nomeUsuario = $row['nomeServidor'];

                $login = $_SESSION ['nomeUsuario'];
                $dataRegistro = date("Y-m-d H:i:s");
                $ocorrencia = "Removeu Responsável por RACAP: " . $sequencial . " - " . $nomeUsuario;
                $ip = get_client_ip_env();
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
                $sql = mysqli_query($conexao, $query);
            } else {
                //$racapMensagem = "Falha na inclusão. RACAP não pôde ser inserida.";
            }
        }
        
        $difIndex = 0;
        //Compara Responsáveis pela RACAP do Form com o Banco
        for ($i = 0; $i < count($selectResponsavel); $i++) {
            for ($j = 0; $j < count($result); $j++) {
                if ($selectResponsavel[$i] == $result[$j]) {
                    $controleArray = TRUE;
                }
            }

            if ($controleArray == FALSE) {
                $elementosDiferentesForm [$difIndex] = $selectResponsavel[$i];
                $difIndex++;
            } elseif ($controleArray == TRUE) {
                $controleArray = FALSE;
            }
        }

        for ($i = 0; $i < count($elementosDiferentesForm); $i++) {
            $query = "INSERT INTO racap_responsavel_racap (id, id_racap, id_responsavel) "
                    . "VALUES ('0', '$sequencial', '$elementosDiferentesForm[$i]]')";
            $sql = mysqli_query($conexao, $query);

            if ($sql) {

                $query = "SELECT nomeServidor FROM racap_usuario WHERE id='$elementosDiferentesForm[$i]]'";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_array($sql);
                $nomeUsuario = $row['nomeServidor'];

                $login = $_SESSION ['nomeUsuario'];
                $dataRegistro = date("Y-m-d H:i:s");
                $ocorrencia = "Inseriu Responsável por RACAP: " . $sequencial . " - " . $nomeUsuario;
                $ip = get_client_ip_env();
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
                $sql = mysqli_query($conexao, $query);
            }
        }
    } elseif ($novaRacap == true) {
        for ($i = 0; $i < count($selectResponsavel); $i++) {

            $query = "INSERT INTO racap_responsavel_racap (id, id_racap, id_responsavel) "
                    . "VALUES ('0', '$sequencial', '$selectResponsavel[$i]]')";
            $sql = mysqli_query($conexao, $query);

            if ($sql) {

                $query = "SELECT nomeServidor FROM racap_usuario WHERE id='$selectResponsavel[$i]]'";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_array($sql);
                $nomeUsuario = $row['nomeServidor'];

                $login = $_SESSION ['nomeUsuario'];
                $dataRegistro = date("Y-m-d H:i:s");
                $ocorrencia = "Inseriu Responsável por RACAP: " . $sequencial . " - " . $nomeUsuario;
                $ip = get_client_ip_env();
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
                $sql = mysqli_query($conexao, $query);
            }
        }
    }
}

$alertMessage = $racapMensagem;

echo '<script type="text/javascript">alert("' . $alertMessage . '");</script>';

$urlBack = "<script>voltar ();</script>";
echo $urlBack;
