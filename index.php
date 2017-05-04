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
    <title>Controle de RACAP's - Home</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/accordion.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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
<input type="button" value="Abrir RACAP" />
&nbsp;&nbsp;
<input type="button" value="Fechar RACAP" />
&nbsp;&nbsp;
<input type="button" value="Ações da RACAP" />
&nbsp;&nbsp;
<input type="button" value="Relatórios de RACAP's" />
</p>
<br/>
</div>

<button class="accordion">Configuração das RACAP's</button>
<div class="panel">
<p align="center">
<input type="button" value="Tipos de RACAP" />
&nbsp;&nbsp;
<input type="button" value="Status de RACAP" />
&nbsp;&nbsp;
<input type="button" value="Causas de RACAP" />
&nbsp;&nbsp;
<input type="button" value="Status das Ações" />
&nbsp;&nbsp;
<input type="button" value="Motivos de Abertura de RACAP's" />
</p>
<br/>
</div>

<button class="accordion">Configurações Adicionais</button>
<div class="panel">
<p align="center">
<input type="button" value="Usuários" />
&nbsp;&nbsp;
<input type="button" value="Tipos de Usuários" />
&nbsp;&nbsp;
<input type="button" value="Setores" />
&nbsp;&nbsp;
<input type="button" value="Log do Sistema" />
</p>
</div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function(){
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
  }
}
</script>
  
 </body>
</html>
