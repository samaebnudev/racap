<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";
header("Content-type: text/html; charset=utf-8");

$userDados = array('success' => false,
    'tableData' => ""
);

$tableStart = "<tr><td class='reportTableHeader' colspan = '3'>Ações da RACAP</td></tr>";
$tableStartNoResults = "<tr><td class='reportTableHeader'>Ações da RACAP</td></tr>";
$tableFirstLineNoResults = "<tr><td class='reportTableInfo'>Nenhuma Ação para ser mostrada aqui.</td></tr>";
$tableFirstLineResults = "<tr><td class='reportTableHeader'>Editar</td><td class='reportTableHeader'>Ação</td><td class='reportTableHeader'>Status</td></tr>";

$tableRadioStart = "<input type='radio' name='radioAcaoRacap' class='radioAcao' onclick='selecionaAcao ()' value='";
$tableRadioEnd = "'/>";

if (isset($_POST['selectbuscaBanco'])) {
    $sequencial = $_POST['selectbuscaBanco'];
    //$sequencial = 1;

    $query = "SELECT racap_acao.id, id_acao, titulo_acao, descricao AS 'status' 
            FROM racap_acao, racap_status_acao WHERE id_racap = '$sequencial'
            AND status_acao = racap_status_acao.id;";

    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $userDados ['success'] = true;
        $id = $row['id'];
        $idAcao = $row['id_acao'];
        $tituloAcao = $row['titulo_acao'];
        $statusAcao = $row['status'];

        $buffer = $tableStart . $tableFirstLineResults;
        $userDados['tableData'] = $userDados['tableData'] . $buffer;

        $buffer = "<tr><td class='reportTableInfo'>" . $tableRadioStart . $id . $tableRadioEnd . "</td><td class='reportTableInfo'>" . $idAcao . " - " . $tituloAcao . "</td><td class='reportTableInfo'>" . $statusAcao . "</td></tr>";
        $userDados['tableData'] = $userDados['tableData'] . $buffer;

        while ($row = mysqli_fetch_array($sql)) {
            $id = $row['id'];
            $idAcao = $row['id_acao'];
            $tituloAcao = $row['titulo_acao'];
            $statusAcao = $row['status'];

            $buffer = "<tr><td class='reportTableInfo'>" . $tableRadioStart . $id . $tableRadioEnd . "</td><td class='reportTableInfo'>" . $idAcao . " - " . $tituloAcao . "</td><td class='reportTableInfo'>" . $statusAcao . "</td></tr>";
            $userDados['tableData'] = $userDados['tableData'] . $buffer;
        }
    } elseif (mysqli_affected_rows($conexao) == 0) {
      $buffer = $tableStartNoResults . $tableFirstLineNoResults;
      $userDados['tableData'] = $userDados['tableData'] . $buffer;
    }
}

echo json_encode($userDados);