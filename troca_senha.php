<?php
session_start();
include "conecta_banco.inc";

if ($_SESSION['nomeUsuario']==''){
    header("Location:login.php");
    }
	
	$privilegio = $_SESSION['tipoPrivilegio'];
?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Controle de Glosas - Home</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/form.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="js/index.js"></script>
	<script src="js/troca_senha.js"></script>
  </head>

<body>
<div class="topbar">
<p>
&nbsp;&nbsp;&nbsp;<?php echo "Usuário(a): ".$_SESSION['nomeUsuario'];?>
</p>
<div  class="open">
	<span class="cls"></span>
	<span>
		<ul class="sub-menu ">
			<li>
				<a href="glosa.php" >Glosas</a>
			</li>
			<li>
				<a href="glosa_relatorio.php">Relatório de Glosas</a>
			</li>
			<?php
			if ($privilegio == "1" or $privilegio == "2"){
				echo "<li>
					<a href='tipo_glosa.php'>Tipos de Glosas</a>
				</li>
				<li>
					<a href='competencia.php'>Competências</a>
				</li>
				<li>
					<a href='usuario.php'>Usuários</a>
				</li>
				<li>
					<a href='log.php'>Log do Sistema</a>
				</li>";
			}
			?>
			<li>
				<a href="logout.php">Sair</a>
			</li>
		</ul>
	</span>
	<span class="cls"></span>
</div>
</div>
<h3>Troca de Senha</h3>
<form method="POST" id="trocaSenha" action ="troca_senha_manage.php" onsubmit= "return verificaSenha ()">

<label for="senhaAtual">Senha Atual: </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="password" name="senhaAtual" id="senhaAtual" required/>
<input type="button" value="Mostrar" onclick="mostraSenha (0)"/>
<br/><br/>

<label for="novaSenha">Nova Senha: </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="password" name="novaSenha" id="novaSenha" required/>
<input type="button" value="Mostrar" onclick="mostraSenha (1)"/>
<br/><br/>

<label for="novaSenha2">Re-Digite a Senha: </label>
<input type="password" name="novaSenha2" id="novaSenha2" required/>
<input type="button" value="Mostrar" onclick="mostraSenha (2)"/>
<br/>

<p align="center"><input type="submit" value="Atualizar Senha"/>
&nbsp;&nbsp;<a href="index2.php"><input type="button" value="Voltar"/></a>
</p>
</form>
</body>
</html>