<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os parâmetros.
$glosaId = $_GET['glosaId'];

//Pega o tipo de glosa que vai no título.
$query = "SELECT glosa_glosa.id, descRes FROM glosa_glosa, glosa_tipo_glosa 
WHERE glosa_glosa.id = '$glosaId' AND glosa_glosa.tipoGlosa = glosa_tipo_glosa.id";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);
$descRes = $row['descRes'];

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Detalhes da Glosa. ID: ".$glosaId." - ".$descRes."</h3>";
$dataAtual = date ("d/m/Y H:i:s");
$dateString = "<div id='reportDate'>".$dataAtual."</div>";

/*Contador de linhas é iniciado com valor 2.
Assim, ele conta o cabeçalho e a linha inicial da tabela.
Quando o contador chega a 25, ele pode ser reiniciado com valor 2
dependendo da situação.*/
//$lineCount = 2;

//Define o CSS a ser usado para definir os estilos do PDF.
$css = "<link rel='stylesheet' type='text/css' href='css/report2.css' />";

//Define o cabeçalho com título e data inclusos.
$pageHeader = "<page>
<page_header>
<div id='logo'><img src='img/logo_samae.png' /></div>
<div id='samaeHeader'>
<p><h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.<br/><br/><br/>".$reportTitle.$dateString."</p></div>
</page_header><br /><br /><div id='report'>";
$pageHeader = utf8_decode ($pageHeader);

//Define o rodapé da página.
$pageFooter = "</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>";
$pageFooter = utf8_decode ($pageFooter);

//Query para popular as tabelas do relatório.
$query = "SELECT * FROM glosa_glosa WHERE id='$glosaId'";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao) == 1){
	
		//Conta quantos resultados foram encontrados.
		//Pega a descrição da competência;
		$competencia = $row['competencia'];
		$query2="SELECT mesCompetencia FROM glosa_competencia WHERE id = '$competencia'";
		$sql2 = mysqli_query($conexao,$query2);
		$row2 = mysqli_fetch_assoc($sql2);
		$competencia = date ('m/Y',strtotime($row2['mesCompetencia']));
		
		//Pega a descrição do Usuário que Cadastrou a Glosa.
		$usuario = $row['usuario'];
		$query3 = "SELECT nomeUsuario FROM glosa_usuario WHERE id='$usuario'";
		$sql3 = mysqli_query($conexao,$query3);
		$row3 = mysqli_fetch_assoc($sql3);
		$usuario = $row3['nomeUsuario'];
		
		$dataInicio = date ('d/m/Y H:i',strtotime($row['dataInicio']));
		
		if ($row['dataFim']){
			$dataFim = date ('d/m/Y H:i',strtotime($row['dataFim']));
		} else {
			$dataFim = null;
		}
		
		
		//Começa a escrever a tabela no documento.
		$firstLine = "<table>
		<tr>
		<th>Competência: </th><td>".$competencia."</td>
		<th>Data Inicial: </th><td>".$dataInicio."</td><th>Data Final: </th><td>".$dataFim."</td>
		</tr></table>";	
		$firstLine = utf8_decode($firstLine);
		echo $firstLine;
		
		$secondLine = "<table><tr>
		<th>Duração (hh:mm): </th><td>".$row['duracao']."</td><th>Procede (S/N): </th><td>".$row['procedente']."</td>
		<th>Usuário: </th><td>".$usuario."</td>
		</tr></table>";
		$secondLine = utf8_decode($secondLine);
		echo $secondLine;
		
		$thirdLine = "<table><tr>
		<th>Quantidade de Ocorrências: </th><td>".$row['qtd_ocorrencias']."</td>
		<th>Nº do Ticket: </th><td>".$row['numTicket']."</td>
		</tr></table>";
		$thirdLine = utf8_decode($thirdLine);
		echo $thirdLine;
		
		$fourthLine = "<table><tr>
		<th>Observações:</th>
		</tr>
		<tr><td>".nl2br($row['observacao'])."</td></tr>
		<tr><th>Conclusão: </th></tr>
		<tr><td>".nl2br($row['conclusao'])."</td></tr>";
		$fourthLine = utf8_decode($fourthLine);
		echo $fourthLine;
		
		/*$descRes = utf8_decode($row['descRes']);
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
	/*	
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
	*/
	//Fim da tabela.
	echo "</table>";
} 

//Fim do relatório.
echo $pageFooter;	
?>