<?php
include "conecta_banco.inc";
?>

<html>
    <head>
        <meta charset="UTF-8"></meta>
        <title>Controle de RACAP's - Login</title>
        <link rel="stylesheet" href="css/login.css"/>
    </head>
    <body>
        <br/><br/><br/>
        <div class="container">
            <img src="img/logo_samae.png" class="logoSamae"/>
            <p align="center">Controle de RACAP's - Login</p>
            <br/>
            <div class="loginContainer">
                <form action="valida_login.php" method="POST">
                    <label><br/>Usuário: <input type="text" maxlength="100" name="usuario" required/><br/><br/></label>
                    <label>&nbsp;&nbsp;Senha: <input type="password" size="15" name="senha" maxlength="20" required/><br/><br/></label>
                    <input type="submit" class="btnLogin" value="Login"/>
                </form>
            </div>
        </div>
    </body>
</html>
