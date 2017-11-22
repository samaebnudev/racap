<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";

$userDados = array ('success' => false);

if (isset($_POST['idRacap'])){
	$sequencial = $_POST['idRacap'];
        $dataFechamento = $_POST['dataAcao'];

	$query = "SELECT * FROM racap_racap WHERE id = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){
            
            $dateBuffer = date_parse($dataFechamento);
            $prazoRacap = date_parse($row['prazo_racap']);
            
            if ($prazoRacap >= $dateBuffer){
                $userDados ['success'] = true;
            } else {
                $userDados ['success'] = false;
            }
	}
}

echo json_encode($userDados);

