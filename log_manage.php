<?php
session_start ();
date_default_timezone_set('Brazil/East');
include ('html2pdf/html2pdf.class.php');

$tipoRelatorio = $_POST ['criterioLog'];
//$relCompetenciaParam = $_POST['relCompetenciaParam'];

if ($tipoRelatorio == "byDate"){
	$byDateDataInicio = $_POST['byDateDataInicio'];
	$byDateDataFim = $_POST['byDateDataFim'];
	$dataAtual = date ('d-m-Y H-i-s');
	$fileName = "Sistema de RACAP's - Log por data - ".$dataAtual.".pdf";
	$url = "http://localhost/racap/templates/log_byDate.php?dataIni=".$byDateDataInicio."&dataFim=".$byDateDataFim;
}

if ($tipoRelatorio == "byEntrie"){
	$byEntrieQuantidade = $_POST['byEntrieQuantidade'];
	$dataAtual = date ('d-m-Y H-i-s');
	$fileName = "Sistema de RACAP's - Últimas Entradas - ".$dataAtual.".pdf";
	$url = "http://localhost/racap/templates/log_byEntrie.php?entries=".$byEntrieQuantidade;
}

if ($tipoRelatorio == "byUser"){
	$dataAtual = date ('d-m-Y H-i-s');
	$byUserNomeUsuario = $_POST['byUserNomeUsuario'];
	$byUserCriterio = $_POST ['byUserCriterio'];
	
	if ($byUserCriterio == "byUserData"){
		$byUserDataInicio = $_POST['byUserDataInicio'];
		$byUserDataFim = $_POST['byUserDataFim'];
		$url = "http://localhost/racap/templates/log_byUser.php?user=".$byUserNomeUsuario."&dataIni=".$byUserDataInicio."&dataFim=".$byUserDataFim;
	}
	
	if ($byUserCriterio == "byUserEntries"){
		$byUserLastEntries = $_POST['byUserLastEntries'];
		$url = "http://localhost/racap/templates/log_byUser.php?user=".$byUserNomeUsuario."&entries=".$byUserLastEntries;
	}
	
	$fileName = "Sistema de RACAP's - Atividades de Usu�rio - ".$dataAtual.".pdf";
}

if ($tipoRelatorio == "byLoginAttempt"){
	$byLoginAttempt = $_POST['byLoginAttempt'];
	$dataAtual = date ('d-m-Y H-i-s');
	$fileName = "Sistema de RACAP's - Tentativas de Login - ".$dataAtual.".pdf";
	
	if ($byLoginAttempt == "byLoginAttemptEntrie"){
		$loginAttemptNumber = $_POST ['loginAttemptNumber'];
		$url = "http://localhost/racap/templates/log_byLogin.php?attempts=".$loginAttemptNumber;
	}
	
	if ($byLoginAttempt == "byLoginAttemptDate"){
		$loginAttemptDateStart = $_POST['loginAttemptDateStart'];
		$loginAttemptDateEnd = $_POST['loginAttemptDateEnd'];
		$url = "http://localhost/racap/templates/log_byLogin.php?dataIni=".$loginAttemptDateStart."&dataFim=".$loginAttemptDateEnd;
	}
}

$template= file_get_contents ($url);

try {
	
	if ($tipoRelatorio != "glosa"){
		$html2pdf = new HTML2PDF('L', 'A4', 'pt', true, 'UTF-8', 2);
	} else {
		$html2pdf = new HTML2PDF('P', 'A4', 'pt', true, 'UTF-8', 2);
	}
	
	$html2pdf->writeHTML(utf8_encode($template));
	ob_end_clean();
	$html2pdf->Output($fileName, 'I');
} 

catch (HTML2PDF_exception $e){
	echo $e;
}
?>