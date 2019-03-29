<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function calculaPrazoRacap ($conexao){
    $idRacap = $_POST['idRacap'];
 
    $query = "SELECT MAX(prazo_execucao) AS prazoRACAP FROM racap_acao WHERE id_racap = '$idRacap'";
    $sql = mysqli_query($conexao, $query);
    
    $row = mysqli_fetch_assoc($sql);
    $prazoRacap = $row['prazoRACAP'];

    $query = "UPDATE racap_racap SET prazo_racap ='$prazoRacap' WHERE id='$idRacap'";
    $sql = mysqli_query($conexao, $query);
}