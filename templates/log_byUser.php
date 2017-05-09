<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os par�metros.
$byUserNomeUsuario = $_GET['user'];

/*Pega o valor de $byUserNomeUsuario e pesquisa pelo nome do usu�rio
visto que em glosa_log n�o h� uma chave estrangeira para o id do usuario*/
$query = "SELECT nomeUsuario FROM glosa_usuario WHERE id='$byUserNomeUsuario'";
$sql = mysqli_query($conexao,$query);
$row = mysqli_fetch_array ($sql);
$nomeUsuario = utf8_decode($row['nomeUsuario']);

//Verifica qual dos par�metros foi enviado junto com o id de usu�rio.

// Se for o log por data.
if (isset ($_GET['dataIni'])){
	$byDateDataInicio = date ('Y-m-d 00:00:00', strtotime ($_GET['dataIni']));
	$byDateDataFim = date ('Y-m-d 23:59:59', strtotime ($_GET['dataFim']));
	
	//Define data e hora que aparecem no t�tulo
	$dataIni = date ('d/m/Y', strtotime ($_GET['dataIni']));
	$dataFim = date ('d/m/Y', strtotime ($_GET['dataFim']));
	
	//Query que ser� usada para montar a tabela mais abaixo
	$tableQuery = "SELECT * FROM glosa_log 
	WHERE usuario = '$nomeUsuario' AND dataRegistro BETWEEN '$byDateDataInicio' AND '$byDateDataFim'";
	
	//Se a query da tabela falhar ser� exibida uma mensagem
	$tableQueryFail = "<p><h4>N�o existe(m) atividade(s) do(a) usu�rio(a) no per�odo pesquisado.</h4></p>";
	
	//Define o t�tulo do Relat�rio
	$title = "<h3>Log do Usu�rio - ".$nomeUsuario.". De ".$dataIni." at� ".$dataFim.".</h3>";
} 

//Se for um n�mero X de entradas do usu�rio.
elseif (isset ($_GET['entries'])){
	$byUserLastEntries = $_GET['entries'];
	
	//Query que ser� usada para montar a tabela mais abaixo
	$tableQuery = "SELECT * FROM glosa_log 
	WHERE usuario = '$nomeUsuario' ORDER BY id DESC LIMIT $byUserLastEntries";
	
	//Se a query da tabela falhar ser� exibida uma mensagem
	$tableQueryFail = "<p><h4>N�o existe(m) atividade(s) do(a) usu�rio(a) no Sistema.</h4></p>";
	
	//Define o t�tulo do Relat�rio
	$title = "<h3>Log do Usu�rio - ".$nomeUsuario.". �ltimas ".$byUserLastEntries." entradas.</h3>";
}

//Define o t�tulo do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = $title;
$dataAtual = date ("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>".$dataAtual."</div>";

/*Contador de linhas � iniciado com valor 2.
Assim, ele conta o cabe�alho e a linha inicial da tabela.
Quando o contador chega a 25, ele pode ser reiniciado com valor 2
dependendo da situa��o.*/
$lineCount = 2;

//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report.css' />";

//Define o cabe�alho com t�tulo e data inclusos.
$pageHeader = "<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Servi�o Aut�nomo Municipal de �gua e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.".$reportTitle.$dateString."</p></div>
</page_header><br /><br /><div id='report'>";

//Define o rodap� da p�gina.
$pageFooter = "</div><page_footer><div id='samaeFooter'>P�gina [[page_cu]] de [[page_nb]]</div></page_footer></page>";

//Query para popular as tabelas do relat�rio.
$query = $tableQuery;

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relat�rio colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao) > 0){
		//Conta quantos resultados foram encontrados.
		$rowsAffected = mysqli_affected_rows ($conexao);
		
		//Come�a a escrever a tabela no documento.
		echo "<table>
		<tr>
		<th>ID</th><th>Data do Registro</th><th>Ocorr�ncia</th><th>Usu�rio</th><th>IP</th>
		</tr>";
		$dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
		$ocorrencia = utf8_decode ($row['ocorrencia']);
		$usuario = utf8_decode ($row['usuario']);
		
		echo "<tr>
		<td>".$row['id']."</td><td>".$dataRegistro."</td><td>".$ocorrencia."</td><td>".$usuario."</td>
		<td>".$row['ip']."</td>
		</tr>";
	
	while ($row = mysqli_fetch_array ($sql)){
		
		$dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
		$ocorrencia = utf8_decode ($row['ocorrencia']);
		$usuario = utf8_decode ($row['usuario']);
		
		echo "<tr>
		<td>".$row['id']."</td><td>".$dataRegistro."</td><td>".$ocorrencia."</td><td>".$usuario."</td>
		<td>".$row['ip']."</td>
		</tr>";
		
		//Conta o n�mero de linhas da tabela.
		$lineCount += 1;
		
		/*Se o contador de linhas chegar a 25 e o n�mero de resultados
		encontrados for maior que 24, ent�o ocorre uma quebra de p�gina
		e o contador de linhas � reiniciado.*/
		if ($lineCount == 25 && $rowsAffected > 24){
			echo "</table>";
			echo $pageFooter;
			echo $pageHeader;
			echo "<table>
				<tr>
				<th>ID</th><th>Data do Registro</th><th>Ocorr�ncia</th><th>Usu�rio</th><th>IP</th>
				</tr>";
			$lineCount = 2;
		}
		
	}
	//Fim da tabela.
	echo "</table>";
} 

//Caso n�o haja resultados a serem exibidos no relat�rio.
elseif (mysqli_affected_rows($conexao) == 0){
	echo $tableQueryFail;
}	
//Fim do relat�rio.
echo $pageFooter;	
?>