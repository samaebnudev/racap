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
        <script type="text/javascript" src="js/relatorio.js"></script>
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
            <form method="POST" action="gera_relatorio.php" target="_blank">
                <input type="hidden" name="tipoRel" value="racapGeral"/>
                <label for="statusRacap">Status: Pendente</label>
                <input type="radio" name="statusRacap" value="1" required/>

                <label for="statusRacap">Fechada </label>
                <input type="radio" name="statusRacap" value="2"/>

                <label for="statusRacap">Cancelada </label>
                <input type="radio" name="statusRacap" value="3"/>

                <label for="statusRacap">Todas </label>
                <input type="radio" name="statusRacap" value="4"/>

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
        <hr>
        <button class="accordion">RACAP's Vencidas</button>
        <div class="panel">
            <form method="POST" action="gera_relatorio.php" target="_blank">
                <input type="hidden" name="tipoRel" value="racapVencida"/>

                <?php
                $dataVencimento = date("Y-m-d 23:59:59");
                echo "<input type='hidden' name='dataVencimento' id='dataVencimento' value='$dataVencimento'/>";
                ?>

                <input type="submit" value="Gerar Relatório"/>
            </form>
            <br/>
        </div>
        <hr>
        <button class="accordion">RACAP's a Vencer</button>
        <div class="panel">
            <form method="POST" action="gera_relatorio.php" target="_blank">
                <input type="hidden" name="tipoRel" value="racapAVencer"/>
                <?php
                $dataAtual = date("Y-m-d");
                echo "<input type='hidden' name='dataHoje' id='dataHoje' value='$dataAtual'/>";
                ?>

                <label for="dataLimite" title='Data Limite sempre será igual ou maior que a Data Atual'> 
                    &nbsp; Data Limite:</label>
                <input type='date' name="dataLimite" id="dataLimite" required/>
                &nbsp;&nbsp;
                <input type="submit" value="Gerar Relatório"/>
            </form>
            <br/>
        </div>
        <hr>
        <button class="accordion">Capa de RACAP</button>
        <div class="panel">
            <form method="POST" action="gera_relatorio.php" target="_blank">
                <input type="hidden" name="tipoRel" value="racap"/>
                <label for="idRacap">Selecione a RACAP: </label>
                <select id="idRacap" name="idRacap">
                    <option></option>
                    <?php
                    $query = "SELECT * FROM racap_racap";
                    $sql = mysqli_query($conexao, $query);
                    $row = mysqli_fetch_assoc($sql);

                    if (mysqli_affected_rows($conexao) > 0) {
                        echo "<option value=" . $row['id'] . "> RACAP " . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value=" . $row['id'] . "> RACAP " . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                        }
                    }
                    ?>
                </select>
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
