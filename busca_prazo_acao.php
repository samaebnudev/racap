<?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'prazo' => ""
);

if (isset($_POST['idRacap'])){
	$sequencial = $_POST['idRacap'];

	$query = "SELECT prazo_racap FROM racap_racap WHERE id = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){

		$userDados ['success'] = true;
                $userDados ['prazo'] = date('Y-m-d H:i', strtotime($row['prazo_racap']));
		$userDados ['prazo'] = (string) $row ['prazo_racap'];
	}
}

echo json_encode($userDados);
?>