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
$dateString = "<div id='reportDate'>" . $dataAtual . "</div>";

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <!--<link rel='stylesheet' type='text/css' href='../css/report2.css' />-->
    </head>

    <body>
        <div id='logo'><img src='../img/logo_samae.png' /></div>
        <div id='samaeHeader'>
            <p>
                <h4>SAMAE - Serviço Autônomo Municipal de Água e Esgoto</h4>
                Rua Bahia 1530, CEP - 89031-001, Salto, Blumenau - SC.
                <?php echo $reportTitle . $dateString ;?>
            </p>
        </div>
    <br /><br />

    <div class='report'>
        <table class='reportTable'>
            <tr>
                <td class='reportTableHeader' >Nº da RACAP:</td><td class='reportTableInfo' colspan="3">113</td>
                <td class='reportTableHeader'>Título:</td><td class='reportTableInfo' colspan="3">Lorem Ipsum Dolor...</td>
            </tr>
            <tr>
                <td class='reportTableHeader'>Seção:</td><td class='reportTableInfo'>CPD</td>
                <td class='reportTableHeader'>Responsável(eis):</td><td class='reportTableInfo'>Wallace, Julimar</td>
                <td class='reportTableHeader'>Status:</td><td class='reportTableInfo'>Aberta</td>
                <td class='reportTableHeader'>Prazo da RACAP:</td><td class='reportTableInfo'>31/12/2017</td>
            </tr>
        </table>
    </div>
</body>

</html>