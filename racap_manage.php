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
//include 'racap_anexo.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$bufferSequencial = $_POST['sequencial'];
//echo "Buffer Sequencial: ".$bufferSequencial."<br/>";
$sequencial = "0";

if ($bufferSequencial != NULL) {
    //echo "Buffer Sequencial não está vazio <br/>";
    $sequencial = $bufferSequencial;
}

//echo "Sequencial: ".$sequencial."<br/>";

$statusRacap = $_POST['statusRacap'];
//echo "Status RACAP: ".$statusRacap."<br/>";
$tipoRacap = $_POST['tipoRacap'];
//echo "Tipo RACAP: ".$tipoRacap."<br/>";
$motivoAbertura = $_POST['motivoAbertura'];
//echo "Motivo de Abertura da RACAP".$motivoAbertura."<br/>";
$motivoDescricao = $_POST['motivoDescricao'];
//echo "Descricao do Motivo da RACAP: ".$motivoDescricao."<br/>";
$setorRacap = $_POST['setorRacap'];
//echo "Setor: ".$setorRacap."<br/>";

if (isset($_POST['causaRacap'])) {
    $causaRacap = $_POST['causaRacap'];
} else {
    $causaRacap = NULL;
}

//echo "Causa da RACAP: ".$causaRacap."<br/>";

$dataAbertura = date("Y-m-d H:i:s");

//echo "Data de Abertura: ".$dataAbertura."<br/>";

if (isset($_POST['prazoRacap'])) {
    $dateBuffer = explode("T", $_POST['prazoRacap']);
    $prazoRacap = implode(" ", $dateBuffer);
    $prazoRacap = date('Y-m-d H:i:00', strtotime($prazoRacap));
} else {
    $prazoRacap = NULL;
}

//echo "Data de Prazo: ".$prazoRacap."<br/>";

$historicoRACAP = $_POST['historicoRACAP'];

//echo "Histórico: ".$historicoRACAP."<br/>";

$selectResponsavel = $_POST['selectResponsavel'];

$query = "SELECT * FROM racap_racap WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    //echo "Entrou no UPDATE </br>";
    $query = "UPDATE racap_racap
            SET status_racap = '$statusRacap', tipo_racap = '$tipoRacap',
            prazo_racap = '$prazoRacap',
            motivo_abertura_racap_id = '$motivoAbertura', 
            motivo_racap = '$motivoDescricao', setor_racap = '$setorRacap',
            historico_racap = '$historicoRACAP', causa_racap = '$causaRacap'
            WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);

    if ($sql) {
        //echo "UPDATE com sucesso, registrando LOG </br>";
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
    //echo "Entrou no INSERT </br>";
    $query = "INSERT INTO racap_racap (id, status_racap, tipo_racap, data_racap,
        prazo_racap, motivo_abertura_racap_id, motivo_racap, setor_racap,
        historico_racap, causa_racap)
	VALUES ('$sequencial', '$statusRacap', '$tipoRacap', '$dataAbertura',
        '$prazoRacap', '$motivoAbertura', '$motivoDescricao', '$setorRacap',
        '$historicoRACAP', '$causaRacap')";

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
        //
        //Compara Responsáveis pela RACAP no Banco com o Select do Form
        for ($i = 0; $i < count($result); $i++) {
            for ($j = 0; $j < count($selectResponsavel); $j++) {
                if ($result[$i] == $selectResponsavel[$j]) {
                    $controleArray = TRUE;
                }
            }

            if ($controleArray == FALSE) {
                $elementosDiferentesResult [$i] = $result[$i];
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
                $ocorrencia = "Removeu Responsável por RACAP: " . $sequencial." - " . $nomeUsuario;
                $ip = get_client_ip_env();
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
                $sql = mysqli_query($conexao, $query);

                //$racapMensagem = "RACAP incluída com sucesso.";
            } else {
                //$racapMensagem = "Falha na inclusão. RACAP não pôde ser inserida.";
            }
        }

        //Compara Responsáveis pela RACAP do Form com o Banco
        for ($i = 0; $i < count($selectResponsavel); $i++) {
            for ($j = 0; $j < count($result); $j++) {
                if ($selectResponsavel[$i] == $result[$j]) {
                    $controleArray = TRUE;
                }
            }

            if ($controleArray == FALSE) {
                $elementosDiferentesForm [$i] = $selectResponsavel[$i];
            } elseif ($controleArray == TRUE) {
                $controleArray = FALSE;
            }
        }
        
        for ($i = 0;$i < count($elementosDiferentesForm);$i++){
            
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
                $ocorrencia = "Inseriu Responsável por RACAP: " . $sequencial." - " . $nomeUsuario;
                $ip = get_client_ip_env();
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
                $sql = mysqli_query($conexao, $query);

                //$racapMensagem = "RACAP incluída com sucesso.";
            }
        }
    }
}


/* if ($_FILES['anexoRacap'] && ($racapMensagem == "RACAP incluída com sucesso." 
  || $racapMensagem == "RACAP alterada com sucesso.")) {
  //$nome_final = $_FILES['anexoRacap']['name'];
  //echo $nome_final."<br/>";
  $pasta = "uploads/";
  $anexoMensagem = anexaArquivo();
  $anexoMensagemCount = count($anexoMensagem);

  if ($anexoMensagemCount == 2){
  $nome_final = $anexoMensagem [1];
  }
  } else {
  $anexoMensagem [0] = "Arquivo não foi enviado ao servidor.";
  }

  if ($anexoMensagem [0] == "Upload efetuado com sucesso!"){
  if ($sequencial == "0") {
  $query = "SELECT MAX(id) AS id FROM racap_racap;";
  $sql = mysqli_query($conexao, $query);
  $row = mysqli_fetch_assoc($sql);

  if ($sql) {
  $sequencial = $row['id'];
  // echo "Max Sequencial: ".$sequencial."<br/>";
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
  } elseif ($sequencial != 0) {
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
  } */

//$alertMessage = $racapMensagem."\\n".$anexoMensagem [0];
$alertMessage = $racapMensagem;

echo '<script type="text/javascript">alert("' . $alertMessage . '");</script>';

$urlBack = "<script>voltar ();</script>";
echo $urlBack;
