<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
include ('html2pdf/html2pdf.class.php');

$tipoRelatorio = $_POST ['tipoRel'];

switch ($tipoRelatorio) {
    case "racapGeral":
        $statusRacap = $_POST['statusRacap'];
        $periodoRacapInicio = $_POST['periodoRacapInicio'];
        $periodoRacapFim = $_POST['periodoRacapFim'];

        $dataAtual = date('d-m-Y H-i-s');
        $fileName = "Sistema de RACAP's - Listagem - " . $dataAtual . ".pdf";
        $url = "http://localhost/racap/templates/racapGeral.php?statusRacap=" . $statusRacap . "&dataIni=" . $periodoRacapInicio . "&dataFim=" . $periodoRacapFim;
        break;
    case "racapVencida":
        $dataVencimento = $_POST['dataVencimento'];
        $dataAtual = date('d-m-Y H-i-s');
        $fileName = "Sistema de RACAP's - RACAP's Vencidas - " . $dataAtual . ".pdf";
        $url = "http://localhost/racap/templates/racapVencida.php?dataVenc=" . $dataVencimento;
        break;
    case "racapAVencer":
        $periodoRacapInicio = $_POST['dataHoje'];
        $dataLimite = $_POST['dataLimite'];
        $dataAtual = date('d-m-Y H-i-s');
        $fileName = "Sistema de RACAP's - RACAP's a Vencer - " . $dataAtual . ".pdf";
        $url = "http://localhost/racap/templates/racapAVencer.php?dataHoje=".$periodoRacapInicio."&dataLimite=".$dataLimite;
        break;
    case "racap":
        $idRacap = $_POST['idRacap'];
        $dataAtual = date('d-m-Y H-i-s');
        $fileName = "Detalhes da RACAP - ".$idRacap." - " . $dataAtual . ".pdf";
        $url = "http://localhost/racap/templates/racapCapa.php?sequencial=".$idRacap;
        break;
}

$template = file_get_contents($url);

try {
    if ($tipoRelatorio != "racap"){
		$html2pdf = new HTML2PDF('L', 'A4', 'pt', true, 'UTF-8', 2);
	} else {
		$html2pdf = new HTML2PDF('P', 'A4', 'pt', true, 'UTF-8', 2);
	}

        $html2pdf->writeHTML(utf8_encode($template));
        ob_end_clean();
        $html2pdf->Output($fileName, 'I');
} 

catch (HTML2PDF_exception $e) {
    echo $e;
}