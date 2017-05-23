<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'racap.php';
    }
</script>

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");
include "conecta_banco.inc";
include "getIP.php";
include 'racap_anexo.php';

if (isset($_POST['excluiAnexo'])) {

    $sequencial = $_POST['excluiAnexo'];

    $query = "SELECT * FROM racap_anexo WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) == 1) {
        $id = $row ['id'];
        $idRacap = $row ['id_racap'];
        $nomeArquivo = $row ['nome_arquivo'];
        $url = $row ['url'];

        $query = "SELECT id, motivo_racap FROM racap_racap WHERE id = '$idRacap'";
        $sql = mysqli_query($conexao, $query);
        $row = mysqli_fetch_assoc($sql);
        $ocorrencia = "Deletou anexo da RACAP " . $row['id'] . " - " . $row['motivo_racap'] . " - " . $nomeArquivo;

        $query = "DELETE FROM racap_anexo WHERE id = '$id'";
        $sql = mysqli_query($conexao, $query);

        if ($sql) {
            $login = $_SESSION ['nomeUsuario'];
            $dataRegistro = date("Y-m-d H:i:s");
            $ip = get_client_ip_env();
            $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
                            VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
            $sql = mysqli_query($conexao, $query);

            if (file_exists($url)) {
                unlink($url);
                $mensagem = "Anexo Excluído com sucesso.";
            }
        } else {
            $mensagem = "Falha na exclusão do anexo.\\nFavor contatar o Administrador do Sistema.";
        }
    }
} else {
    $mensagem = "Nenhum anexo foi selecionado.\\nFavor selecionar um anexo para exclusão.";
}

echo '<script type="text/javascript">alert("'.$mensagem.'");</script>';

$urlBack = "<script>voltar ();</script>";
echo $urlBack;