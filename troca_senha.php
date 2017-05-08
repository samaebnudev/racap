<?php
session_start();
include "conecta_banco.inc";

/* if ($_SESSION['nomeUsuario']==''){
  header("Location:login.php");
  }

  $privilegio = $_SESSION['tipoPrivilegio']; */

$privilegio = 1;
?>

<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Sistema RACAP - Troca de Senha</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/troca_senha.js"></script>
    </head>

    <body>
        <div class="topbar">
        <h2 align="center">Controle de RACAP - Troca de Senha</h2>
            <div  class="open">
                <span class="cls"></span>
                <span>
                    <ul class="sub-menu ">
                        <li>
                            <a href="index.php">Menu Principal</a>
                        </li>
                        <li>
                            <a href="logout.php">Sair</a>
                        </li>
                    </ul>
                </span>
                <span class="cls"></span>
            </div>
        </div>
        <br/>
        <form method="POST" id="trocaSenha" action ="troca_senha_manage.php" onsubmit= "return verificaSenha()">

            <label for="senhaAtual">Senha Atual: </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="password" name="senhaAtual" id="senhaAtual" required/>
            <input type="button" value="Mostrar" onclick="mostraSenha(0)"/>
            <br/><br/>

            <label for="novaSenha">Nova Senha: </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="password" name="novaSenha" id="novaSenha" required/>
            <input type="button" value="Mostrar" onclick="mostraSenha(1)"/>
            <br/><br/>

            <label for="novaSenha2">Re-Digite a Senha: </label>
            <input type="password" name="novaSenha2" id="novaSenha2" required/>
            <input type="button" value="Mostrar" onclick="mostraSenha(2)"/>
            <br/>

            <p align="center"><input type="submit" value="Atualizar Senha"/>
                &nbsp;&nbsp;<a href="index.php"><input type="button" value="Voltar"/></a>
            </p>
        </form>
    </body>
</html>