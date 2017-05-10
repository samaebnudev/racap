<?php
session_start();
include "conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:login.php");
}

$privilegio = $_SESSION['tipoPrivilegio'];
//$privilegio = 1;
?>

<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Controle de RACAP's - Relatórios</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/accordion.css">
        <link rel="stylesheet" href="css/form.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP's - Relatórios</h2>
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

        <button class="accordion">Relatório Geral de RACAP's</button>
        <div class="panel">
            <form>
                <label for="statusRacap">Status: Aberta</label>
                <input type="radio" name="statusRacap" value="" required/>

                <label for="statusRacap">Fechada </label>
                <input type="radio" name="statusRacap" value=""/>

                <label for="statusRacap">Todas </label>
                <input type="radio" name="statusRacap" value=""/>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="periodoRacapInicio">Período De:</label>
                <input type='date' name='periodoRacapInicio' id='periodoRacapInicio' required/>

                <label for="periodoRacapFim"> &nbsp; Até:</label>
                <input type='date' name='periodoRacapFim' id='periodoRacapFim' required/>

                &nbsp;&nbsp;
                <input type="submit" value="Gerar Relatório"/>
            </form>
            <br/>
        </div>

        <button class="accordion">RACAP's Vencidas</button>
        <div class="panel">
            <form>
                <input type="hidden" name="dataAtual" id="dataAtual" value="dataAtual colocar PHP Aqui"/>
                <input type="submit" value="Gerar Relatório"/>
            </form>
            <br/>
        </div>

        <button class="accordion">RACAP's a Vencer</button>
        <div class="panel">
            <form>
                <label for="periodoRacapInicio">Período De:</label>
                <input type='date' name='periodoRacapInicio' id='periodoRacapInicio' required/>

                <label for="periodoRacapFim"> &nbsp; Até:</label>
                <input type='date' name='periodoRacapFim' id='periodoRacapFim' required/>

                &nbsp;&nbsp;
                <input type="submit" value="Gerar Relatório"/>
            </form>
            <br/>
        </div>

        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function () {
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
                }
            }
        </script>

    </body>
</html>
