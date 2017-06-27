<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");

function anexaArquivo() {
//Array para o retorno do método
    $messageAnexo = [];
// Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = 'uploads/';
// Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 15; // 15Mb
// Array com as extensões permitidas
    $_UP['extensoes'] = array('jpg', 'png', 'gif', 'pdf', 'doc', 'zip', 'rar', '7z');
// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
    $_UP['renomeia'] = true;
// Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['anexoRacap']['error'] != 0) {
        $messageAnexo [0] = "Não foi possível fazer o upload, erro: " . $_UP['erros'][$_FILES['anexoRacap']['error']];
        return $messageAnexo; // Para a execução do script
    }
// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
// Faz a verificação da extensão do arquivo
    $file_nome = $_FILES['anexoRacap']['name'];
    $file_separator = explode('.', $file_nome);
    $extensao = strtolower(end($file_separator));

    if (array_search($extensao, $_UP['extensoes']) === false) {
        $messageAnexo [0] = "Por favor, envie arquivos com as seguintes extensões: jpg, png, gif, pdf, doc, zip, rar e 7z";
        return $messageAnexo;
    }
// Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['anexoRacap']['size']) {
        $messageAnexo [0] = "O arquivo enviado é muito grande, envie arquivos de até 15Mb.";
        return $messageAnexo;
    }
// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
// Primeiro verifica se deve trocar o nome do arquivo
    if ($_UP['renomeia'] == true) {
        // Cria um nome baseado no UNIX TIMESTAMP atual com a extensão certa
        $nome_final = md5(time()) . '.' . $extensao;
        $messageAnexo [1] = $nome_final;
    } else {
        // Mantém o nome original do arquivo
        $nome_final = $_FILES['anexoRacap']['name'];
        $messageAnexo [1] = $nome_final;
    }

// Depois verifica se é possível mover o arquivo para a pasta escolhida
    if (move_uploaded_file($_FILES['anexoRacap']['tmp_name'], $_UP['pasta'] . $nome_final)) {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        $messageAnexo [0] = "Upload efetuado com sucesso!";
    } else {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $messageAnexo [0] = "Não foi possível enviar o arquivo, tente novamente";
    }

    return $messageAnexo;
}
