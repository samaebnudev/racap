<?php
session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os par�metros.
$byEntrieQuantidade = $_GET['entries'];

//Define o t�tulo do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Log do Sistema. Últimas ".$byEntrieQuantidade." Entradas</h3>";
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
$query = "SELECT * FROM racap_log ORDER BY id DESC LIMIT $byEntrieQuantidade";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo utf8_decode($pageHeader);

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
		$ocorrencia = $row['ocorrencia'];
		$usuario = $row['usuario'];
		
		echo utf8_decode("<tr>
		<td>".$row['id']."</td><td>".$dataRegistro."</td><td>".$ocorrencia."</td><td>".$usuario."</td>
		<td>".$row['ip']."</td>
		</tr>") ;
		
		//Conta o número de linhas da tabela.
		$lineCount += 1;
		
		/*Se o contador de linhas chegar a 25 e o número de resultados
		encontrados for maior que 24, então ocorre uma quebra de p�gina
		e o contador de linhas é reiniciado.*/
		if ($lineCount == 25 && $rowsAffected > 24){
			echo "</table>";
			echo utf8_decode($pageFooter);
			echo utf8_decode($pageHeader);
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
	echo "<p><h4>Não existe registro de atividade no sistema no período pesquisado.</h4></p>";
}	
//Fim do relatório.
echo utf8_decode($pageFooter);	
?>