<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start ();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

$sequencial = $_GET['sequencial'];

$reportTitle = "<p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>";
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
Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.<br/><br/>".$reportTitle.$dateString."</p></div>
</page_header><br /><br /><div id='report'>";
$pageHeader = utf8_decode ($pageHeader);

//Define o rodapé da página.
$pageFooter = "</div><page_footer><div id='samaeFooter'>Página [[page_cu]] de [[page_nb]]</div></page_footer></page>";
$pageFooter = utf8_decode ($pageFooter);

$query = "SELECT racap_racap.id, data_racap, motivo_racap, historico_racap, prazo_racap, racap_setor.nomesetor AS 'setor',
racap_tipo_racap.descricao AS 'tipo', racap_motivo_abertura.descricao AS 'motivoAbertura', racap_causa.descricao AS 'causa'
FROM racap_racap, racap_tipo_racap, racap_motivo_abertura, racap_causa, racap_setor
WHERE racap_racap.id = '$sequencial' AND tipo_racap = racap_tipo_racap.id
AND motivo_abertura_racap_id = racap_motivo_abertura.id AND causa_racap = racap_causa.id
AND setor_racap = racap_setor.id";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc ($sql);

//Escreve o relatório colocando os resultados encontrados em uma tabela.
echo $css;
echo $pageHeader;

if (mysqli_affected_rows($conexao)== 1){
    $numero = "Nº: ".$row['id'];
    $dataRacap = date ('d/m/Y H:i',strtotime($row['data_racap']));
    $motivoRacap = $row['motivo_racap'];
    $historicoRACAP = $row['historico_racap'];
    $prazoRacap = date ('d/m/Y', strtotime($row['prazo_racap']));
    $setorRacap = $row['setor'];
    $tipoRacap = "Tipo da RACAP: ".$row['tipo'];
    $motivoAbertura = $row['motivoAbertura'];
    $causaRacap = $row['causa'];
    
    $divNumeroRacap = "<div id='numeroRacap'>".$numero."</div>";
    $divDataAbertura = "<div id='dataAbertura'>Aberta em: ".$dataRacap."</div>";
    $divMotivoAbertura = "<div id='motivoAbertura'>Motivo da Abertura: ".$motivoAbertura."</div>";
    $divMotivoRacap = "<div id='motivoDescricao'>Descrição :".$motivoRacap."</div>";
    $divSetor = "<div id='setorRacap'>Setor: ".$setorRacap."</div>";
    $divPrazoRacap = "<div id='prazoRacap'>Prazo: ".$prazoRacap."</div>";
    $divCausaRacap = "<div id='causaRacap'>Causa: ".$causaRacap."</div>";
    
    echo utf8_decode($divNumeroRacap.$divDataAbertura.$divMotivoAbertura);
    echo utf8_decode($divMotivoRacap);
    echo utf8_decode($divSetor.$divPrazoRacap.$divCausaRacap);
}else{
 echo utf8_decode("RACAP não encontrada.");   
}

echo $pageFooter;