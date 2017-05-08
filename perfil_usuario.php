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
        <title>Controle de RACAP's - Perfis de Usuário</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
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

        <form method="POST" id="buscaPerfil">
            <label for="selectBuscaPerfil">Buscar: </label>
            <select id="selectBuscaPerfil" name="selectBuscaPerfil">
                <option>Selecione o Perfil...</option>
            </select>
        </form>
        <hr>

        <form method="POST" id="cadTipoUsuario" action="usuario_manage.php">
            <?php
            /* if ($_SESSION['tipoPrivilegio'] == 2){
              echo "<fieldset disabled>";
              } else { */
            echo "<fieldset>";
            //}*/
            ?>
            <input type="hidden" name="operacao" value="inclui"/>
            
            <label for="sequencial">ID:</label>
            <input type="number" step="1" min="0" name="sequencial" id="seqTipoUsuario" readonly />
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            
            <label for="nomePerfil">Perfil: </label>
            <input type="text" name="nomePerfil" id="nomePerfil" required/>
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
