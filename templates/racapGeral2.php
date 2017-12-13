<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");
include "../conecta_banco.inc";

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Relação Geral de RACAP's</h3>";

if (isset($_POST['statusCheck'])) {
    $statusCheck = $_POST['statusCheck'];
} else {
    $statusCheck = "";
}

if (isset($_POST['checkPeriodo'])) {
    $checkPeriodo = $_POST['checkPeriodo'];
} else {
    $checkPeriodo = "";
}

if (isset($_POST['motivoAberturaCheck'])){
    $motivoAberturaCheck = $_POST['motivoAberturaCheck'];
} else {
    $motivoAberturaCheck = "";
}

if (isset($_POST['secaoCheck'])){
    $secaoCheck = $_POST['secaoCheck'];
} else {
    $secaoCheck = "";
}

$query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap,
  racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

  FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

  WHERE racap_tipo_racap.id = racap_racap.tipo_racap
  AND racap_setor.id = racap_racap.setor_racap
  AND racap_status_racap.id = racap_racap.status_racap";

if ($statusCheck != "") {
    $statusRacap = $_POST['statusRacap'];
    $queryStatus = " AND status_racap = '$statusRacap'";
    $query = $query . $queryStatus;
} else {
    $queryStatus = "";
}

if ($checkPeriodo != "") {
    $dateBuffer = $_POST['periodoRacapInicio'];
    $dataIni = date('Y-m-d 00:00:00', strtotime($dateBuffer));

    $dateBuffer = $_POST['periodoRacapFim'];
    $dataFim = date('Y-m-d 23:59:59', strtotime($dateBuffer));
    $queryPeriodo = " AND data_racap BETWEEN '$dataIni' AND '$dataFim'";
    $query = $query . $queryPeriodo;
} else {
    $queryPeriodo = "";
}

if ($motivoAberturaCheck != "") {
    $motivoRacap = $_POST['motivoRacap'];
    $queryMotivo = " AND motivo_abertura_racap_id = '$motivoRacap'";
    $query = $query . $queryMotivo;
} else {
    $queryMotivo = "";
}

if ($secaoCheck != "") {
    $setorRacap = $_POST['setorRacap'];
    $querySecao = " AND setor_racap = '$setorRacap'";
    $query = $query . $querySecao;
} else {
    $querySecao = "";
}

$query = $query." ORDER BY racap_racap.id";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) > 0) {

    $tabelaRACAPS = "<tr>
		<th class='reportTableHeader' style='width: 3%;'>Nº</th>
                <th class='reportTableHeader' style='width: 7%;'>Aberta Em</th>
                <th class='reportTableHeader' style='width: 5%;'>Tipo</th>
                <th class='reportTableHeader' style='width: 20%;'>Título</th>
                <th class='reportTableHeader' style='width: 15%;'>Seção</th>
		<th class='reportTableHeader' style='width: 5%;'>Status</th>
                <th class='reportTableHeader' style='width: 7%;'>Prazo</th>
                </tr>";

    $id = $row['id'];
    $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
    $tipo = $row['tipo'];
    $motivo = $row['motivo_racap'];
    $setor = $row['nomeSetor'];
    $status = $row['status'];
    $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

    $buffer = "<tr>"
            . "<td class='reportTableInfo' style='width: 3%;'>" . $id . "</td>"
            . "<td class='reportTableInfo' style='width: 7%;'>" . $dataAbertura . "</td>"
            . "<td class='reportTableInfo' style='width: 5%;'>" . $tipo . "</td>"
            . "<td class='reportTableInfo' style='width: 20%;'>" . $motivo . "</td>"
            . "<td class='reportTableInfo' style='width: 15%;'>" . $setor . "</td>"
            . "<td class='reportTableInfo' style='width: 5%;'>" . $status . "</td>"
            . "<td class='reportTableInfo' style='width: 7%;'>" . $prazoRacap . "</td>"
            . "</tr>";

    $tabelaRACAPS = $tabelaRACAPS . $buffer;

    while ($row = mysqli_fetch_array($sql)) {
        $id = $row['id'];
        $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
        $tipo = $row['tipo'];
        $motivo = $row['motivo_racap'];
        $setor = $row['nomeSetor'];
        $status = $row['status'];
        $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

        $buffer = "<tr>"
                . "<td class='reportTableInfo' style='width: 3%;'>" . $id . "</td>"
                . "<td class='reportTableInfo' style='width: 7%;'>" . $dataAbertura . "</td>"
                . "<td class='reportTableInfo' style='width: 5%;'>" . $tipo . "</td>"
                . "<td class='reportTableInfo' style='width: 20%;'>" . $motivo . "</td>"
                . "<td class='reportTableInfo' style='width: 15%;'>" . $setor . "</td>"
                . "<td class='reportTableInfo' style='width: 5%;'>" . $status . "</td>"
                . "<td class='reportTableInfo' style='width: 7%;'>" . $prazoRacap . "</td>"
                . "</tr>";

        $tabelaRACAPS = $tabelaRACAPS . $buffer;
    }
} elseif (mysqli_affected_rows($conexao) == 0) {
    $tabelaRACAPS = "<tr>"
            . "<td class='reportTableInfo'>"
            . "Não há RACAP's no Sistema com os parâmetros pesquisados."
            . "</td>"
            . "</tr>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet' type='text/css' href='../css/racapReport.css' />
        <link rel="stylesheet" type="text/css" href="../css/indexTable.css"/>
    </head>

    <body>
        <div id='logo'><img src='../img/logo_samae.png' /></div>
        <div id='samaeHeader'>
            <h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
            <h5>Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.</h5>
            <p id='reportTitle'><?php echo $reportTitle; ?></p>
        </div>

        <div class='report'>
            <table class='reportTable'>
                <?php echo $tabelaRACAPS; ?>
            </table>
        </div>
    </body>
</html>

