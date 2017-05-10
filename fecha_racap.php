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
        <title>Controle de RACAP's - Fechar RACAP's</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="css/accordion.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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

        <form>
            <label>Buscar: </label>
            <select>
                <option>Selecione o Fechamento...</option>
            </select>
        </form>
        <hr>

        <form method="POST" id="cadRACAP" action="usuario_manage.php">
            <?php
            /* if ($_SESSION['tipoPrivilegio'] == 2){
              echo "<fieldset disabled>";
              } else { */
            echo "<fieldset>";
            //}*/
            ?>
            <input type="hidden" name="operacao" value="incluiTipoUsuario"/>
            <label for="sequencial">ID:</label>
            <input type="number" step="1" min="0" name="sequencial" id="seqTipoUsuario" readonly />

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="racapId">ID da RACAP: </label>
            <select>
                <option>Selecione a RACAP...</option>
            </select>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for='dataFechamento'>Data de Fechamento:</label>
            <input type='datetime-local' name='dataFechamento' id='dataFechamento' required/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="racapPrazo">RACAP no Prazo: Sim <input type="radio" name="racapPrazo" id="racapPrazoSim" value="S"/></label>
            <label>Não <input type="radio" name="racapPrazo" id="racapPrazoNao" value="N"/></label>

            <br/><br/>

            <label for="racapEficiencia">RACAP Eficiente: Sim <input type="radio" name="racapEficiencia" id="racapEficienciaSim" value="S"/></label>
            <label>Não <input type="radio" name="racapEficiencia" id="racapEficienciaNao" value="N"/></label>

            <br/><br/>
            <label for="observacaoRACAP">Observações: </label>
            <br/>
            <textarea name="observacaoRACAP" id="observacaoRACAP" rows="6" cols="140" wrap="hard" required></textarea>

            <p align="center">
                <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar RACAP"/>
                &nbsp;&nbsp;
                <input type="reset" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
            </p>
        </fieldset>
    </form>

</body>
</html>
