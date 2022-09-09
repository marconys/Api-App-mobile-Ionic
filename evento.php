<?php 
//Api - Aplicação para recursos de app mobile
include_once('conn.php');


//Eventos
//Variável que recebe o conteúdo da requisição do APP decodificando-a (json)
$postjson = json_decode(file_get_contents('php://input', true), true);

if ($postjson['requisicao'] == 'addevent') {
    $query = $pdo->prepare("insert into eventos set nome = :nome, data_evento = :data_evento, capacidade = :capacidade, usuarios_id = :usuarios_id, ativo = 1");
    
    $old_date = strtotime($postjson['data_evento']);
    $new_date = date('d-m-Y H:i:s', $old_date);
    
    
    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":data_evento", $new_date);
    $query->bindValue(":capacidade", $postjson['capacidade']);
    $query->bindValue(":usuarios_id", $postjson['usuarios_id']);

    $query->execute();

    $id = $pdo->lastInsertId();

    if ($query) {
        $result = json_encode(array('success' => true, 'id' => $id));
    } else {
        $result = json_encode(array('success' => false, 'msg' => 'Falha ao inserir evento'));
    }

    echo $result;
} //Final requisição add

else if($postjson['requisicao'] == 'listarevent'){
    if($postjson['nome'] == ''){
        $query = $pdo->query("SELECT * FROM eventos ORDER BY id DESC LIMIT $postjson[start], $postjson[limit]");
    } else{
        $busca = '%' . $postjson['nome'] . '%';
        $query = $pdo->query("SELECT * FROM eventos WHERE nome LIKE '$busca' OR usuarios_id LIKE '$busca' ORDER BY id DESC LIMIT $postjson[start], $postjson[limit]");
    }

    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < count($res); $i++){
        $dados[][] = array(
            'id' => $res[$i]['id'],
            'nome' => $res[$i]['nome'],
            'data_evento' => $res[$i]['data_evento'],
            'capacidade' => $res[$i]['capacidade'],
            'usuarios_id' => $res[$i]['usuarios_id'],
            'ativo' => $res[$i]['ativo']
        );
    }
    if (count($res) > 0) {
        $result = json_encode(array('success' => true, 'result' => $dados));
    } else {
        $result = json_encode(array('success' => false, 'result' => '0'));
    }
    echo $result;
}// Fim do listarevent

else if ($postjson['requisicao'] == 'editarevent') {
    $query = $pdo->prepare("UPDATE eventos SET nome=:nome, data_evento =:data_evento, capacidade =:capacidade, ativo=:ativo WHERE id=:id");
    $query->bindValue(":nome", $postjson['nome']);
    $query->bindValue(":data_evento", $postjson['data_evento']);
    $query->bindValue(":capacidade", md5($postjson['capacidade']));
    $query->bindValue(":id", $postjson['id']);
    $query->execute();

    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Alteração realizada com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Dados incorretos! Falha ao atualizar evento!"));
    }
    echo $result;
} //Final da requisição editarevent

else if ($postjson['requisicao'] == 'excluirevent') {
    $query = $pdo->query("DELETE FROM eventos WHERE id = $postjson[id]");
    //$query = $pdo->query("UPDATE eventos SET ativo = 0 WHERE id = $postjson[id]");
    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Eventoexcluido com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao excluir o Evento!"));
    }
    echo $result;
} //Final do excluirevent

else if ($postjson['requisicao'] == 'ativarevent') {
    $query = $pdo->query("UPDATE eventos SET ativo = 1 WHERE id = $postjson[id]");
    if ($query) {
        $result = json_encode(array('success' => true, 'msg' => "Evento ativado com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao ativar o evento!"));
    }
    echo $result;
}//Final ativar
?>

