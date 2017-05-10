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
        <title>Controle de RACAP's - Status da RACAP</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/buscaStatusRacap.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Status da RACAP</h2>
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
            <label for="selectbuscaBanco">Buscar: </label>
            <select id="selectbuscaBanco" name="selectbuscaBanco">
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
        </form>
        <hr>

        <form method="POST" id="cadStatusRacap" action="status_racap_manage.php">
            <?php
            /* if ($_SESSION['tipoPrivilegio'] == 2){
              echo "<fieldset disabled>";
              } else { */
            echo "<fieldset>";
            //}*/
            ?>

            <input type="number" step="1" min="0" name="sequencial" id="sequencial" readonly />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="descricao">Status da RACAP: </label>
            <input type="text" name="descricao" id="descricao" required/><br/><br/>

            <p align="center">
                <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar Status de RACAP"/>
                &nbsp;&nbsp;
                <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
            </p>
        </fieldset>
    </form>

</body>
</html>
