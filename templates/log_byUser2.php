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
$byUserNomeUsuario = $_POST['byUserNomeUsuario'];
//$byUserNomeUsuario = 1;

/* Pega o valor de $byUserNomeUsuario e pesquisa pelo nome do usuário
  visto que em racap_log não há uma chave estrangeira para o id do usuario */
$query = "SELECT nomeServidor FROM racap_usuario WHERE id='$byUserNomeUsuario'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);
$nomeUsuario = utf8_decode($row['nomeServidor']);

$byUserCriterio = $_POST['byUserCriterio'];

//Verifica qual dos parâmetros foi enviado junto com o id de usuário.
// Se for o log por data.
if ($byUserCriterio == "byUserData") {
    $byDateDataInicio = date('Y-m-d 00:00:00', strtotime($_POST['byUserDataInicio']));
    $byDateDataFim = date('Y-m-d 23:59:59', strtotime($_POST['byUserDataFim']));

    //Define data e hora que aparecem no título
    $dataIni = date('d/m/Y', strtotime($_POST['byUserDataInicio']));
    $dataFim = date('d/m/Y', strtotime($_POST['byUserDataFim']));

    //Query que será usada para montar a tabela mais abaixo
    $tableQuery = "SELECT * FROM racap_log 
	WHERE usuario = '$nomeUsuario' AND dataRegistro BETWEEN '$byDateDataInicio' AND '$byDateDataFim'";

    //Se a query da tabela falhar será exibida uma mensagem
    $tableQueryFail = "<tr><td style='border-style: none;'><h2>Não existe(m) atividade(s) do(a) usuário(a) no período pesquisado.</h2></td></tr>";

    //Define o título do Relatório
    $title = "<h3>Log do Usuário - " . $nomeUsuario . ". De " . $dataIni . " até " . $dataFim . ".</h3>";
}

//Se for um número X de entradas do usuário.
elseif ($byUserCriterio == "byUserEntries") {
    $byUserLastEntries = $_POST['byUserLastEntries'];

    //Query que será usada para montar a tabela mais abaixo
    $tableQuery = "SELECT * FROM racap_log 
	WHERE usuario = '$nomeUsuario' ORDER BY id DESC LIMIT $byUserLastEntries";

    //Se a query da tabela falhar será exibida uma mensagem
    $tableQueryFail = "<tr><td style='border-style: none;'><h2>Não existe(m) atividade(s) do(a) usuário(a) no Sistema.</h2></td></tr>";

    //Define o título do Relatório
    $title = "<h3>Log do Usuário - " . $nomeUsuario . ". Últimas " . $byUserLastEntries . " entradas.</h3>";
}

//Define o título do PDF, a data atual e o contador de linhas da tabela.
$reportTitle = $title;

//Query para popular as tabelas do relatório.
$query = $tableQuery;

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
    $dataRegistro = date('d/m/Y H:i:s', strtotime($row['dataRegistro']));
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
        $dataRegistro = date('d/m/Y H:i:s', strtotime($row['dataRegistro']));
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
    $tabelaLog = $tableQueryFail;
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