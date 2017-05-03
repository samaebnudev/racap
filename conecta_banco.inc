<?php
  define("DB_SERVER", "localhost");
  define("DB_USERNAME", "parais");
  define("DB_PASSWORD", "pra290388");
  define("DB_DATABASE", "samaenet");
  $conexao = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or print (mysqli_error($conexao));
  
  mysqli_query($conexao,"SET NAMES 'utf8'");
  mysqli_query($conexao,'SET character_set_connection=utf8');
  mysqli_query($conexao,'SET character_set_client=utf8');
  mysqli_query($conexao,'SET character_set_results=utf8');
?>
