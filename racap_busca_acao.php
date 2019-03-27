<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "conecta_banco.inc";

$userDados = array('success' => false,
    'id' => "",
    'idRacap' => "",
    'numeroAcao' => "",
    'tituloAcao' => "",
    'selectStatusAcao' => "",
    'prazoExecucao'=>"",
    'dataAcao' => "",
    'acaoPrazo' => "",
    'acaoEficiencia' => "",
    'dataEficiencia' => "",
    'descricaoAcao' => ""
);

if (isset($_POST['selectAcaoRacap'])) {
    $sequencial = $_POST['selectAcaoRacap'];
    //$sequencial = 1;
    //echo "Sequencial passado: ".$sequencial."<br/>";

    $query = "SELECT * FROM racap_acao WHERE id = '$sequencial'";
    $sql = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($sql);

    if (mysqli_affected_rows($conexao) == 1) {
        $userDados ['success'] = true;
        $userDados ['id'] = $row['id'];
        //echo "Sequencial : ".$userDados['id']."<br/>";
        $userDados ['idRacap'] = $row['id_racap'];
        //echo "ID da RACAP : ".$userDados['idRacap']."<br/>";
        $userDados ['numeroAcao'] = $row['id_acao'];
        //echo "Numero Amigavel da Acao : ".$userDados['numeroAcao']."<br/>";
        $userDados ['tituloAcao'] = $row['titulo_acao'];
        //echo "Titulo da Acao : ".$userDados['tituloAcao']."<br/>";
        $userDados ['selectStatusAcao'] = $row['status_acao'];
        //echo "Status da Acao : ".$userDados['selectStatusAcao']."<br/>";
        $userDados['prazoExecucao'] = $row['prazo_execucao'];
        $userDados ['dataAcao'] = $row['data_acao'];
        //echo "Data de Execucao : ".$userDados['dataAcao']."<br/>";
        $userDados ['acaoPrazo'] = $row['acao_no_prazo'];
        //echo "Acao no Prazo S/N : ".$userDados['acaoPrazo']."<br/>";
        $userDados ['acaoEficiencia'] = $row['acao_eficaz'];
        //echo "Acao Eficiente S/N : ".$userDados['acaoEficiencia']."<br/>";
        $userDados ['dataEficiencia'] = $row['data_eficacia'];
        //echo "Data da Eficacia : ".$userDados['dataEficiencia']."<br/>";
        $userDados['descricaoAcao'] = $row ['descricao_acao'];
        //echo "Descricao : ".$userDados['descricaoAcao']."<br/>";
    }
}

echo json_encode($userDados);