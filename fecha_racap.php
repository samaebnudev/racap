<?php
session_start();
include "conecta_banco.inc";
date_default_timezone_set('Brazil/East');

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
        <title>Controle de RACAP's - Fechar RACAP's</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/fechaRacap.js"></script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Fechamento de RACAP's</h2>
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

        <form method="POST" id="buscaBanco" style="text-align: center;">
            <label for="selectbuscaBanco">Buscar: </label>
            <select id="selectbuscaBanco" name="selectbuscaBanco">
                <option></option>
                <?php
                $query = "SELECT * FROM racap_fecha_racap";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_assoc($sql);

                if (mysqli_affected_rows($conexao) > 0) {
                    echo "<option value=" . $row['id'] . ">" . $row['observacao_racap'] . "</option>";
                    while ($row = mysqli_fetch_array($sql)) {
                        echo "<option value=" . $row['id'] . ">" . $row['observacao_racap'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
        <hr>

        <form method="POST" id="cadFechaRacap" action="fecha_racap_manage.php">
            <fieldset>
                <label for="sequencial">ID:</label>
                <input type="number" step="1" min="0" name="sequencial" id="sequencial" readonly />

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="idRacap">RACAP: </label>
                <select id="idRacap" name="idRacap" required>
                    <option></option>
                    <?php
                    $query = "SELECT * FROM racap_racap WHERE status_racap = '1'";
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

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for='racapPrazo'>RACAP no Prazo: Sim 
                    <input type='radio' name='racapPrazo' id='racapPrazoSim' class="noClick" value='S'/>
                </label>
                <label>Não
                    <input type='radio' name='racapPrazo' id='racapPrazoNao' class="noClick" value='N'/>
                </label>

                <?php
                $dataFechamento = date("Y-m-d H:i:s");
                echo "<input type='hidden' name='dataFechamento' id='dataFechamento' value='$dataFechamento'";
                ?>

                <br/><br/><br/>

                <label for="racapEficiencia">RACAP Eficiente: Sim 
                    <input type="radio" name="racapEficiencia" id="racapEficienciaSim" value="S"/>
                </label>
                <label>
                    Não <input type="radio" name="racapEficiencia" id="racapEficienciaNao" value="N"/>
                </label>

                <br/><br/>
                <label for="observacaoRACAP">Observações: </label>
                <p align="center">
                <textarea name="observacaoRACAP" id="observacaoRACAP" rows="6" cols="140" wrap="hard" required></textarea>
                </p>
                
                <p align="center">
                    <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar RACAP"/>
                    &nbsp;&nbsp;
                    <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
                </p>
            </fieldset>
        </form>

    </body>
</html>
