<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 
function checarStatus ($conexao){
 $idRacap = $_POST['idRacap'];
 
 $query = "SELECT * FROM racap_acao WHERE status_acao < 3 AND id_racap = '$idRacap'";
 $sql = mysqli_query($conexao, $query);
 
 if (mysqli_affected_rows($conexao) == 0){
     $query = "UPDATE racap_racap SET status_racap = '3' WHERE id = '$idRacap'";
     $sql = mysqli_query($conexao, $query);
     
     $message = "Todas as Ações Foram Executadas ou Canceladas. Já é possível Fechar a RACAP.";
 } else {
     $message = "Ainda existem Ações para serem Encerradas ou Canceladas.";
 }
 
 return $message;
 
}