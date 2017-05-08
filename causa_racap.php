<?php
session_start();
include "conecta_banco.inc";

//if ($_SESSION['nomeUsuario']==''){
//header("Location:login.php");
//}
//$privilegio = $_SESSION['tipoPrivilegio'];
$privilegio = 1;
?>

<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <title>Controle de RACAP's - Causa RACAP's</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>

    
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Causa RACAP's</h2>
            <div  class="open">
                <span class="cls"></span>
                <span>
                    <ul class="sub-menu ">
                        <li>
                            <a href="index.php">Menu Principal</a>
                        </li>
                        <li>
                            <a href="troca_senha.php">Trocar Senha</a>
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
            <form method="POST" id="buscaCausaRacap">
                <label for="selectBuscaCausaRacap">Buscar Causa da RACAP: </label>
                <select name="selectBuscaCausaRacap" id="selectBuscaCausaRacap">
                    <option>Selecione a causa de RACAP Buscar...</option>
                </select>
            </form>
        <hr>

        <form method="POST" id="cadCausaRacap" action="">
            <?php
            /*if ($_SESSION['tipoPrivilegio'] == 2) {
                echo "<fieldset disabled>";
            } else {*/
                echo "<fieldset>";
            //}
            ?>
            <input type="hidden" name="operacao" value="inclui"/>
            <label for="sequencial">ID:</label>

            <input type="number" step="1" min="0" name="sequencial" id="idCausa" readonly />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="nomeCausa">Causa: </label>
            <input type="text" name="nomeCausa" id="nomeCausa" required/><br/><br/>

            <p align="center">
                <input type="submit" class="btn" value="Gravar" title="Incluir Causa de RACAP"/>
                &nbsp;&nbsp;
                <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
            </p>
        </fieldset>
    </form>



