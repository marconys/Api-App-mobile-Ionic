<?php
//Api - Aplicação para recursos de app mobile
include_once('conn.php');

//Variável que recebe o conteúdo da requisição do APP decodificando-a (json)
$postjson = json_decode(file_get_contents('php://input', true), true);

if ($postjson['requisicao'] == 'add') {
    $query = $pdo->prepare("insert into usuarios set nome = :nome, usuario = :usuario, senha = :senha, senha_original = :senha_original, nivel = :nivel, ativo = 1");
    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":usuario", $postjson['usuario']);
    $query->bindValue(":senha", md5($postjson['senha']));
    $query->bindValue(":senha_original", $postjson['senha']);
    $query->bindValue(":nivel", $postjson['nivel']);

    $query->execute();

    $id = $pdo->lastInsertId();

    if ($query) {
        $result = json_encode(array('success' => true, 'id' => $id));
    } else {
        $result = json_encode(array('success' => false, 'msg' => 'Falha ao inserir usuário'));
    }

    echo $result;
} //Final requisição add
else if ($postjson['requisicao'] == 'listar') {
    if ($postjson['nome'] == '') {
        $query = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT $postjson[start], $postjson[limit]");
    } else {
        $busca = '%' . $postjson['nome'] . '%';
        $query = $pdo->query("SELECT * FROM usuarios WHERE nome LIKE '$busca' OR usuario LIKE '$busca' ORDER BY id DESC LIMIT $postjson[start], $postjson[limit]");
    }
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($res); $i++) {
        $dados[][] = array(
            'id' => $res[$i]['id'],
            'nome' => $res[$i]['nome'],
            'usuario' => $res[$i]['usuario'],
            'senha' => $res[$i]['senha'],
            'senha_original' => $res[$i]['senha_original'],
            'nivel' => $res[$i]['nivel'],
            'ativo' => $res[$i]['ativo']
        );
    }
    if (count($res) > 0) {
        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'result' => '0'));
    }
    echo $result;
} // Fim do listar
else if ($postjson['requisicao'] == 'editar') {
    $query = $pdo->prepare("UPDATE usuarios SET nome=:nome, usuario=:usuario, senha=:senha, senha_original=:senha_original, nivel=:nivel WHERE id=:id");
    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":usuario", $postjson['usuario']);
    $query->bindValue(":senha", md5($postjson['senha']));
    $query->bindValue(":senha_original", $postjson['senha']);
    $query->bindValue(":nivel", $postjson['nivel']);
    $query->bindValue(":id", $postjson['id']);
    $query->execute();

    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Alteração realizada com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Dados incorretos! Falha ao atualizar usuário! (WRH014587)"));
    }
    echo $result;
} //Final da requisição Editar
else if ($postjson['requisicao'] == 'excluir') {
    //$query = $pdo->query("DELETE FROM usuarios WHERE id = $postjson[id]");
    $query = $pdo->query("UPDATE usuarios SET ativo = 0 WHERE id $postjson[id]");
    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Usuário excluido com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao excluir o usuário!"));
    }
    echo $result;
} //Final do excluir
else if ($postjson['requisicao'] == 'login') {
    $query = $pdo->query("SELECT * FROM usuarios WHERE usuario = '$postjson[usuario]' AND senha = MD5('$postjson[senha]') AND ATIVO = 1");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($res); $i++) {
        $dados = array(
            'id' => $res[$i]['id'],
            'nome' => $res[$i]['nome'],
            'usuario' => $res[$i]['usuario'],
            'nivel' => $res[$i]['nivel'],
            'ativo' => $res[$i]['ativo']
        );
        if ($query) {
            $result = json_encode(array('success' => true, 'result' => $dados));
        } else {
            $result = json_encode(array('success' => false, 'msg' => "Usuário ou senha incorretos"));
        }
        echo $result;
    }
} //Final do login

else if ($postjson['requisicao'] == 'ativar') {
    $query = $pdo->query("UPDATE usuarios SET ativo = 1 WHERE id $postjson[id]");
    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Usuário excluido com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao excluir o usuário!"));
    }
    echo $result;
}
