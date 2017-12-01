<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

$dataVenc = date('Y-m-d H:i:s', strtotime($_POST['dataVencimento']));
//$dataVencRep = date('d/m/Y', strtotime($_POST['dataVencimento']));

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Relação de RACAP's Vencidas</h3>";
/* $dataAtual = date("d/m/Y H:i:s");
  $dateString = "<div id='reportDate'>" . $dataAtual . "</div>"; */

$query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap, 
racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

WHERE racap_tipo_racap.id = racap_racap.tipo_racap
AND racap_setor.id = racap_racap.setor_racap
AND racap_status_racap.id = racap_racap.status_racap
AND (status_racap = '2' OR status_racap = '3')
AND prazo_racap < '$dataVenc' ORDER BY racap_racap.id";

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
} elseif (mysqli_affected_rows($conexao) == 0){
    $tabelaRACAPS = "<tr>"
            . "<td style='border-style: none;'>"
            ."<h2>Não há RACAP's no Sistema vencendo na data de hoje.</h2>"
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
                <?php echo $tabelaRACAPS;?>
            </table>
        </div>
    </body>
</html>

