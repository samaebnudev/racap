<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

/* $statusRacap = $_GET['statusRacap'];
  $dateBuffer = $_GET['dataIni'];
  $dataIni = date('Y-m-d 00:00:00', strtotime($dateBuffer));
  $dataIniRep = date('d/m/Y', strtotime($dateBuffer));

  $dateBuffer = $_GET['dataFim'];
  $dataFim = date('Y-m-d 23:59:59', strtotime($dateBuffer));
  $dataFimRep = date('d/m/Y', strtotime($dateBuffer)); */

$dataVenc = date('Y-m-d H:i:s', strtotime($_GET['dataVenc']));
$dataVencRep = date('d/m/Y', strtotime($_GET['dataVenc']));

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Relação de RACAP's Vencidas</h3>";
$dataAtual = date("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>" . $dataAtual . "</div>";

//Query para popular as tabelas do relatório.
$query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap, 
racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

WHERE racap_tipo_racap.id = racap_racap.tipo_racap
AND racap_setor.id = racap_racap.setor_racap
AND racap_status_racap.id = racap_racap.status_racap
AND prazo_racap < '$dataVenc' ORDER BY racap_racap.id";


$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

/* Contador de linhas é iniciado com valor 2.
  Assim, ele conta o cabeçalho e a linha inicial da tabela.
  Quando o contador chega a 25, ele pode ser reiniciado com valor 2
  dependendo da situação. */
$lineCount = 2;

//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report.css' />";

//Define o cabeçalho com título e data inclusos.
$pageHeader = "<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC." . $reportTitle . $dateString . "</p></div>
</page_header><br /><br /><div id='report'>";

//Define o rodapé da página.
$pageFooter = "</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>";

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo utf8_decode($pageHeader);

if (mysqli_affected_rows($conexao) > 0) {
    //Conta quantos resultados foram encontrados.
    $rowsAffected = mysqli_affected_rows($conexao);

    //Começa a escrever a tabela no documento.
    echo utf8_decode("<table>
		<tr>
		<th>ID</th><th>Aberta Em</th><th>Tipo</th><th>Motivo</th><th>Setor</th>
		<th>Status</th><th>Prazo</th></tr>");

    $id = $row['id'];
    $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
    $tipo = $row['tipo'];
    $motivo = $row['motivo_racap'];
    $setor = $row['nomeSetor'];
    $status = $row['status'];
    $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

    echo utf8_decode("<tr>
		<td>" . $id . "</td><td>" . $dataAbertura . "</td><td>" . $tipo . "</td><td>" . $motivo . "</td>
		<td>" . $setor . "</td><td>" . $status . "</td><td>" . $prazoRacap . "</td>
		</tr>");

    while ($row = mysqli_fetch_array($sql)) {

        $id = $row['id'];
        $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
        $tipo = $row['tipo'];
        $motivo = $row['motivo_racap'];
        $setor = $row['nomeSetor'];
        $status = $row['status'];
        $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

        echo utf8_decode("<tr>
		<td>" . $id . "</td><td>" . $dataAbertura . "</td><td>" . $tipo . "</td><td>" . $motivo . "</td>
		<td>" . $setor . "</td><td>" . $status . "</td><td>" . $prazoRacap . "</td>
		</tr>");

        //Conta o número de linhas da tabela.
        $lineCount += 1;

        /* Se o contador de linhas chegar a 25 e o número de resultados
          encontrados for maior que 24, então ocorre uma quebra de página
          e o contador de linhas é reiniciado. */
        if ($lineCount == 25 && $rowsAffected > 24) {
            echo "</table>";
            echo utf8_decode($pageFooter);
            echo utf8_decode($pageHeader);
            echo utf8_decode("<table>
		<tr>
		<th>ID</th><th>Aberta Em</th><th>Tipo</th><th>Motivo</th><th>Setor</th>
		<th>Status</th><th>Prazo</th></tr>");
            $lineCount = 2;
        }
    }
    //Fim da tabela.
    echo "</table>";
}

//Caso não haja resultados a serem exibidos no relatório.
elseif (mysqli_affected_rows($conexao) == 0) {
    echo utf8_decode("<p><h4>Não há RACAP's no Sistema vencendo hoje.</h4></p>");
}
//Fim do relatório.
echo utf8_decode($pageFooter);

