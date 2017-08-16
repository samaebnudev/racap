<?php
session_start();
include "conecta_banco.inc";

if ($_SESSION['nomeUsuario'] == '') {
    header("Location:login.php");
}
$privilegio = $_SESSION['tipoPrivilegio'];
?>

<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Controle de RACAP's - Home</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/accordion.css">
        <link rel="stylesheet" href="css/indexTable.css">
        <script type="text/javascript" 
                src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js">
        </script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="topbar">
            <h2 align="center">Controle de RACAP - Home</h2>
            <div  class="open">
                <span class="cls"></span>
                <span>
                    <ul class="sub-menu ">
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
        <button class="accordion">RACAP's</button>
        <div class="panel">
            <p align="center">
                <a href="racap.php">
                    <input type="button" value="Abrir RACAP"/>
                </a>
                &nbsp;&nbsp;
                <a href="fecha_racap.php">
                    <input type="button" value="Fechar RACAP" />
                </a>
                &nbsp;&nbsp;
                <a href="acao_racap.php">
                    <input type="button" value="Ações da RACAP" />
                </a>
                &nbsp;&nbsp;
                <a href="relatorio.php">
                    <input type="button" value="Relatórios de RACAP's" />
                </a>
            </p>
            <br/>
        </div>

        <?php
        $divPanel2 = "<hr><button class='accordion'>Configuração das RACAP's</button>
        <div class='panel'>
            <p align='center'>
                <a href='tipo_racap.php'>
                    <input type='button' value='Tipos de RACAP' />
                </a>

                &nbsp;&nbsp;
                <a href='status_racap.php'>
                    <input type='button' value='Status de RACAP' />
                </a>
                &nbsp;&nbsp;
                <a href='causa_racap.php'>
                    <input type='button' value='Causas de RACAP' />
                </a>
                &nbsp;&nbsp;
                <a href='status_acao.php'>
                    <input type='button' value='Status das Ações' />
                </a>
                &nbsp;&nbsp;
                <a href='motivo_abertura_racap.php'>
                    <input type='button' value='Motivos de Abertura de RACAP' />
                </a>
            </p>
            <br/>
        </div>";

        $divPanel3 = "<hr><button class='accordion'>Configurações Adicionais</button>
        <div class='panel'>
            <p align='center'>
                <a href='usuario.php'>
                    <input type='button' value='Usuários' />
                </a>
                &nbsp;&nbsp;
                <a href='perfil_usuario.php'>
                    <input type='button' value='Tipos de Usuários' />
                </a>
                &nbsp;&nbsp;
                <a href='setores.php'>
                    <input type='button' value='Setores' />
                </a>
                &nbsp;&nbsp;
                <a href='log.php'>
                    <input type='button' value='Log do Sistema' />
                </a>
            </p>
       </div>";
        
        $dataAtual = date("d/m/Y H:i:s");

        //Query para popular as tabelas do relatório.
        $query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap, 
racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

WHERE racap_tipo_racap.id = racap_racap.tipo_racap
AND racap_setor.id = racap_racap.setor_racap
AND racap_status_racap.id = racap_racap.status_racap
AND prazo_racap BETWEEN '$dataIni' AND '$dataFim' ORDER BY racap_racap.id";

        $divTableVencimento = "<table class='reportTable'>"
                . "<tr><td class='reportTableHeader'>RACAP</td><td class='reportTableHeader'>Vencimento</td></tr>"
                . "<tr><td class='reportTableInfo'>1</td><td class='reportTableInfo'>05/05/15</td></tr>"
                . "</table>";

        if ($privilegio == "1") {
            echo $divPanel2;
            echo $divPanel3;
            echo "<br/>";
            echo $divTableVencimento;
        }
        ?>

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

    </body>
</html>
