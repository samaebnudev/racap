 <?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'id'=>"",
'descricao' => ""
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];

	$query = "SELECT * FROM racap_tipo_racap WHERE id = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){

		$userDados ['success'] = true;
		$userDados ['id'] = (string) $row ['id'];
		$userDados ['descricao'] = (string) $row ['descricao'];
	}
}

echo json_encode($userDados);
?>