<?php
session_start();
date_default_timezone_set('Brazil/East');
include "conecta_banco.inc";
include "getIP.php";

//$login = $_SESSION ['nomeUsuario'];
$dataRegistro = date ("Y-m-d H:i:s");
$ocorrencia = utf8_encode("Saiu do Sistema.");
$ip = get_client_ip_env();
/*$query = "INSERT INTO glosa_log (id, dataRegistro, ocorrencia, usuario, ip) 
VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
$sql = mysqli_query ($conexao, $query);*/

session_destroy ();
//mysqli_close($conexao);
header ("location: login.php");
?>