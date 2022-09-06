<?php 
//Api - Aplicação para recursos de app mobile
include_once('conn.php');

//Variável que recebe o conteúdo da requisição do APP decodificando-a (json)
$postjson = json_decode(file_get_contents('php://input', true), true);

if($postjson['requisicao'] == 'add'){
    $query = $pdo->prepare("insert into usuarios set nome = :nome, usuario = :usuario senha = :senha, senha_original = :senha_original, nivel = :nivel, ativo = 1");
    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":usuario", $postjson['usuario']);
    $query->bindValue(":senha",md5($postjson['senha']));
    $query->bindValue(":senha_original", $postjson['senha_original']);
    $query->bindValue(":nivel", $postjson['nivel']);

    $query->execute();
    
}
?>