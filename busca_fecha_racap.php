<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";

$userDados = array ('success' => false,
'id'=>"",
'idRacap' => "",
'dataFechamento' => "",
'observacaoRACAP' => "",
'statusPos' => ""
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];

	$query = "SELECT * FROM racap_fecha_racap WHERE id_racap = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){
		$userDados ['success'] = true;
		$userDados ['id'] = (string) $row ['id'];
		$userDados ['idRacap'] = (string) $row ['id_racap'];
                $userDados ['dataFechamento'] = (string) $row['data_fechamento'];
                $userDados ['observacaoRACAP'] = (string) $row['observacao_racap'];
                $userDados ['statusPos'] = (string) $row['status_racap_pos'];
	}
}

echo json_encode($userDados);
