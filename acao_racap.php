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
        <title>Controle de RACAP's - Ações da RACAP</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/racapBuscaAcao.js"></script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Ações da RACAP</h2>
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

        <form method="POST" id="buscaAcaoRacap" style="text-align: center;">
            <label for="selectAcaoRacap">Buscar: </label>
            <select id="selectAcaoRacap" name="selectAcaoRacap">
                <option></option>
                <?php
                $query = "SELECT * FROM racap_acao ORDER BY id_racap";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_assoc($sql);

                if (mysqli_affected_rows($conexao) > 0) {
                    echo "<option value=" . $row['id'] . ">Ação da RACAP: " . $row['id_racap'] . " - " . $row['descricao_acao'] . "</option>";
                    while ($row = mysqli_fetch_array($sql)) {
                        echo "<option value=" . $row['id'] . ">Ação da RACAP: " . $row['id_racap'] . " - " . $row['descricao_acao'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
        <hr>

        <form method="POST" id="cadAcao" action="racap_acao_manage.php">
            <fieldset>
                <label for="sequencialAcao">ID:</label>
                <input type="number" step="1" min="0" name="sequencialAcao" id="sequencialAcao" readonly />
                <input type="hidden" name="urlBack" id="urlBack" value="acao_racap.php"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="idRacap">RACAP: </label>
                <select id="idRacap" name="idRacap" required>
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

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="selectStatusAcao">Status da Ação: </label>
                <select id="selectStatusAcao" name="selectStatusAcao" required>
                    <option></option>
                    <?php
                    $query = "SELECT * FROM racap_status_acao";
                    $sql = mysqli_query($conexao, $query);
                    $row = mysqli_fetch_assoc($sql);

                    if (mysqli_affected_rows($conexao) > 0) {
                        echo "<option value=" . $row['id'] . ">" . $row['descricao'] . "</option>";
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value=" . $row['id'] . ">" . $row['descricao'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <br/><br/>

                <label for="selectResponsavel">Responsável: </label>
                <select id="selectResponsavel" name="selectResponsavel" required>
                    <option></option>
                    <?php
                    $query = "SELECT id, nomeServidor FROM racap_usuario";
                    $sql = mysqli_query($conexao, $query);
                    $row = mysqli_fetch_assoc($sql);

                    if (mysqli_affected_rows($conexao) > 0) {
                        echo "<option value=" . $row['id'] . ">" . $row['nomeServidor'] . "</option>";
                        while ($row = mysqli_fetch_array($sql)) {
                            echo "<option value=" . $row['id'] . ">" . $row['nomeServidor'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <br/><br/>

                <label for="descricaoAcao">Descrição da Ação: </label>
                <p style="text-align: center;">
                    <textarea name="descricaoAcao" id="descricaoAcao" rows="6" cols="140" wrap="hard" required></textarea>
                </p>
                <p align="center">
                    <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar Ação da RACAP"/>
                    &nbsp;&nbsp;
                    <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
                </p>
            </fieldset>
        </form>

    </body>
</html>
