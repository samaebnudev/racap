<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";

$userDados = array('success' => false,
    'tableData' => ""
);

$tableBuffer = "";
$tableButtonDown = "<input type='button' value='Download'/>";
$tableButtonDel = "<input type='button' value='Excluir Anexo'/>";
$tableCheckStart = "<input type='radio' name='excluiAnexo' value='";
$tableCheckEnd = "' required/>";
$tableCheckEnd2 = "' />";


if (isset($_POST['selectbuscaBanco'])) {
    $sequencial = $_POST['selectbuscaBanco'];

    $query = "SELECT * FROM racap_anexo WHERE id_racap = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $userDados ['success'] = true;
        $id = $row['id'];
        $nomeArquivo = $row['nome_arquivo'];
        $url = $row['url'];

        /* $tableBuffer = "<tr><td>" . $nomeArquivo . "</td><td><a target='_blank'
          href='" . $url . "'>" . $tableButtonDown . "</a></td><td>" . $tableButtonDel . "</td>
          </tr>"; */

        $tableBuffer = "<tr><td class='reportTableInfo'>".$tableCheckStart.$id.$tableCheckEnd."</td><td class='reportTableInfo'>"
        .$nomeArquivo."</td><td class='reportTableInfo'><a target='_blank' href='".$url."'>".$tableButtonDown."</a></td></tr>";

        $userDados['tableData'] = $userDados['tableData'] . $tableBuffer;

        while ($row = mysqli_fetch_array($sql)) {
            $id = $row['id'];
            $nomeArquivo = $row['nome_arquivo'];
            $url = $row['url'];

            /* $tableBuffer = "<tr><td>" . $nomeArquivo . "</td><td><a target='_blank'
              href='" . $url . "'>" . $tableButtonDown . "</a></td><td>" . $tableButtonDel . "</td>
              </tr>"; */

            $tableBuffer = "<tr><td class='reportTableInfo'>".$tableCheckStart.$id.$tableCheckEnd2."</td><td class='reportTableInfo'>"
            .$nomeArquivo."</td><td class='reportTableInfo'><a target='_blank' href='".$url."'>".$tableButtonDown."</a></td></tr>";

            $userDados['tableData'] = $userDados['tableData'] . $tableBuffer;
        }
    }
}
echo json_encode($userDados);
