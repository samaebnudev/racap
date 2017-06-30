<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Verifica qual dos parâmetros foi enviado

// Se for o log por data.
if (isset ($_GET['dataIni'])){
	$loginAttemptDateStart = date ('Y-m-d 00:00:00', strtotime ($_GET['dataIni']));
	$loginAttemptDateEnd = date ('Y-m-d 23:59:59', strtotime ($_GET['dataFim']));
	
	//Define data e hora que aparecem no título
	$dataIni = date ('d/m/Y', strtotime ($_GET['dataIni']));
	$dataFim = date ('d/m/Y', strtotime ($_GET['dataFim']));
	
	//Query que será usada para montar a tabela mais abaixo
	$tableQuery = "SELECT * FROM racap_log 
	WHERE ocorrencia LIKE 'Tentativa de login%' AND dataRegistro BETWEEN '$loginAttemptDateStart' AND '$loginAttemptDateEnd'";
	
	//Se a query da tabela falhar será exibida uma mensagem
	$tableQueryFail = utf8_decode("<p><h4>Não há tentativa(s) de Login no período pesquisado.</h4></p>");
	
	//Define o título do Relatório
	$title = "<h3>Tentativas de Login. De ".$dataIni." até ".$dataFim.".</h3>";
} 

//Se for um número X de tentativas de Login.
elseif (isset ($_GET['attempts'])){
	$loginAttemptNumber = $_GET['attempts'];
	
	//Query que será usada para montar a tabela mais abaixo
	$tableQuery = "SELECT * FROM racap_log 
	WHERE ocorrencia LIKE 'Tentativa de login%' ORDER BY id DESC LIMIT $loginAttemptNumber";
	
	//Se a query da tabela falhar será exibida uma mensagem
	$tableQueryFail = utf8_decode("<p><h4>Não há tentativa(s) de Login no sistema.</h4></p>");
	
	//Define o título do Relatório
	$title = "<h3>Tentativas de Login. Últimas ".$loginAttemptNumber." tentativas.</h3>";
}

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = $title;
$dataAtual = date ("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>".$dataAtual."</div>";

/*Contador de linhas é iniciado com valor 2.
Assim, ele conta o cabeçalho e a linha inicial da tabela.
Quando o contador chega a 25, ele pode ser reiniciado com valor 2
dependendo da situação.*/
$lineCount = 2;

//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report.css' />";

//Define o cabeçalho com ttulo e data inclusos.
$pageHeader = utf8_decode("<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.".$reportTitle.$dateString."</p></div>
</page_header><br /><br /><div id='report'>");

//Define o rodapé da página.
$pageFooter = utf8_decode("</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>");

//Query para popular as tabelas do relatório.
$query = $tableQuery;

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao) > 0){
		//Conta quantos resultados foram encontrados.
		$rowsAffected = mysqli_affected_rows ($conexao);
		
		//Começa a escrever a tabela no documento.
		echo utf8_decode("<table>
		<tr>
		<th>ID</th><th>Data do Registro</th><th>Ocorrência</th><th>Usuário</th><th>IP</th>
		</tr>");
		$dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
		$ocorrencia = $row['ocorrencia'];
		$usuario = $row['usuario'];
		
		echo utf8_decode("<tr>
		<td>".$row['id']."</td><td>".$dataRegistro."</td><td>".$ocorrencia."</td><td>".$usuario."</td>
		<td>".$row['ip']."</td>
		</tr>");
	
	while ($row = mysqli_fetch_array ($sql)){
		
		$dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
		$ocorrencia = utf8_decode ($row['ocorrencia']);
		$usuario = utf8_decode ($row['usuario']);
		
		echo "<tr>
		<td>".$row['id']."</td><td>".$dataRegistro."</td><td>".$ocorrencia."</td><td>".$usuario."</td>
		<td>".$row['ip']."</td>
		</tr>";
		
		//Conta o número de linhas da tabela.
		$lineCount += 1;
		
		/*Se o contador de linhas chegar a 25 e o n�mero de resultados
		encontrados for maior que 24, então ocorre uma quebra de página
		e o contador de linhas é reiniciado.*/
		if ($lineCount == 25 && $rowsAffected > 24){
			echo "</table>";
			echo $pageFooter;
			echo $pageHeader;
			echo "<table>
				<tr>
				<th>ID</th><th>Data do Registro</th><th>Ocorrência</th><th>Usuário</th><th>IP</th>
				</tr>";
			$lineCount = 2;
		}
		
	}
	//Fim da tabela.
	echo "</table>";
} 

//Caso não haja resultados a serem exibidos no relatório.
elseif (mysqli_affected_rows($conexao) == 0){
	echo $tableQueryFail;
}	
//Fim do relatório.
echo $pageFooter;	
?>