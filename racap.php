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
        <title>Controle de RACAP's - Abrir/Editar RACAP's</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Gerenciar RACAP's</h2>
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

        <form method="POST" id="buscaBanco">
            <label for="selectbuscaBanco">Buscar Causa da RACAP: </label>
            <select id="selectbuscaBanco" name="selectbuscaBanco">
                <option></option>
                <?php
                $query = "SELECT * FROM racap_racap";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_assoc($sql);

                if (mysqli_affected_rows($conexao) > 0) {
                    echo "<option value=" . $row['id'] . ">" . $row['motivo_racap'] . "</option>";
                    while ($row = mysqli_fetch_array($sql)) {
                        echo "<option value=" . $row['id'] . ">" . $row['motivo_racap'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>
        <hr>

        <form method="POST" id="cadRACAP" action="racap_manage.php">
            <?php
            if ($_SESSION['tipoPrivilegio'] == 2) {
                echo "<fieldset disabled>";
            } else {
                echo "<fieldset>";
            }
            ?>
            <label for="sequencial">ID:</label>
            <input type="number" step="1" min="0" name="sequencial" id="sequencial" readonly />

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="statusRacap">Status da RACAP: </label>
            <select id="statusRacap" name="statusRacap" required>
                <option></option>
                <?php
                $query = "SELECT * FROM racap_status_racap";
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

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="tipoRacap">Tipo da RACAP: </label>
            <select id="tipoRacap" name="tipoRacap" required>
                <option></option>
                <?php
                $query = "SELECT * FROM racap_tipo_racap";
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

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="motivoAbertura">Motivo de Abertura: </label>
            <select id="motivoAbertura" name="motivoAbertura" required>
                <option></option>
                <?php
                $query = "SELECT * FROM racap_motivo_abertura";
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
            <label for="motivoDescricao">Motivo da RACAP (Descrição): </label>
            <input type="text" maxlength="50" id="motivoDescricao" required/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="setorRacap">Setor: </label>
            <select id="setorRacap" name="setorRacap" required>
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

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="causaRacap">Causa da RACAP: </label>
            <select id="causaRacap" name="causaRacap">
                <option></option>
                <?php
                $query = "SELECT * FROM racap_causa";
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

            <label for='prazoRacap'>Prazo da RACAP:</label>
            <input type='datetime-local' name='prazoRacap' id='prazoRacap' style="width:18%;"required/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="anexoRacap" title="Anexar Arquivos na RACAP se houver.">Anexar Arquivo:</label>
            <input type="file" name="arquivo" name="anexoRacap" id="anexoRacap"/>
            
            <br/><br/>
            <label for="historicoRACAP">Histórico da RACAP: </label>
            <br/>
            <textarea name="históricoRACAP" id="históricoRACAP" rows="6" cols="140" wrap="hard" required></textarea>

            <p align="center">
                <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar RACAP"/>
                &nbsp;&nbsp;
                <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
            </p>
        </fieldset>
    </form>

</body>
</html>
