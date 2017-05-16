<script type="text/javascript">
    function mensagem(chamada, texto) {
        alert(chamada + ": " + texto);
    }

    function voltar() {
        window.location.href = 'usuario.php';
    }
</script>

<?php
session_start();
date_default_timezone_set('Brazil/East');
include "conecta_banco.inc";
include "getIP.php";

if (isset($_POST['sequencial'])) {
    $sequencial = $_POST ['sequencial'];
} else {
    $sequencial = "0";
}

$matUsuario = $_POST ['matUsuario'];
$nomeUsuario = $_POST['nomeUsuario'];
$setorUsuario = $_POST['setorUsuario'];

$perfilUsuario = $_POST['perfilUsuario'];


$senha = password_hash($_POST['senhaUsuario'], PASSWORD_DEFAULT);
//$senha = crypt ($_POST['senhaUsuario']);

if (isset($_POST['flgAtivo'])) {
    $flgAtivo = $_POST['flgAtivo'];
} else {
    $flgAtivo = "N";
}

$query = "SELECT * FROM racap_usuario WHERE id = '$sequencial'";
$sql = mysqli_query($conexao, $query);
$row = mysqli_fetch_assoc($sql);

if (mysqli_affected_rows($conexao) == 1) {
    $query = "UPDATE racap_usuario SET matServidor = '$matUsuario',
	nomeServidor = '$nomeUsuario', 	setor='$setorUsuario', 
        senha = '$senha', flgAtivo = '$flgAtivo', perfil_acesso = '$perfilUsuario'
	WHERE id = '$sequencial'";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = utf8_encode("Alterou usuário ID: " . $sequencial);
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log 
         (id, dataRegistro, ocorrencia, usuario, ip) 
	 VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Usuário alterado com sucesso.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {

        $message = "<script> alert ('Falha na alteração. Usuário não pôde ser alterado.');</script>";
        echo $message;
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
} elseif (mysqli_affected_rows($conexao) == 0) {
    $query = "INSERT INTO racap_usuario (id, matServidor, nomeServidor, 
        setor, 	senha, 	flgAtivo, perfil_acesso)
	VALUES ('$sequencial', '$matUsuario', '$nomeUsuario',
            '$setorUsuario', '$senha', '$flgAtivo', '$perfilUsuario')";

    $sql = mysqli_query($conexao, $query);

    if ($sql) {

        $login = $_SESSION ['nomeUsuario'];
        $dataRegistro = date("Y-m-d H:i:s");
        $ocorrencia = "Incluiu usuário: " . $nomeUsuario;
        $ip = get_client_ip_env();
        $query = "INSERT INTO racap_log (id, dataRegistro, ocorrencia, usuario, ip) 
			VALUES ('0', '$dataRegistro', '$ocorrencia', '$login', '$ip')";
        $sql = mysqli_query($conexao, $query);

        $message = "<script> alert ('Usuário incluído com sucesso.');</script>";
        echo utf8_decode($message);
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    } else {
        $message = "<script> alert ('Falha na inclusão. Usuário não pôde ser inserido.');</script>";
        echo utf8_decode($message);
        $urlBack = "<script>voltar ();</script>";
        echo $urlBack;
    }
}