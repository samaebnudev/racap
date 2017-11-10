 <?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'id_responsavel' => array()
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];

	$query = "SELECT * FROM racap_responsavel_racap WHERE id_racap = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){

		$userDados ['success'] = true;
		$userDados ['id_reponsavel'][0] = (string) $row ['id_responsavel'];
	}
}

echo json_encode($userDados);
?>