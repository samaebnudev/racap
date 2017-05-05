<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os par�metros.
$userRel = $_GET['user'];
$comp = $_GET['comp'];

//Pega o nome de usu�rio que vai no t�tulo.
$query = "SELECT nomeUsuario FROM glosa_usuario WHERE id='$userRel'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);
$username = $row['nomeUsuario'];

//Pega a compet�ncia em aberto que vai no t�tulo.
$query = "SELECT mesCompetencia FROM glosa_competencia WHERE id = '$comp'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);
$mesCompetencia = date ('m/Y',strtotime($row['mesCompetencia']));

//Define o t�tulo do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Glosas do Usu�rio - ".$username.". Compet�ncia - ".$mesCompetencia."</h3>";
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
$query = "SELECT descRes, dataInicio, dataFim, duracao, qtd_ocorrencias, numTicket, procedente
FROM glosa_glosa, glosa_tipo_glosa WHERE usuario = '$userRel' AND competencia = $comp
AND glosa_glosa.tipoGlosa = glosa_tipo_glosa.id ORDER BY dataInicio";

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
		<th>Tipo de Glosa</th><th>Data Inicial</th><th>Data Final</th><th>Dura��o</th><th>Qtd. Ocorr�ncias</th><th>Ticket</th><th>Procede (S/N)</th>
		</tr>";
		$dataInicio = date ('d/m/Y H:i',strtotime($row['dataInicio']));
		
		if ($row['dataFim']){
			$dataFim = date ('d/m/Y H:i',strtotime($row['dataFim']));
		} else {
			$dataFim = null;
		}
		
		$descRes = utf8_decode($row['descRes']);
		echo "<tr>
		<td>".$descRes."</td><td>".$dataInicio."</td><td>".$dataFim."</td><td>".$row['duracao']."</td>
		<td>".$row['qtd_ocorrencias']."</td><td>".$row['numTicket']."</td><td>".$row['procedente']."</td>
		</tr>";
	
	while ($row = mysqli_fetch_array ($sql)){
		
		$dataInicio = date ('d/m/Y H:i',strtotime($row['dataInicio']));
		
		if ($row['dataFim']){
			$dataFim = date ('d/m/Y H:i',strtotime($row['dataFim']));
		} else {
			$dataFim = null;
		}
		
		
		$descRes = utf8_decode($row['descRes']);
		echo "<tr>
			<td>".$descRes."</td><td>".$dataInicio."</td><td>".$dataFim."</td><td>".$row['duracao']."</td>
			<td>".$row['qtd_ocorrencias']."</td><td>".$row['numTicket']."</td><td>".$row['procedente']."</td>
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
				<th>Tipo de Glosa</th><th>Data Inicial</th><th>Data Final</th><th>Dura��o</th><th>Qtd. Ocorr�ncias</th><th>Ticket</th><th>Procede (S/N)</th>
				</tr>";
			$lineCount = 2;
		}
		
	}
	//Fim da tabela.
	echo "</table>";
} 

//Caso n�o haja resultados a serem exibidos no relat�rio.
elseif (mysqli_affected_rows($conexao) == 0){
	echo "<p><h4>Este usu�rio n�o possui nenhuma Glosa cadastrada na compet�ncia atual.</h4></p>";
}	
//Fim do relat�rio.
echo $pageFooter;	
?>