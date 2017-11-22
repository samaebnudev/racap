<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";
header("Content-type: text/html; charset=utf-8");

$userDados = array('success' => false,
    'selectData' => ""
);

$buffer = "";
$optionStart = "<option value='";
$optionEnd = "</option>";


if (isset($_POST['selectbuscaBanco'])) {
    $sequencial = $_POST['selectbuscaBanco'];
    //$sequencial = 1;

    $query = "SELECT id, id_acao, titulo_acao FROM racap_acao WHERE id_racap = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $userDados ['success'] = true;
        $id = $row['id'];
        $idAcao = $row['id_acao'];
        $tituloAcao = $row['titulo_acao'];
        
        //$buffer = $optionStart."'>".$optionEnd;
        $userDados ['selectData'] = $userDados ['selectData'] . $buffer;
        
        //$buffer = $optionStart . $id . "'>" . $tituloAcao . $optionEnd;
        $buffer = $optionStart . $id . "'>" .$idAcao." - ". $tituloAcao . $optionEnd;
        $userDados ['selectData'] = $userDados ['selectData'] . $buffer;

        while ($row = mysqli_fetch_array($sql)) {
            $id = $row['id'];
            $idAcao = $row['id_acao'];
            $tituloAcao = $row['titulo_acao'];

            //$buffer = $optionStart . $id . "'>" . $tituloAcao . $optionEnd;
            $buffer = $optionStart . $id . "'>" .$idAcao." - ". $tituloAcao . $optionEnd;
            $userDados ['selectData'] = $userDados ['selectData'] . $buffer;
        }
    }
}

echo json_encode($userDados);
   // echo $userDados['selectData'];