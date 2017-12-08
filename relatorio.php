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
        <link rel="stylesheet" href="css/multiple-select.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/multiple-select.js"></script>
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
            <form method="POST" action="templates/racapGeral2.php" target="_blank">

                <label for="checkStatus">Status:</label>
                <input type="checkbox" name="statusCheck" id="statusCheck" value="S"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="statusRacap">Aberta</label>
                <input type="radio" name="statusRacap" value="1" disabled/>
                &nbsp;&nbsp;
                <label for="statusRacap">Pendente </label>
                <input type="radio" name="statusRacap" value="2" disabled/>
                &nbsp;&nbsp;
                <label for="statusRacap">Análise </label>
                <input type="radio" name="statusRacap" value="3" disabled/>
                &nbsp;&nbsp;
                <label for="statusRacap">Encerrada </label>
                <input type="radio" name="statusRacap" value="4" disabled/>
                &nbsp;&nbsp;
                <label for="statusRacap">Cancelada </label>
                <input type="radio" name="statusRacap" value="5" disabled/>
                &nbsp;&nbsp;
                <!--<label for="statusRacap">Todas </label>
                <input type="radio" name="statusRacap" value="6"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                <br/><br/><br/>
                <label for="checkPeriodo">Data de Abertura:</label>
                <input type="checkbox" name="checkPeriodo" id="checkPeriodo" value="S"/>
                &nbsp;
                <label for="periodoRacapInicio">De:</label>
                <input type='date' name='periodoRacapInicio' id='periodoRacapInicio' required/>

                <label for="periodoRacapFim"> &nbsp; Até:</label>
                <input type='date' name='periodoRacapFim' id='periodoRacapFim' required/>
                <br/><br/><br/>
                <label for="motivoAberturaCheck">Motivo de Abertura: </label>
                <input type="checkbox" name="motivoAberturaCheck" id="motivoAberturaCheck" value="S"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="statusRacap">Auditoria Interna</label>
                <input type="radio" name="motivoRacap" value="1" required/>
                &nbsp;&nbsp;
                <label for="statusRacap">Auditoria Externa </label>
                <input type="radio" name="motivoRacap" value="2"/>
                &nbsp;&nbsp;
                <label for="statusRacap">Visita Técnica </label>
                <input type="radio" name="motivoRacap" value="3"/>
                &nbsp;&nbsp;
                <label for="statusRacap">Solicitação da Seção </label>
                <input type="radio" name="motivoRacap" value="4"/>
                &nbsp;&nbsp;
                <label for="statusRacap">Índice </label>
                <input type="radio" name="motivoRacap" value="5"/>
                &nbsp;&nbsp;
                <br/><br/><br/>
                <label for="motivoAberturaCheck">Seção: </label>
                <input type="checkbox" name="secaoCheck" id="secaoCheck" value="S"/>
                &nbsp;&nbsp;
                <select id="setorRacap" name="setorRacap"  required>
                    <option></option>
                    <?php
                    $query = "SELECT * FROM racap_setor";
                    $sql = mysqli_query($conexao, $query);
                    $row = mysqli_fetch_assoc($sql);

                    if (mysqli_affected_rows($conexao) > 0) {
                        echo "<option value=" . $row['id'] . ">" . $row['nomeSetor'] . "</option>";
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value=" . $row['id'] . ">" . $row['nomeSetor'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <p align="center">
                    <input type="submit" value="Gerar Relatório"/>
                </p>
            </form>
            <br/>
        </div>
        <hr>
        <button class="accordion">RACAP's Vencidas</button>
        <div class="panel">
            <form method="POST" action="templates/racapVencida2.php" target="_blank">
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
            <form method="POST" action="templates/racapAVencer2.php" target="_blank">
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
        <div class="panel" style="height: 40%;">
            <form method="POST" action="templates/racapCapa2.php" target="_blank">
                <label for="idRacap">RACAP:</label>
                <select id="idRacap" name="idRacap" class="capaRacap" required>
                    <option></option>
                    <?php
                    $query = "SELECT * FROM racap_racap";
                    $sql = mysqli_query($conexao, $query);
                    $row = mysqli_fetch_assoc($sql);

                    if (mysqli_affected_rows($conexao) > 0) {
                        echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                        }
                    }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;
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

        <script>
            $('.capaRacap').multipleSelect({
                single: true,
                filter: true
            });
        </script>

    </body>
</html>
