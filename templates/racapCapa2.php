<?php
session_start();
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:../login.php");
}
$sequencial = $_POST['idRacap'];
//$sequencial = 128;

$reportTitle = "<p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>";
$dataAtual = date("d/m/Y H:i:s");
//$dateString = "<div id='reportDate'>" . $dataAtual . "</div>";

$query = "SELECT racap_racap.id, data_racap, motivo_racap, historico_racap, prazo_racap, racap_setor.nomesetor AS 'setor',
racap_tipo_racap.descricao AS 'tipo', racap_motivo_abertura.descricao AS 'motivoAbertura', racap_causa.descricao AS 'causa',
racap_status_racap.descricao AS 'status'
FROM racap_racap, racap_tipo_racap, racap_motivo_abertura, racap_causa, racap_setor, racap_status_racap
WHERE racap_racap.id = '$sequencial' AND tipo_racap = racap_tipo_racap.id
AND motivo_abertura_racap_id = racap_motivo_abertura.id AND causa_racap = racap_causa.id
AND setor_racap = racap_setor.id AND status_racap = racap_status_racap.id";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {

    $numero = $row['id'];
    $dataRacap = date('d/m/Y', strtotime($row['data_racap']));
    $motivoRacap = $row['motivo_racap'];
    
    if ($row['historico_racap'] == ""){
        $historicoRACAP = "Nenhuma descrição disponível para esta RACAP.";
    } else {
        $historicoRACAP = $row['historico_racap'];
    }
    
    $prazoRacap = date('d/m/Y', strtotime($row['prazo_racap']));
    $setorRacap = $row['setor'];
    $tipoRacap = $row['tipo'];
    $motivoAbertura = $row['motivoAbertura'];
    $causaRacap = $row['causa'];
    $status = $row['status'];

    $query = "SELECT id_racap, id_responsavel, nomeServidor "
            . "FROM racap_responsavel_racap, racap_usuario "
            . "WHERE id_racap = '$sequencial' "
            . "AND id_responsavel = racap_usuario.id";

    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        //$resultadosAchados = mysqli_affected_rows($conexao);
        $responsavelRacap = array();

        $responsavelRacap[0] = $row['nomeServidor'];
        $responsavelRacapTexto = "";
        $i = 0;
        $responsavelRacapTexto = $responsavelRacapTexto . $responsavelRacap[0];

        while ($row = mysqli_fetch_array($sql)) {
            $i = $i + 1;
            $responsavelRacap[$i] = $row['nomeServidor'];
            $buffer = ", " . $responsavelRacap[$i];
            $responsavelRacapTexto = $responsavelRacapTexto . $buffer;
        }
    }

    $query = "SELECT id_acao, racap_status_acao.descricao AS 'status', "
            . "titulo_acao, acao_no_prazo, data_acao, acao_eficaz, data_eficacia"
            . " FROM racap_acao, racap_status_acao "
            . " WHERE id_racap = '$sequencial'"
            . " AND status_acao = racap_status_acao.id";

    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $linhaAcoes = "<tr>
                    <td class='reportTableHeader' >Nº</td>
                    <td class='reportTableHeader' colspan='2'>Descrição</td>
                    <td class='reportTableHeader' >Status</td>
                    <td class='reportTableHeader' >Executada</td>
                    <td class='reportTableHeader' >No Prazo</td>
                    <td class='reportTableHeader' >Data Eficácia</td>
                    <td class='reportTableHeader' >Eficiente</td>
                </tr>";

        $idAcao = $row['id_acao'];
        $statusAcao = $row['status'];
        $tituloAcao = $row['titulo_acao'];

        if ($row['acao_no_prazo'] == 'S') {
            $acaoPrazo = "Sim";
        } elseif ($row['acao_no_prazo'] == 'N') {
            $acaoPrazo = "Não";
        } else {
            $acaoPrazo = "";
        }

        if ($row['data_acao'] != NULL) {
            $dataAcao = date('d/m/Y H:i', strtotime($row['data_acao']));
        } else {
            $dataAcao = "";
        }

        if ($row['acao_eficaz'] == 'S') {
            $acaoEficiencia = "Sim";
        } elseif ($row['acao_eficaz'] == 'N') {
            $acaoEficiencia = "Não";
        } else {
            $acaoEficiencia = "";
        }

        if ($row['data_eficacia'] != NULL) {
            $dataEficiencia = date('d/m/Y H:i', strtotime($row['data_eficacia']));
        } else {
            $dataEficiencia = "";
        }

        $buffer = "<tr>
                    <td class='reportTableInfo'>" . $idAcao . "</td>
                    <td class='reportTableInfo' colspan='2'>" . $tituloAcao . "</td>
                    <td class='reportTableInfo'>" . $statusAcao . "</td>
                    <td class='reportTableInfo' >" . $dataAcao . "</td>
                    <td class='reportTableInfo'>" . $acaoPrazo . "</td>
                    <td class='reportTableInfo' >" . $dataEficiencia . "</td>
                    <td class='reportTableInfo'>" . $acaoEficiencia . "</td>
                </tr>";

        $linhaAcoes = $linhaAcoes . $buffer;

        while ($row = mysqli_fetch_array($sql)) {
            $idAcao = $row['id_acao'];
            $statusAcao = $row['status'];
            $tituloAcao = $row['titulo_acao'];

            if ($row['acao_no_prazo'] == 'S') {
                $acaoPrazo = "Sim";
            } elseif ($row['acao_no_prazo'] == 'N') {
                $acaoPrazo = "Não";
            } else {
                $acaoPrazo = "";
            }

            if ($row['data_acao'] != NULL) {
                $dataAcao = date('d/m/Y H:i', strtotime($row['data_acao']));
            } else {
                $dataAcao = "";
            }

            if ($row['acao_eficaz'] == 'S') {
                $acaoEficiencia = "Sim";
            } elseif ($row['acao_eficaz'] == 'N') {
                $acaoEficiencia = "Não";
            } else {
                $acaoEficiencia = "";
            }

            if ($row['data_eficacia'] != NULL) {
                $dataEficiencia = date('d/m/Y H:i', strtotime($row['data_acao']));
            } else {
                $dataEficiencia = "";
            }

            $buffer = "<tr>
                    <td class='reportTableInfo'>" . $idAcao . "</td>
                    <td class='reportTableInfo' colspan='2'>" . $tituloAcao . "</td>
                    <td class='reportTableInfo'>" . $statusAcao . "</td>
                    <td class='reportTableInfo' >" . $dataAcao . "</td>
                    <td class='reportTableInfo'>" . $acaoPrazo . "</td>
                    <td class='reportTableInfo' >" . $dataEficiencia . "</td>
                    <td class='reportTableInfo'>" . $acaoEficiencia . "</td>
                </tr>";

            $linhaAcoes = $linhaAcoes . $buffer;
        }
    } else {
        $linhaAcoes = "<tr><td class='reportTableInfo' colspan='8'>Não existem Ações para esta RACAP.</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet' type='text/css' href='../css/racapCapa.css' />
        <link rel="stylesheet" type="text/css" href="../css/indexTable.css"/>
    </head>

    <body>
        <div id='logo'><img src='../img/logo_samae.png' /></div>
        <div id='samaeHeader'>
            <h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
            <h5>Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.</h5>
            <p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>
            <br/><br/>
        </div>


        <div class='report'>
            <table class='reportTable'>
                <tr>
                    <td class='reportTableHeader' >Nº:</td><td class='reportTableInfo'><?php echo $numero; ?></td>
                    <td class='reportTableHeader'>Título:</td><td class='reportTableInfo' colspan='5'><?php echo $motivoRacap; ?></td>
                </tr>
                <tr>
                    <td class='reportTableHeader'>Motivo de Abertura:</td><td class='reportTableInfo'><?php echo $motivoAbertura; ?></td>
                    <td class='reportTableHeader'>Tipo:</td><td class='reportTableInfo'><?php echo $tipoRacap ?></td>
                    <td class='reportTableHeader'>Causa:</td><td class='reportTableInfo'><?php echo $causaRacap; ?></td>
                    <td class='reportTableHeader'>Data de Abertura:</td><td class='reportTableInfo'><?php echo $dataRacap; ?></td>
                </tr>
                <tr>
                    <td class='reportTableHeader'>Seção:</td><td class='reportTableInfo' colspan="3"><?php echo $setorRacap; ?></td>
                    <td class='reportTableHeader'>Status:</td><td class='reportTableInfo'><?php echo $status; ?></td>
                    <td class='reportTableHeader'>Prazo:</td><td class='reportTableInfo'><?php echo $prazoRacap; ?></td>
                </tr>
                <tr>
                    <td class='reportTableHeader' colspan="8">Responsável(eis):</td>
                </tr>
                <tr>
                    <td class='reportTableInfo' colspan="8"><?php echo $responsavelRacapTexto ?></td>
                </tr>
                <tr>
                    <td class='reportTableHeader' colspan='8'>Descrição:</td>
                </tr>
                <tr>
                    <td class='reportTableInfo' colspan='8'><?php echo $historicoRACAP; ?></td>
                </tr>
                <tr>
                    <td class='reportTableHeader' colspan='8'>Ações da RACAP:</td>
                </tr>
                <?php echo $linhaAcoes; ?>
            </table>
        </div>
    </body>

</html>