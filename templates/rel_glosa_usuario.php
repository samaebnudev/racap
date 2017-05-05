<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os parâmetros.
$userRel = $_GET['user'];
$comp = $_GET['comp'];

//Pega o nome de usuário que vai no título.
$query = "SELECT nomeUsuario FROM glosa_usuario WHERE id='$userRel'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);
$username = $row['nomeUsuario'];

//Pega a competência em aberto que vai no título.
$query = "SELECT mesCompetencia FROM glosa_competencia WHERE id = '$comp'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);
$mesCompetencia = date ('m/Y',strtotime($row['mesCompetencia']));

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Glosas do Usuário - ".$username.". Competência - ".$mesCompetencia."</h3>";
$dataAtual = date ("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>".$dataAtual."</div>";

/*Contador de linhas é iniciado com valor 2.
Assim, ele conta o cabeçalho e a linha inicial da tabela.
Quando o contador chega a 25, ele pode ser reiniciado com valor 2
dependendo da situação.*/
$lineCount = 2;

//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report.css' />";

//Define o cabeçalho com título e data inclusos.
$pageHeader = "<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.".$reportTitle.$dateString."</p></div>
</page_header><br /><br /><div id='report'>";

//Define o rodapé da página.
$pageFooter = "</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>";

//Query para popular as tabelas do relatório.
$query = "SELECT descRes, dataInicio, dataFim, duracao, qtd_ocorrencias, numTicket, procedente
FROM glosa_glosa, glosa_tipo_glosa WHERE usuario = '$userRel' AND competencia = $comp
AND glosa_glosa.tipoGlosa = glosa_tipo_glosa.id ORDER BY dataInicio";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao) > 0){
		//Conta quantos resultados foram encontrados.
		$rowsAffected = mysqli_affected_rows ($conexao);
		
		//Começa a escrever a tabela no documento.
		echo "<table>
		<tr>
		<th>Tipo de Glosa</th><th>Data Inicial</th><th>Data Final</th><th>Duração</th><th>Qtd. Ocorrências</th><th>Ticket</th><th>Procede (S/N)</th>
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
		
		//Conta o número de linhas da tabela.
		$lineCount += 1;
		
		/*Se o contador de linhas chegar a 25 e o número de resultados
		encontrados for maior que 24, então ocorre uma quebra de página
		e o contador de linhas é reiniciado.*/
		if ($lineCount == 25 && $rowsAffected > 24){
			echo "</table>";
			echo $pageFooter;
			echo $pageHeader;
			echo "<table>
				<tr>
				<th>Tipo de Glosa</th><th>Data Inicial</th><th>Data Final</th><th>Duração</th><th>Qtd. Ocorrências</th><th>Ticket</th><th>Procede (S/N)</th>
				</tr>";
			$lineCount = 2;
		}
		
	}
	//Fim da tabela.
	echo "</table>";
} 

//Caso não haja resultados a serem exibidos no relatório.
elseif (mysqli_affected_rows($conexao) == 0){
	echo "<p><h4>Este usuário não possui nenhuma Glosa cadastrada na competência atual.</h4></p>";
}	
//Fim do relatório.
echo $pageFooter;	
?>