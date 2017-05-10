<?php
include "conecta_banco.inc";

$userDados = array ('success' => false,
'id'=>"",
'matUsuario' => "",
'nomeUsuario' => "",
'setorUsuario' => "",
'perfilUsuario' => "",
'flgAtivo' => ""
);

if (isset($_POST['selectbuscaBanco'])){
	$sequencial = $_POST['selectbuscaBanco'];

	$query = "SELECT * FROM racap_usuario WHERE id = '$sequencial'";
	$sql = mysqli_query ($conexao, $query);
	$row = mysqli_fetch_assoc($sql);

	if (mysqli_affected_rows($conexao) == 1){

		$userDados ['success'] = true;
		$userDados ['id'] = (string) $row ['id'];
		$userDados ['matUsuario'] = (string) $row ['matServidor'];
                $userDados ['nomeUsuario'] = (string) $row ['nomeServidor'];
                $userDados ['setorUsuario'] = (string) $row ['setor'];
                $userDados ['perfilUsuario'] = (string) $row ['perfil_acesso'];
                $userDados ['flgAtivo'] = (string) $row ['flgAtivo'];
	}
}

echo json_encode($userDados);
?>