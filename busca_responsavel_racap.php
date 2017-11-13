 <?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'id_responsavel' => array()
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];
        $idResponsavel = array();
        
	$query = "SELECT * FROM racap_responsavel_racap WHERE id_racap = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) > 0){
		$userDados ['success'] = true;
                $idResponsavel[0] = $row['id_responsavel'];
                $i = 1;
                
                while ($row = mysqli_fetch_assoc($sql)){
                    $idResponsavel[$i] = $row['id_responsavel'];
                    $i++;
                }
                
                $userDados ['id_responsavel'] = $idResponsavel;
	}
}

echo json_encode($userDados);
?>