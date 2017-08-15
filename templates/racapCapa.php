<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

$sequencial = $_GET['sequencial'];
//$sequencial = 113;

$reportTitle = "<p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>";
$dataAtual = date("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>" . $dataAtual . "</div>";

/* Contador de linhas é iniciado com valor 9.
  Assim, ele conta o cabeçalho e a linha inicial da tabela.
  Quando o contador chega a 45, ele pode ser reiniciado com valor 9
  dependendo da situação. */
$lineCount = 9;
//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report2.css' />";

//Define o cabeçalho com título e data inclusos.
$pageHeader = "<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.<br/><br/>" . $reportTitle . $dateString . "</p></div>
</page_header><br /><br /><div class='report'>";
$pageHeader = utf8_decode($pageHeader);

//Define o rodapé da página.
$pageFooter = "</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>";
$pageFooter = utf8_decode($pageFooter);

$query = "SELECT racap_racap.id, data_racap, motivo_racap, historico_racap, prazo_racap, racap_setor.nomesetor AS 'setor',
racap_tipo_racap.descricao AS 'tipo', racap_motivo_abertura.descricao AS 'motivoAbertura', racap_causa.descricao AS 'causa',
racap_status_racap.descricao AS 'status'
FROM racap_racap, racap_tipo_racap, racap_motivo_abertura, racap_causa, racap_setor, racap_status_racap
WHERE racap_racap.id = '$sequencial' AND tipo_racap = racap_tipo_racap.id
AND motivo_abertura_racap_id = racap_motivo_abertura.id AND causa_racap = racap_causa.id
AND setor_racap = racap_setor.id AND status_racap = racap_status_racap.id";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao) == 1) {
    $numero = $row['id'];
    $dataRacap = date('d/m/Y', strtotime($row['data_racap']));
    $motivoRacap = $row['motivo_racap'];
    $historicoRACAP = $row['historico_racap'];

    $contaQuebraLinhasHistorico = substr_count($historicoRACAP, "\n");

    $prazoRacap = date('d/m/Y', strtotime($row['prazo_racap']));
    $setorRacap = $row['setor'];
    $tipoRacap = $row['tipo'];
    $motivoAbertura = $row['motivoAbertura'];
    $causaRacap = $row['causa'];
    $status = $row['status'];

    echo "<table class='reportTable'><tr>";
    echo utf8_decode("<td class='reportTableHeader'>RACAP:</td><td class='reportTableInfo'>" . $numero . "</td>");
    echo "<td class='reportTableHeader'>Data de Abertura:</td><td class='reportTableInfo'>" . $dataRacap . "</td>";
    echo utf8_decode("<td class='reportTableHeader'>Tipo da RACAP:</td><td class='reportTableInfo'>" . $tipoRacap . "</td>");
    echo utf8_decode("<td class='reportTableHeader'>Motivo da Abertura:</td><td class='reportTableInfo'>" . $motivoAbertura . "</td>");
    echo "</tr>";
    echo "<tr>";
    echo utf8_decode("<td class='reportTableHeader'>Descrição:</td><td class='reportTableInfo' colspan='7'>" . $motivoRacap . "</td>");
    echo "</tr>";
    echo "<tr>";
    echo utf8_decode("<td class='reportTableHeader'>Setor:</td><td class='reportTableInfo'>" . $setorRacap . "</td>");
    echo utf8_decode("<td class='reportTableHeader'>Status:</td><td class='reportTableInfo'>" . $status . "</td>");
    echo utf8_decode("<td class='reportTableHeader'>Prazo:</td><td class='reportTableInfo'>" . $prazoRacap . "</td>");
    echo utf8_decode("<td class='reportTableHeader'>Causa:</td><td class='reportTableInfo'>" . $causaRacap . "</td>");
    echo "</tr>";
    echo "</table>";
    echo "<br/>";
    /* Fechar a primeira tabela aqui. Começar tabelas separadas com Histórico e Ações */
    echo "<table class='reportTable'><tr>";

    /* Encapsular campo do Histórico e imprimir de acordo com o tamanho
     * Usar Explode e Implode respectivamente para separar e juntar strings
     * quando for necessário.
     * 
     * O campo Histórico poderá ser separado em duas páginas quando necessário.
     * Uma vez impresso o Histórico deve-se colocar as Ações da RACAP.
     */

    $lineBuffer = $lineCount + $contaQuebraLinhasHistorico;

    switch ($lineBuffer) {
        case $lineBuffer < 66:
            echo utf8_decode("<td class='reportTableHeader' style='width: 97%;'>Histórico da RACAP:</td>");
            echo "</tr>";
            echo "<tr>";
            echo utf8_decode("<td class='reportTableInfo' style='word-break: break-all; "
                    . "text-align: left; word-wrap: break-word; font-size: 12.5px;'>" . nl2br($historicoRACAP) . "</td>");
            echo "</tr>";
            $lineCount = $lineCount + $contaQuebraLinhasHistorico;
            break;

        case $lineBuffer == 66:
            echo utf8_decode("<td class='reportTableHeader' style='width: 97%;'>Histórico da RACAP:</td>");
            echo "</tr>";
            echo "<tr>";
            echo utf8_decode("<td class='reportTableInfo' style='word-break: break-all; "
                    . "text-align: left; word-wrap: break-word; font-size: 12.5px;'>" . nl2br($historicoRACAP) . "</td>");
            echo "</tr>";
            echo "</table>";

            echo $pageFooter;
            echo $pageHeader;

            echo "<table class='reportTable'>";
            $lineCount = 1;

            break;

        case $lineBuffer > 66:

            /* Separa a String do campo Histórico por Linhas em um array e
              grava o tamanho do array em uma variável */
            $historicoRacapArray = explode("\n", $historicoRACAP);
            $historicoRacapTamanho = sizeof($historicoRacapArray);

            /* Arrumar este trecho de código. */
            echo utf8_decode("<td class='reportTableHeader' style='width: 97%;'>Histórico da RACAP:</td>");
            echo "</tr>";
            $lineCount += 1;
            echo "<tr>";
            echo utf8_decode("<td class='reportTableInfo' style='word-break: break-all; "
                    . "text-align: left; word-wrap: break-word; font-size: 12.5px;'>");

            $arrayIndex = 0;
            $fimHistorico = false;

            while ($fimHistorico == false) {
                echo utf8_decode($historicoRacapArray[$arrayIndex]);
                echo "<br/>";
                $arrayIndex += 1;
                $lineCount += 1;

                if ($arrayIndex > $contaQuebraLinhasHistorico) {
                    $fimHistorico = true;
                    echo "</td></tr>";

                    if ($lineCount == 66) {
                        echo "</table>";
                        echo $pageFooter;
                        echo $pageHeader;
                        echo "<table class='reportTable'>";
                        $lineCount = 1;
                    } elseif ($lineCount < 66) {
                        echo "</table>";
                    }
                }

                if ($lineCount == 66 && $fimHistorico == false) {
                    echo "</td></tr></table>";
                    echo $pageFooter;
                    echo $pageHeader;
                    echo "<table class='reportTable'>";
                    echo "<tr>";
                    echo utf8_decode("<td class='reportTableHeader' style='width: 97%;'>Histórico da RACAP (cont.):</td>");
                    echo "</tr>";

                    $lineCount = 1;

                    echo "<tr>";
                    echo utf8_decode("<td class='reportTableInfo' style='word-break: break-all; "
                            . "text-align: left; word-wrap: break-word; font-size: 12.5px;'>");
                }
            }

            break;
    }


    $query = "SELECT racap_acao.id, racap_status_acao.descricao AS 'status', 
descricao_acao, racap_usuario.nomeServidor AS 'responsavel' 
FROM racap_acao, racap_usuario, racap_status_acao
WHERE id_racap = '$sequencial'
AND status_acao = racap_status_acao.id
AND responsavel_acao = racap_usuario.id
ORDER BY racap_acao.id";

    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {

        if ($lineCount + 6 == 66) {
            echo "</table>";
            echo $pageFooter;
            echo $pageHeader;
            echo "<table class='reportTable'>";
            $lineCount = 1;
        } else {
            echo "<br/>";
            echo "<table class='reportTable'>";
        }

        $fimAcao = false;
        $qtdAcoes = mysqli_affected_rows($conexao);
        $contaAcoes = 0;
        $idAcao = $row['id'];
        $statusAcao = $row['status'];
        $descricaoAcao = $row['descricao_acao'];
        $responsavelAcao = $row['responsavel'];

        echo "<tr>";
        echo utf8_decode("<td class='reportTableHeader' colspan='4'>Ações da RACAP</td>");
        echo "</tr><tr>";
        echo utf8_decode("<td class='reportTableHeader' style='width: 5%;'>Nº</td>");
        echo utf8_decode("<td class='reportTableHeader' style='width: 12%;'>Status</td>");
        echo utf8_decode("<td class='reportTableHeader'  style='width: 45%;'>Descrição</td>");
        echo utf8_decode("<td class='reportTableHeader' style:'width: 35%;'>Responsável</td>");
        echo "</tr>";
        echo "<tr>";
        echo utf8_decode("<td class='reportTableInfo' style='width: 5%;'>" . $idAcao . "</td>");
        echo utf8_decode("<td class='reportTableInfo' style='width: 12%;'>" . $statusAcao . "</td>");
        echo utf8_decode("<td class='reportTableInfo' style='width: 45%; word-break: break-all; word-wrap: break-word; font-size: 12.5px;'>" . $descricaoAcao . "</td>");
        echo utf8_decode("<td class='reportTableInfo' style='width: 35%;'>" . nl2br($responsavelAcao) . "</td>");
        echo "</tr>";

        $contaAcoes += 1;
        $lineCount += 5;

        if ($contaAcoes == $qtdAcoes) {
            $fimAcao = true;
        }

        if ($lineCount == 45 && $fimAcao == false) {
            echo "</table>";
            echo $pageFooter;
            echo $pageHeader;
            echo "<table class='reportTable'>";
            $lineCount = 5;

            echo "<tr>";
            echo utf8_decode("<td class='reportTableHeader' colspan='4'>Ações da RACAP</td>");
            echo "</tr><tr>";
            echo utf8_decode("<td class='reportTableHeader' style='width: 5%;'>Nº</td>");
            echo utf8_decode("<td class='reportTableHeader' style='width: 12%;'>Status</td>");
            echo utf8_decode("<td class='reportTableHeader'  style='width: 45%;'>Descrição</td>");
            echo utf8_decode("<td class='reportTableHeader' style:'width: 35%;'>Responsável</td>");
            echo "</tr>";
        }

        while ($row = mysqli_fetch_array($sql)) {
            $idAcao = $row['id'];
            $statusAcao = $row['status'];
            $descricaoAcao = $row['descricao_acao'];
            $responsavelAcao = $row['responsavel'];

            echo "<tr>";
            echo utf8_decode("<td class='reportTableInfo' style='width: 5%;'>" . $idAcao . "</td>");
            echo utf8_decode("<td class='reportTableInfo' style='width: 12%;'>" . $statusAcao . "</td>");
            echo utf8_decode("<td class='reportTableInfo' style='width: 45%; word-break: break-all; word-wrap: break-word; font-size: 12.5px;'>" . $descricaoAcao . "</td>");
            echo utf8_decode("<td class='reportTableInfo' style='width: 35%;'>" . $responsavelAcao . "</td>");
            echo "</tr>";

            $lineCount += 1;
            $contaAcoes += 1;

            if ($contaAcoes == $qtdAcoes) {
                $fimAcao = true;
            }

            if ($lineCount == 45 && $fimAcao == false) {
                echo "</table>";
                echo $pageFooter;
                echo $pageHeader;
                echo "<table class='reportTable'>";
                $lineCount = 5;

                echo "<tr>";
                echo utf8_decode("<td class='reportTableHeader' colspan='4'>Ações da RACAP</td>");
                echo "</tr><tr>";
                echo utf8_decode("<td class='reportTableHeader' style='width: 5%;'>Nº</td>");
                echo utf8_decode("<td class='reportTableHeader' style='width: 12%;'>Status</td>");
                echo utf8_decode("<td class='reportTableHeader'  style='width: 45%;'>Descrição</td>");
                echo utf8_decode("<td class='reportTableHeader' style:'width: 35%;'>Responsável</td>");
                echo "</tr>";
            }
        }

        echo "</table>";
    } elseif (mysqli_affected_rows($conexao) == 0) {
        echo "<tr>";
        echo utf8_decode("<td class='reportTableHeader' colspan='4'>Ações da RACAP</td>");
        echo "</tr><tr>";
        echo utf8_decode("<td class='reportTableInfo' >Não existem Ações para esta RACAP.</td>");
        echo "</tr>";
        echo "</table>";
    }
} else {
    echo utf8_decode("RACAP não encontrada.");
}

echo $pageFooter;
