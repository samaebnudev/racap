<?php    
date_default_timezone_set('Brazil/East');
//include "conecta_banco.inc";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendMail($idRacap,$idUsuario){
    
    $conexao = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or print (mysqli_error($conexao));
    $queryUsuario = "SELECT nomeServidor, emailUsuario FROM racap_usuario WHERE id='$idUsuario'";
    $sql = mysqli_query($conexao, $queryUsuario);
    $row = mysqli_fetch_array($sql);
    $nomeUsuario = utf8_decode($row['nomeServidor']);
    $emailUsuario = utf8_decode($row['emailUsuario']);
    
    if($emailUsuario != ""){
        $queryRacap = "SELECT motivo_racap FROM racap_racap WHERE id='$idRacap'";
        $sql = mysqli_query($conexao, $queryRacap);
        $row = mysqli_fetch_array($sql);
        $motivoRacap = utf8_decode($row['motivo_racap']);
        
        $mensagemAssunto = "Responsável Técnico. RACAP - ".$idRacap;
        
        $mensagemCorpo = "Olá sr(a). <b>".$nomeUsuario."</b>.<br/><br/>"
                . "Você foi adicionado(a) como responsável técnico(a) para a "
                . "<b>RACAP ".$idRacap." - ".$motivoRacap."</b>"
                ."<br/><br/>Acesse o sistema neste <a href='http://192.168.30.14/racap'>link</a> para mais informações."
                . "<br/><br/>Caso não tenha acesso ao sistema, favor entrar em contato com a ISO pelo Ramal <b>8447</b>.";
        
        $mailer = new PHPMailer();
        $mailer->charSet = 'utf-8';
        $mailer->IsSMTP();
        $mailer->isHTML(true);
        //$mailer->SMTPDebug = 1;
        $mailer->Port = 587; //Indica a porta de conexão 
        $mailer->Host = 'smtp.samae.com.br';//Endereço do Host do SMTP 
        $mailer->SMTPAuth = true; //define se haverá ou não autenticação 
        $mailer->Username = 'devsistemas@samae.com.br'; //Login de autenticação do SMTP
        $mailer->Password = 'samae2904'; //Senha de autenticação do SMTP
        $mailer->FromName = utf8_decode('SAMAE RACAP (Não Responder)'); //Nome que será exibido
        $mailer->From = 'devsistemas@samae.com.br'; //Obrigatório ser 
        //a mesma caixa postal configurada no remetente do SMTP
        $mailer->AddAddress($emailUsuario,$nomeUsuario);
        //Destinatários
        $mailer->Subject = utf8_decode($mensagemAssunto);
        $mailer->Body = utf8_decode($mensagemCorpo);
        if(!$mailer->Send())
        {
            $mailResponse = "A mensagem não pôde ser enviada."
                    . "Mailer Error: ". $mailer->ErrorInfo;
            return $mailResponse;
        }
        else{
            $mailResponse = "E-mail enviado com sucesso.";
            return $mailResponse;
        }
        
    }else{
        $mailResponse = "O responsável técnico ".$nomeUsuario." não possui e-mail cadastrado no sistema.";
        return $mailResponse;
    }
    
}
 
?>