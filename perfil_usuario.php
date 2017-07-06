<?php
session_start();
include "conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:login.php");
}

if (isset($_SESSION['id'])) {
    if ($_SESSION['tipoPrivilegio'] != '1') {
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Tentativa de acesso sem privilégio administrativo em perfil_usuario.php";
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
        <title>Controle de RACAP's - Perfis de Usuário</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/buscaPerfilUsuario.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Perfil de Usuário</h2>
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
                $query = "SELECT * FROM racap_perfil";
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

        <form method="POST" id="cadPerfilUsuario" action="perfil_usuario_manage.php">
            <fieldset>

                <label for="sequencial">ID:</label>
                <input type="number" step="1" min="0" name="sequencial" id="sequencial" readonly />

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="descricao">Perfil: </label>
                <input type="text" name="descricao" id="descricao" required/>
                <br/><br/>

                <p align="center">
                    <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar Perfil de Usuário"/>
                    &nbsp;&nbsp;
                    <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
                </p>
            </fieldset>
        </form>

    </body>
</html>
