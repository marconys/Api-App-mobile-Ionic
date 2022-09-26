<?php 
//Inicializa a sessão de usuário
if (Isset($_SESSION)) {
    session_start();
}

//Definindo padrão de Zona GMT (TimeZone) -3.00
date_default_timezone_set('America/Sao_Paulo');

//inicia o carregamento de classes do projeto
spl_autoload_register(function($nome_classe){
    $nome_arquivo = "classes".DIRECTORY_SEPARATOR.$nome_classe.".php";
    if(file_exists($nome_arquivo)){
        require_once($nome_arquivo);
    }
});
?>