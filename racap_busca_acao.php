<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";

$userDados = array('success' => false,
    'id' => "",
    'id_racap' => "",
    'status_acao' => "",
    'descricao_acao' => "",
    'responsavel_acao' => "",
    'descricao_acao' => "",
    'prazo' => ""
);

if (isset($_POST['selectAcaoRacap'])) {
    $sequencial = $_POST['selectAcaoRacap'];

    $query = "SELECT * FROM racap_acao WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) == 1) {
        $userDados ['success'] = true;
        $userDados ['id'] = $row['id'];
        $userDados ['id_racap'] = $row['id_racap'];
        $userDados ['status_acao'] = $row['status_acao'];
        $userDados ['descricao_acao'] = $row['descricao_acao'];
        $userDados ['responsavel_acao'] = $row['responsavel_acao'];
        $userDados ['descricao_acao'] = $row['descricao_acao'];
        $userDados ['prazo'] = $row['prazo_acao'];
    }
}

echo json_encode($userDados);