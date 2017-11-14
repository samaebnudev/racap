<?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'id'=>"",
'statusRacap' => "",
'tipoRacap' => "",
'motivoAbertura' => "",
'motivoDescricao' => "",
'setorRacap' => "",
'causaRacap' => "",
'prazoRacap' => "",
'historicoRACAP' => "",
'dataAbertura' => ""
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];

	$query = "SELECT * FROM racap_racap WHERE id = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){

		$userDados ['success'] = true;
		$userDados ['id'] = (string) $row ['id'];
		$userDados ['statusRacap'] = (string) $row ['status_racap'];
                $userDados ['tipoRacap'] = (string) $row ['tipo_racap'];
                $userDados ['motivoAbertura'] = (string) $row ['motivo_abertura_racap_id'];
                $userDados ['motivoDescricao'] = (string) $row ['motivo_racap'];
                $userDados ['setorRacap'] = (string) $row ['setor_racap'];
                $userDados ['causaRacap'] = (string) $row ['causa_racap'];
                $userDados ['prazoRacap'] = (string) $row ['prazo_racap'];
                $userDados ['historicoRACAP'] = (string) $row ['historico_racap'];
                $userDados ['dataAbertura'] = (string) $row ['data_racap'];
	}
}

echo json_encode($userDados);
?>