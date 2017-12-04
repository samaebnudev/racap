<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

//Recebe os parâmetros.
$byEntrieQuantidade = $_POST['byEntrieQuantidade'];

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = "<h3>Log do Sistema. Últimas ".$byEntrieQuantidade." Entradas</h3>";

//Query para popular as tabelas do relatório.
$query = "SELECT * FROM racap_log ORDER BY id DESC LIMIT $byEntrieQuantidade";

$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) > 0) {

    $tabelaLog = "<tr>
        <th class='reportTableHeader'>ID</th>
        <th class='reportTableHeader'>Data do Registro</th>
        <th class='reportTableHeader'>Ocorrência</th>
        <th class='reportTableHeader'>Usuário</th>
        <th class='reportTableHeader'>IP</th>
	</tr>";

    $id = $row['id'];
    $dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
    $ocorrencia = $row['ocorrencia'];
    $usuario = $row['usuario'];
    $ip = $row['ip'];

    $buffer = "<tr>"
            . "<td class='reportTableInfo' style='width: 3%;'>" . $id . "</td>"
            . "<td class='reportTableInfo' style='width: 7%;'>" . $dataRegistro . "</td>"
            . "<td class='reportTableInfo' style='width: 30%;'>" . $ocorrencia . "</td>"
            . "<td class='reportTableInfo' style='width: 20%;'>" . $usuario . "</td>"
            . "<td class='reportTableInfo' style='width: 10%;'>" . $ip . "</td>"
            . "</tr>";

    $tabelaLog = $tabelaLog . $buffer;

    while ($row = mysqli_fetch_array($sql)) {
        $id = $row['id'];
    $dataRegistro = date ('d/m/Y H:i:s',strtotime($row['dataRegistro']));
    $ocorrencia = $row['ocorrencia'];
    $usuario = $row['usuario'];
    $ip = $row['ip'];

    $buffer = "<tr>"
            . "<td class='reportTableInfo' style='width: 3%;'>" . $id . "</td>"
            . "<td class='reportTableInfo' style='width: 7%;'>" . $dataRegistro . "</td>"
            . "<td class='reportTableInfo' style='width: 30%;'>" . $ocorrencia . "</td>"
            . "<td class='reportTableInfo' style='width: 20%;'>" . $usuario . "</td>"
            . "<td class='reportTableInfo' style='width: 10%;'>" . $ip . "</td>"
            . "</tr>";

        $tabelaLog = $tabelaLog . $buffer;
    }
} elseif (mysqli_affected_rows($conexao) == 0) {
    $tabelaLog = "<tr>"
            . "<td style='border-style: none;'>"
            . "<h2>Não existe registro de atividade no sistema no período pesquisado.</h2>"
            . "</td>"
            . "</tr>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet' type='text/css' href='../css/racapReport.css' />
        <link rel="stylesheet" type="text/css" href="../css/indexTable.css"/>
    </head>

    <body>
        <div id='logo'><img src='../img/logo_samae.png' /></div>
        <div id='samaeHeader'>
            <h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
            <h5>Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.</h5>
            <p id='reportTitle'><?php echo $reportTitle; ?></p>
        </div>

        <div class='report'>
            <table class='reportTable'>
                <?php echo $tabelaLog; ?>
            </table>
        </div>
    </body>
</html>