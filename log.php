<?php
session_start();
include "conecta_banco.inc";
include "getIP.php";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:login.php");
}

if (isset($_SESSION['id'])) {
    if ($_SESSION['tipoPrivilegio'] != '1') {
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Tentativa de acesso sem privilégio administrativo em log.php";
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
<html >
    <head>
        <meta charset="UTF-8">
        <title>Controle de Glosas - Log do Sistema</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script type="text/javascript" src="js/log.js"></script>
    </head>

    <body onload="mudaFormLog(1)">
        <div class="topbar">

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

        <h3>Arquivo de Log - Critérios de Geração:</h3>
        <p align="center">
            <label><input type="radio" name="criterioLogCheck" onclick="mudaFormLog(1)" checked="true"/> Por Datas.</label>&nbsp;&nbsp;
            <label><input type="radio" name="criterioLogCheck" onclick="mudaFormLog(2)"/> Últimas Entradas do Log.</label>&nbsp;&nbsp;
            <label><input type="radio" name="criterioLogCheck" onclick="mudaFormLog(3)"/> Atividades de Usuário.</label>&nbsp;&nbsp;
            <label><input type="radio" name="criterioLogCheck" onclick="mudaFormLog(4)"/> Por tentativas de Login.</label>&nbsp;&nbsp;
        </p><hr>

        <form method="POST" action="log_manage.php" target="blank" id="logByDate" class="formLog">

            <input type="hidden" name="criterioLog" value="byDate"/>
            <label>De : <input type="date" name="byDateDataInicio" id="byDateDataInicio" onblur="restringebyDateDataFim()" required/></label>
            &nbsp;&nbsp;
            <label>Até : <input type="date" name="byDateDataFim" id="byDateDataFim" required/></label>
            <br/><br/>

            <p align="center">
                <input type="submit" value="Gerar Relatório" class="btn" />
            </p>

        </form>

        <form method="POST" action="log_manage.php" target="blank" id="logByEntrie" class="formLog">
            <input type="hidden" name="criterioLog" value="byEntrie"/>

            <label>Última(s) <input type="number" step="1" min="1" name="byEntrieQuantidade" onkeypress="return SomenteNumero(event)" required/> entrada(s) do Log.</label>
            <br/><br/>

            <p align="center">
                <input type="submit" value="Gerar Relatório" class="btn" />
            </p>

        </form>

        <form method="POST" action="log_manage.php" target="blank" id="logByUser" class="formLog">
            <input type="hidden" name="criterioLog" value="byUser"/>

            <label for="byUserNomeUsuario">Usuário: </label>

            <select name="byUserNomeUsuario" id="byUserNomeUsuario" required>
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
            <br/><br/>

            <label><input type="radio" name="byUserCriterio" value="byUserData" onclick="userActivityForm(0)" required/>
                Listar um período específico de atividade do usuário.</label>

            <br/><br/>
            <label>De: <input type="date" name="byUserDataInicio" id="byUserDataInicio" onblur="restringebyUserDataFim()" readonly /></label>
            &nbsp;&nbsp;
            <label>Até: <input type="date" name="byUserDataFim" id="byUserDataFim" readonly /></label>

            <br/><br/>
            <br/><br/>

            <label><input type="radio" name="byUserCriterio" value="byUserEntries" onclick="userActivityForm(1)" />
                Listar as últimas atividades do usuário.</label>

            <br/><br/>
            <label>Última(s) <input type="number" step="1" min="1" name="byUserLastEntries" id="byUserLastEntries" onkeypress="return SomenteNumero(event)" readonly /> atividade(s) do usuário.</label>
            <br/><br/>

            <p align="center">
                <input type="submit" value="Gerar Relatório" class="btn" />
            </p>

        </form>

        <form method="POST" action="log_manage.php" target="blank" id="logByLoginAttempt" class="formLog">
            <input type="hidden" name="criterioLog" value="byLoginAttempt"/>

            <label><input type="radio" name="byLoginAttempt" value="byLoginAttemptEntrie" onclick="loginAttemptForm(0)" required/>
                Listar última(s) tentativa(s) de Login.</label>
            <br/><br/>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Última(s) <input type="number" min="1" step="1" name="loginAttemptNumber" id="loginAttemptNumber" onkeypress="return SomenteNumero(event)" readonly /> tentativa(s) de Login.</label>
            <br/><br/><br/><br/>

            <label><input type="radio" name="byLoginAttempt" value="byLoginAttemptDate" onclick="loginAttemptForm(1)"/>
                Listar última(s) tentativa(s) de Login no período.</label>
            <br/><br/>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;De: <input type="date" name="loginAttemptDateStart" id="loginAttemptDateStart" onblur="restringeloginAttemptDateEnd()" readonly /></label>
            <label>&nbsp;&nbsp;&nbsp;Até: <input type="date" name="loginAttemptDateEnd" id="loginAttemptDateEnd" readonly /></label>
            <br/><br/>
            <p align="center">
                <input type="submit" value="Gerar Relatório" class="btn" />
            </p>
        </form>

    </body>
</html>