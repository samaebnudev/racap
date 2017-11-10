<script type="text/javascript">

    function voltar() {
        window.location.href = 'login.php';
    }

</script>

<?php
session_start();
date_default_timezone_set('Brazil/East');
header("Content-type: text/html; charset=utf-8");

// Fix for removed Session functions
function fix_session_register() {

    function session_register() {
        $args = func_get_args();
        foreach ($args as $key) {
            $_SESSION[$key] = $GLOBALS[$key];
        }
    }

    function session_is_registered($key) {
        return isset($_SESSION[$key]);
    }

    function session_unregister($key) {
        unset($_SESSION[$key]);
    }

}

if (!function_exists('session_register')) {
    fix_session_register();
}

include "conecta_banco.inc";
include "getIP.php";

$login = $_POST['usuario'];
$password = $_POST['senha'];

$query = "SELECT * FROM racap_usuario WHERE matServidor = '$login'";
$sql = mysqli_query($conexao, $query);

if (mysqli_affected_rows($conexao) == 1) {

    $row = mysqli_fetch_assoc($sql);

    if (password_verify($password, $row['senha']) and $row['flgAtivo'] == "S") {

        $idUsuario = $row ['id'];
        $tipoPrivilegio = $row ['perfil_acesso'];
        $flgAtivo = $row ['flgAtivo'];
        $nomeUsuario = $row ['nomeServidor'];

        $_SESSION ['id'] = $idUsuario;
        $_SESSION ['tipoPrivilegio'] = $tipoPrivilegio;
        $_SESSION ['nomeUsuario'] = $row ['nomeServidor'];
        $_SESSION ['senha'] = $password;
        $_SESSION ['flgAtivo'] = $flgAtivo;

        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Entrou no Sistema.";
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
        $sql = mysqli_query($conexao, $query);

        //Procedimento de Atualização de Status das RACAP's
        $dataParametro = date("Y-m-d 00:00:00");
        $query = "SELECT * FROM racap_parametros";
        $sql = mysqli_query($conexao, $query);
        $row = mysqli_fetch_array($sql);
        $dataParametroBanco = date("Y-m-d H:i:s", strtotime($row['data_verificacao']));

        //Se a última verificação de Atualização de RACAP's foi no dia anterior,
        //verifica se tem RACAP's vencidas com Status Aberta a fim de colocá-las
        //como Pendente.
        if ($dataParametro > $dataParametroBanco) {
            $query = "UPDATE racap_racap SET status_racap= '2' WHERE prazo_racap < '$dataParametro' AND status_racap = '1'";
            $sql = mysqli_query($conexao, $query);

            if ($sql) {
                $ocorrencia = "Atualização Automática de Status da RACAP.";
                $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
                $sql = mysqli_query($conexao, $query);
            }
           
            $query = "UPDATE racap_parametros SET data_verificacao = '$dataParametro' WHERE id='1'";
            $sql = mysqli_query($conexao, $query);
        }

        mysqli_close($conexao);
        header("Location:index.php");
    } elseif (password_verify($password, $row['senha']) and $row['flgAtivo'] == "N") {

        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Tentativa de login com usuário inativo.";
        $nomeUsuario = $row ['nomeServidor'];
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
        $sql = mysqli_query($conexao, $query);

        echo "<script> alert ('ESTE USUÁRIO FOI DESATIVADO PELO ADMINISTRADOR DO SISTEMA.');</script>";
        mysqli_close($conexao);
        echo "<script>voltar ();</script>";
    } elseif (!password_verify($password, $row['senha'])) {

        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Tentativa de login. Senha Incorreta.";
        $nomeUsuario = $row ['nomeServidor'];
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
		VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
        $sql = mysqli_query($conexao, $query);

        echo "<script> alert ('SENHA INCORRETA.');</script>";
        mysqli_close($conexao);
        echo "<script>voltar ();</script>";
    }
} else {
    $dataRegistro = date("Y-m-d H:i:s");
    $ocorrencia = "Tentativa de login. Usuário e/ou senha incorreto(s).";
    $ip = get_client_ip_env();
    $nomeUsuario = $login;
    $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
	VALUES ('0', '$dataRegistro', '$ocorrencia', '$nomeUsuario', '$ip')";
    $sql = mysqli_query($conexao, $query);

    echo "<script> alert ('USUÁRIO E/OU SENHA INCORRETOS.');</script>";
    mysqli_close($conexao);
    echo "<script>voltar ();</script>";
}
?>