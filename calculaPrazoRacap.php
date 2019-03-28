<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function calculaPrazoRacap ($conexao, $ip){
    $idRacap = $_POST['idRacap'];
 
    $query = "SELECT prazo_execucao FROM racap_acao WHERE id_racap = '$idRacap'";
    $sql = mysqli_query($conexao, $query);
    
    $row = mysqli_fetch_assoc($sql);
    $result = array();
    $result [0] = $row['prazo_execucao'];
    $i = 1;

    while ($row = mysqli_fetch_array($sql)) {
        $result[$i] = $row['prazo_execucao'];
        $i++;
    }
    
    if (mysqli_affected_rows($conexao) == 1) {
        $prazoRacap = $result[0];
        $query = "UPDATE racap_racap SET prazo_racap = '$prazoRacap' WHERE id = '$idRacap'";
        $sql = mysqli_query($conexao, $query);
        
        if ($sql){
            $login = $_SESSION ['nomeUsuario'];
            $dataRegistro = date("Y-m-d H:i:s");
            $ocorrencia = "Determinou prazo de RACAP: " . $idRacap;
            //$ip = get_client_ip_env();
            $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
                     VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
            $sql = mysqli_query($conexao, $query);
        }
    } elseif (mysqli_affected_rows($conexao) > 1) {
        $counter = mysqli_num_rows($sql);
    }
    
}