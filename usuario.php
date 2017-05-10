<?php
session_start();
include "conecta_banco.inc";

//if ($_SESSION['nomeUsuario']==''){
//header("Location:login.php");
//}
//$privilegio = $_SESSION['tipoPrivilegio'];
$privilegio = 1;
?>

<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Controle de RACAP's - Usuários</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script type="text/javascript" src="js/usuario.js"></script>
        <script src="js/buscaUsuario.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Usuários</h2>
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
                $query = "SELECT * FROM racap_usuario";
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
        </form>
        <hr>

        <form method="POST" id="cadUsuario" action="usuario_manage.php">
            <?php
            /* if ($_SESSION['tipoPrivilegio'] == 2){
              echo "<fieldset disabled>";
              } else { */
            echo "<fieldset>";
            //}*/
            ?>

            <label for="sequencial">ID:</label>

            <input type="number" step="1" min="0" name="sequencial" id="sequencial" readonly />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="matUsuario">Matrícula: </label>
            <input type="number" step="1" min="0" name="matUsuario" id="matUsuario" style="width:10%;" required/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="nomeUsuario">Nome de Usuário: </label>
            <input type="text" name="nomeUsuario" id="nomeUsuario" required/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="setorUsuario">Setor: </label>
            <select name="setorUsuario" id="setorUsuario" required>
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

            <br/><br/>
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="perfilUsuario">Tipo de Usuário: </label>
            <select name="perfilUsuario" id="perfilUsuario">
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

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="senhaUsuario">Senha: </label>
            <input type="password" name="senhaUsuario" id="senhaUsuario" maxlength="20" title="Senha do usuário do sistema. Máximo de 20 Caracteres." required/>
            <input type="button" value="Mostrar" onclick="mostraSenha()"/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="flgAtivo"><input type="checkbox" name="flgAtivo" id="flgAtivo" value="S" title="Marcar este campo para tornar o usuário ativo no sistema."/> Usuário Ativo.</label>
            <br/><br/>

            <p align="center">
                <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar Usuário"/>
                &nbsp;&nbsp;
                <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
            </p>
        </fieldset>
    </form>

</body>
</html>
