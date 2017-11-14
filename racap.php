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
        <link rel="stylesheet" href="css/multiple-select.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/buscaRacap.js"></script>
        <script type="text/javascript" src="js/buscaRacapAnexo.js"></script>
        <script type="text/javascript" src="js/geraSelectAcao.js"></script>
        <script type="text/javascript" src="js/racapBuscaAcao.js"></script>
        <script type="text/javascript" src="js/multiple-select.js"></script>
        <script type="text/javascript" src="js/buscaResponsavelRacap.js"></script>
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

        <form method="POST" id="buscaBanco" style="text-align: center;">
            <label for="selectbuscaBanco">Buscar RACAP: </label>
            <select id="selectbuscaBanco" name="selectbuscaBanco">
                <option></option>
                <?php
                $query = "SELECT * FROM racap_racap";
                $sql = mysqli_query($conexao, $query);
                $row = mysqli_fetch_assoc($sql);

                if (mysqli_affected_rows($conexao) > 0) {
                    echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                    while ($row = mysqli_fetch_array($sql)) {
                        echo "<option value=" . $row['id'] . ">" . $row['id'] . " - " . $row['motivo_racap'] . "</option>";
                    }
                }
                ?>
            </select>
        </form>

        <hr>

        <button type="button" class='accordion' id="thing3">RACAP's:</button>
        <div class='panel'>
            <form method="POST" id="cadRACAP" action="racap_manage.php" enctype="multipart/form-data">
                <fieldset>
                    <label for="sequencial">Número da RACAP:</label>
                    <input type="number" step="1" name="sequencial"
                           id="sequencial" style="width: 5%;" readonly />

                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="motivoDescricao">Título: </label>
                    <input type="text" maxlength="500" id="motivoDescricao" 
                           name="motivoDescricao" 
                           style="width: 50%;"required/>

                    &nbsp;&nbsp;&nbsp;&nbsp;

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

                    &nbsp;&nbsp;&nbsp;&nbsp;

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

                    &nbsp;&nbsp;

                    <label for='dataAbertura'
                           title="Data de Abertura da RACAP é determinada automaticamente. Esse campo é SOMENTE LEITURA.">Data de Abertura:</label>
                    <input type='datetime-local' name='dataAbertura' id='dataAbertura' style="width:18%;" readonly
                           title="Data de Abertura da RACAP é determinada automaticamente. Esse campo é SOMENTE LEITURA."/>

                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <label for="setorRacap">Seção: </label>
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

                    <br/><br/>

                    <label for="selectResponsavel">Responsável: </label>
                    <select multiple="multiple" id="selectResponsavel" name="selectResponsavel[]" 
                            class="responsavel" required>
                                <?php
                                $query = "SELECT id, nomeServidor FROM racap_usuario ORDER BY nomeServidor";
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

                    <label for="statusRacap">Status da RACAP: </label>
                    <select id="statusRacap" name="statusRacap" required disabled>
                        <option></option>
                        <?php
                        $query = "SELECT * FROM racap_status_racap";
                        $sql = mysqli_query($conexao, $query);
                        $row = mysqli_fetch_assoc($sql);

                        if (mysqli_affected_rows($conexao) > 0) {
                            echo "<option value=" . $row['id'] . " selected='selected'>" . $row['descricao'] . "</option>";
                            while ($row = mysqli_fetch_array($sql)) {
                                echo "<option value=" . $row['id'] . ">" . $row['descricao'] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    &nbsp;&nbsp;&nbsp;&nbsp;

                    <label for='prazoRacap'>Prazo da RACAP:</label>
                    <input type='datetime-local' name='prazoRacap' id='prazoRacap' style="width:18%;"required/>

                    <br/><br/>
                    <label for="historicoRACAP">Histórico da RACAP: </label>
                    <p align="center">
                        <textarea name="historicoRACAP" id="historicoRACAP" rows="6" cols="140" wrap="hard" required></textarea>
                    <p align="center">
                        <input type="submit" class="btn" value="Gravar" title="Incluir ou Salvar RACAP"/>
                        &nbsp;&nbsp;
                        <input type="reset" id="racapResetForm" class="btn" value="Limpar" title="Limpa os dados do Formulário"/>
                    </p>
                </fieldset>
            </form>
        </div>
        <hr>
        <button type="button" class='accordion' id="thing2">Ações da RACAP:</button>
        <div class='panel'>
            <form id="buscaAcaoRacap" method="POST" enctype="multipart/form-data" style="text-align: center;">
                <label for="selectAcaoRacap">Buscar: </label>
                <select id="selectAcaoRacap" name="selectAcaoRacap">
                    <option>Buscar Ações...</option>
                </select>
            </form>
            <hr>
            <form method="POST" action="racap_acao_manage.php" id="racapAcaoRacap">

                <input type="hidden" name="sequencialAcao" id="sequencialAcao" readonly/>
                <input type="hidden" id="idRacap" name="idRacap" required/>

                <label for="numeroAcao">Número da Ação: </label>
                <input type="number" name="numeroAcao" id="numeroAcao" step="1" readonly/>

                &nbsp;&nbsp;&nbsp;&nbsp;

                <label for="tituloAcao">Título: </label>
                <input type="text" name="tituloAcao" id="tituloAcao" maxlength="500" />

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

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="prazo_acao">Data de Execução: </label>
                <input type="datetime-local" id="prazo_acao" name="prazo_acao"/>

                &nbsp;&nbsp;&nbsp;&nbsp;
                <label for='racapPrazo'>RACAP no Prazo: Sim 
                    <input type='radio' name='racapPrazo' id='racapPrazoSim' class="noClick" value='S'/>
                </label>
                <label>Não
                    <input type='radio' name='racapPrazo' id='racapPrazoNao' class="noClick" value='N'/>
                </label>

                <br/><br/>

                <label for='racapPrazo'>Ação Eficaz: Sim 
                    <input type='radio' name='racapEficiencia' id='racapEficienciaSim' class="noClick" value='S'/>
                </label>
                <label>Não
                    <input type='radio' name='racapEficiencia' id='racapEficienciaNao' class="noClick" value='N'/>
                </label>

                &nbsp;&nbsp;&nbsp;&nbsp;

                <label for="dataEficiencia">Data da Eficácia: </label>
                <input type="datetime-local" id="dataEficiencia" name="dataEficiencia"/>

                <br/><br/>

                <label for="descricaoAcao">Descrição da Ação: </label>
                <p style="text-align: center;">
                    <textarea name="descricaoAcao" id="descricaoAcao" rows="6" cols="140" wrap="hard" required></textarea>
                </p>
                <br/>
                <p align="center">
                    <input type="submit" value="Gravar Ação" id="racapAcaoSubmit"/>
                </p>
            </form>
        </div>
        <hr>
        <div id="anexosRacap">
            <button type="button" class='accordion' id="thing">Anexos da RACAP:</button>
            <div class='panel'>
                <form id="tabelaAnexos" method="POST" enctype="multipart/form-data">

                    <table>
                        <thead>
                            <tr><th>Seleciona</th><th>Arquivo</th><th>Baixar</th></tr>
                        </thead>
                        <tbody id="listaAnexos">
                        </tbody>
                    </table>

                    <input type="hidden" name="numRACAPFormAnexo" id="numRACAPFormAnexo"/>

                    <br/><br/>
                    <p align="center">
                        <label for="anexoRacap" title="Anexar Arquivos na RACAP se houver.">Anexar Arquivo:</label>
                        <input type="file" name="anexoRacap" id="anexoRacap"/>
                        <br/><br/><br/>
                        <input type="submit" value="Anexar Arquivo" formaction="inclui_anexo.php" />
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" value="Excluir Arquivo" formaction="exclui_anexo.php" />
                    </p>
                </form>
            </div>

        </div>
        <hr>
        <div id="fechaRacap">
            <button type="button" class='accordion' id="thing">Fechar ou Cancelar RACAP:</button>
            <div class="panel">
                <form method="POST" id="cadFechaRacap" action="fecha_racap_manage.php">
                    <fieldset>
                        <input type="hidden" name="numRACAP" id="numRACAP"/>
                        <?php
                        $dataFechamento = date("Y-m-d 23:59:59");
                        echo "<input type='hidden' name='dataFechamento' id='dataFechamento' value='$dataFechamento'";
                        ?>

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="statusRacapPos">Status após Fechamento: </label>
                        <select name="statusRacapPos" id="statusRacapPos" required>
                            <option></option>
                            <option value="4">Encerrada</option>
                            <option value="5">Cancelada</option>
                        </select>

                        <br/><br/>
                        <label for="observacaoRACAP">Observações: </label>
                        <p align="center">
                            <textarea name="observacaoRACAP" id="observacaoRACAP" rows="6" cols="140" wrap="hard" required></textarea>
                        </p>

                        <p align="center">
                            <input type="submit" class="btn" value="Gravar" title="Fechar RACAP"/>
                            &nbsp;&nbsp;
                            <input type="reset" class="btn" value="Limpar" id="limpaForm" name="limpaForm" title="Limpa os dados do Formulário"/>
                        </p>
                    </fieldset>
                </form>
            </div>
        </div>

        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function () {
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
                };
            }
        </script>

        <!--<script>
            $('.responsavel').multipleSelect();
        </script>-->

    </body>
</html>
