<?php
session_start();
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Brazil/East');
include "../conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:../login.php");
}

$privilegio = $_SESSION['tipoPrivilegio'];
//$privilegio = 1;


//$sequencial = $_GET['sequencial'];
$sequencial = 113;

$reportTitle = "<p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>";
$dataAtual = date("d/m/Y H:i:s");
//$dateString = "<div id='reportDate'>" . $dataAtual . "</div>";

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel='stylesheet' type='text/css' href='../css/racapCapa.css' />
        <link rel="stylesheet" type="text/css" href="../css/indexTable.css"/>
    </head>

    <body>
        <div id='logo'><img src='../img/logo_samae.png' /></div>
        <div id='samaeHeader'>
                <h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
                <h5>Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.</h5>
                <?php /*echo $reportTitle;*/?>
                <p id='reportTitle'>Registro de Ações Corretivas, Preventivas e Melhorias</p>
            <br/><br/>
        </div>
    

    <div class='report'>
        <table class='reportTable'>
            <tr>
                <td class='reportTableHeader' >Nº:</td><td class='reportTableInfo'>113</td>
                <td class='reportTableHeader'>Título:</td><td class='reportTableInfo' colspan="5">Lorem Ipsum Dolor...</td>
            </tr>
            <tr>
                <td class='reportTableHeader'>Seção:</td><td class='reportTableInfo'>CPD</td>
                <td class='reportTableHeader'>Responsável(eis):</td><td class='reportTableInfo'>Wallace, Julimar</td>
                <td class='reportTableHeader'>Status:</td><td class='reportTableInfo'>Aberta</td>
                <td class='reportTableHeader'>Prazo:</td><td class='reportTableInfo'>31/12/2017 23:59</td>
            </tr>
            <tr>
                <td class='reportTableHeader' colspan="8">Descrição:</td>
            </tr>
            <tr>
                <td class='reportTableInfo' colspan="8">Lorem Impsum Dolor....</td>
            </tr>
            <tr>
                <td class='reportTableHeader' colspan="8">Ações da RACAP:</td>
            </tr>
            <tr>
                <td class='reportTableHeader' >Nº</td>
                <td class='reportTableHeader' colspan="2">Descrição</td>
                <td class='reportTableHeader' >Status</td>
                <td class='reportTableHeader' >Executada</td>
                <td class='reportTableHeader' >No Prazo</td>
                <td class='reportTableHeader' >Data Eficácia</td>
                <td class='reportTableHeader' >Eficiente</td>
            </tr>
            <tr>
                <td class='reportTableInfo'>1</td>
                <td class='reportTableInfo' colspan="2">Lorem Ipsum....</td>
                <td class='reportTableInfo'>Aberta</td>
                <td class='reportTableInfo' >22/12/2017 15:00</td>
                <td class='reportTableInfo'>Sim</td>
                <td class='reportTableInfo' >22/12/2017 15:00</td>
                <td class='reportTableInfo'>Sim</td>
            </tr>
        </table>
    </div>
</body>

</html>