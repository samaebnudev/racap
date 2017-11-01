<?php
session_start();
include "conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:login.php");
}

$privilegio = $_SESSION['tipoPrivilegio'];

if (isset($_SESSION['id'])) {
    if ($_SESSION['tipoPrivilegio'] != '1') {
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Tentativa de acesso sem privilégio administrativo em causa_racap.php";
        $usuario = $_SESSION['nomeUsuario'];
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$usuario', '$ip')";
        $sql = mysqli_query($conexao, $query);
        header("Location:index.php");
    }
}

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
    <script src="js/buscaCausaRacap.js"></script>
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
<form method="POST" id="buscaBanco" style="text-align: center;">
    <label for="selectbuscaBanco">Buscar Causa da RACAP: </label>
    <select id="selectbuscaBanco" name="selectbuscaBanco">
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
</form>
<hr>

<form method="POST" id="cadCausaRacap">
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
    <label for="descricao">Causa: </label>
    <input type="text" name="descricao" id="descricao" required/><br/><br/>

    <p align="center">
        <button type="submit" formaction="causa_racap_manage.php" class="btn" title="Incluir Causa de RACAP">Gravar</button>
        &nbsp;&nbsp;
        <button type="submit" formaction="exclui_causa_racap.php" class="btn" title="Excluir Causa de RACAP">Excluir</button>
        &nbsp;&nbsp;
        <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
    </p>
</fieldset>
</form>



