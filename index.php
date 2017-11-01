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
        <link rel="stylesheet" href="css/indexTable.css">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/accordion.css">
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
                <!--&nbsp;&nbsp;
                <a href="fecha_racap.php">
                    <input type="button" value="Fechar RACAP" />
                </a>
                &nbsp;&nbsp;
                <a href="acao_racap.php">
                    <input type="button" value="Ações da RACAP" />
                </a>-->
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
                    <input type='button' value='Seções' />
                </a>
                &nbsp;&nbsp;
                <a href='log.php'>
                    <input type='button' value='Log do Sistema' />
                </a>
            </p>
       </div>";


        if ($privilegio == "1") {
            echo $divPanel2;
            echo $divPanel3;
            echo "<br/>";
        }

        if ($privilegio == "1" || $privilegio == "2") {

            $dataAtual = date("Y-m-d 00:00:00");
            $dataFutura = date("Y-m-d 23:59:59", strtotime($dataAtual . '+ 7 days'));

            //Query para popular as tabelas do relatório.
            $query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap, 
            racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

            FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

            WHERE racap_tipo_racap.id = racap_racap.tipo_racap
            AND racap_setor.id = racap_racap.setor_racap
            AND racap_status_racap.id = racap_racap.status_racap
            AND racap_status_racap.id = '1'
            AND prazo_racap BETWEEN '$dataAtual' AND '$dataFutura' ORDER BY racap_racap.id";

            $sql = mysqli_query($conexao, $query);
            $row = mysqli_fetch_assoc($sql);

            if (mysqli_affected_rows($conexao) > 0) {
                //Conta quantos resultados foram encontrados.
                $rowsAffected = mysqli_affected_rows($conexao);

                //Começa a escrever a tabela no documento.
                echo "<table class='reportTable'>
                <tr>
                <td class='reportTableHeader' colspan='7'>RACAP's vencendo nos próximos 7 dias</td>
                </tr>
		<tr>
		<th class='reportTableHeader'>RACAP</th><th class='reportTableHeader'>Aberta Em</th>
                <th class='reportTableHeader'>Tipo</th><th class='reportTableHeader'>Motivo</th>
                <th class='reportTableHeader'>Setor</th><th class='reportTableHeader'>Status</th>
                <th class='reportTableHeader'>Prazo</th></tr>";

                $id = $row['id'];
                $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
                $tipo = $row['tipo'];
                $motivo = $row['motivo_racap'];
                $setor = $row['nomeSetor'];
                $status = $row['status'];
                $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

                echo "<tr>
		<td class='reportTableInfo'>" . $id . "</td><td class='reportTableInfo'>" .
                $dataAbertura . "</td><td class='reportTableInfo'>" . $tipo . "</td>
                <td class='reportTableInfo'>" . $motivo . "</td><td class='reportTableInfo'>" . $setor . "</td>
                <td class='reportTableInfo'>" . $status . "</td><td class='reportTableInfo'>" . $prazoRacap . "</td>
		</tr>";

                while ($row = mysqli_fetch_array($sql)) {

                    $id = $row['id'];
                    $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
                    $tipo = $row['tipo'];
                    $motivo = $row['motivo_racap'];
                    $setor = $row['nomeSetor'];
                    $status = $row['status'];
                    $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

                    echo "<tr>
                    <td class='reportTableInfo'>" . $id . "</td><td class='reportTableInfo'>" .
                    $dataAbertura . "</td><td class='reportTableInfo'>" . $tipo . "</td>
                    <td class='reportTableInfo'>" . $motivo . "</td><td class='reportTableInfo'>" . $setor . "</td>
                    <td class='reportTableInfo'>" . $status . "</td><td class='reportTableInfo'>" . $prazoRacap . "</td>
                    </tr>";
                }
                //Fim da tabela.
                echo "</table>";
            }

            //Caso não haja resultados a serem exibidos no relatório.
            elseif (mysqli_affected_rows($conexao) == 0) {
                echo "<div class='noResult'><h4>Não há RACAP's no Sistema vencendo hoje ou nos próximos 7 dias.</h4></div>";
            }
            
            echo "<br/><br/>";

            //Query para popular as tabelas do relatório.
            $query = "SELECT racap_racap.id, data_racap, racap_tipo_racap.descricao AS 'tipo', motivo_racap, 
            racap_setor.nomeSetor, racap_status_racap.descricao AS 'status', prazo_racap

            FROM racap_racap, racap_tipo_racap, racap_setor, racap_status_racap

            WHERE racap_tipo_racap.id = racap_racap.tipo_racap
            AND racap_setor.id = racap_racap.setor_racap
            AND racap_status_racap.id = racap_racap.status_racap
            AND status_racap = '1'
            AND prazo_racap < '$dataAtual' ORDER BY racap_racap.id";

            $sql = mysqli_query($conexao, $query);
            $row = mysqli_fetch_assoc($sql);

            if (mysqli_affected_rows($conexao) > 0) {
                //Conta quantos resultados foram encontrados.
                $rowsAffected = mysqli_affected_rows($conexao);

                //Começa a escrever a tabela no documento.
                echo "<table class='reportTable'>
                <tr>
                <td class='reportTableHeader' colspan='7'>RACAP's Vencidas ainda Pendentes</td>
                </tr>
		<tr>
		<th class='reportTableHeader'>RACAP</th><th class='reportTableHeader'>Aberta Em</th>
                <th class='reportTableHeader'>Tipo</th><th class='reportTableHeader'>Motivo</th>
                <th class='reportTableHeader'>Setor</th><th class='reportTableHeader'>Status</th>
                <th class='reportTableHeader'>Prazo</th></tr>";

                $id = $row['id'];
                $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
                $tipo = $row['tipo'];
                $motivo = $row['motivo_racap'];
                $setor = $row['nomeSetor'];
                $status = $row['status'];
                $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

                echo "<tr>
		<td class='reportTableInfo'>" . $id . "</td><td class='reportTableInfo'>" .
                $dataAbertura . "</td><td class='reportTableInfo'>" . $tipo . "</td>
                <td class='reportTableInfo'>" . $motivo . "</td><td class='reportTableInfo'>" . $setor . "</td>
                <td class='reportTableInfo'>" . $status . "</td><td class='reportTableInfo'>" . $prazoRacap . "</td>
		</tr>";

                while ($row = mysqli_fetch_array($sql)) {

                    $id = $row['id'];
                    $dataAbertura = date('d/m/Y H:i:s', strtotime($row['data_racap']));
                    $tipo = $row['tipo'];
                    $motivo = $row['motivo_racap'];
                    $setor = $row['nomeSetor'];
                    $status = $row['status'];
                    $prazoRacap = date('d/m/Y H:i:s', strtotime($row['prazo_racap']));

                    echo "<tr>
                    <td class='reportTableInfo'>" . $id . "</td><td class='reportTableInfo'>" .
                    $dataAbertura . "</td><td class='reportTableInfo'>" . $tipo . "</td>
                    <td class='reportTableInfo'>" . $motivo . "</td><td class='reportTableInfo'>" . $setor . "</td>
                    <td class='reportTableInfo'>" . $status . "</td><td class='reportTableInfo'>" . $prazoRacap . "</td>
                    </tr>";
                }
                //Fim da tabela.
                echo "</table>";
            }

            //Caso não haja resultados a serem exibidos no relatório.
            elseif (mysqli_affected_rows($conexao) == 0) {
                echo "<div class='noResult'><h4>Não há RACAP's Vencidas que estejam Pendentes.</h4></div>";
            }
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
