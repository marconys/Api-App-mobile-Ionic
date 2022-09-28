<?php 
require('config.php');
//Api - Aplicação para recursos de app mobile
//include_once('conn.php');


//Eventos
//Variável que recebe o conteúdo da requisição do APP decodificando-a (json)
$postjson = json_decode(file_get_contents('php://input', true), true);

if ($postjson['requisicao'] == 'addevent') {
    $event = new Evento();   
    
      
    $new_date = DateTime::createFromFormat('d/m/Y', $postjson['data_evento']);
    $new_date = $new_date->format('Y-m-d');
    
    
    $event->setNome($postjson['nome']);
    $event->setDataEvento($new_date);
    $event->setCapacidade($postjson['capacidade']);
    $event->setUsuariosId($postjson['usuarios_id']);

    $event->insert();
    

    if ($event->getId()) {
        $result = json_encode(array('success' => true, 'id' => $event->getId()));
    } else {
        $result = json_encode(array('success' => false, 'msg' => 'Falha ao inserir evento'));
    }

    echo $result;
} //Final requisição add

else if($postjson['requisicao'] == 'listarevent'){
    
    $event = new Evento();
    if ($postjson['nome'] == '') {
      $res = Evento::getList();
    } else {
        
        $res = $event->search($postjson['nome']);        
    }
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
    $event = new Evento();
    $event->setId($postjson['id']);
    $event->setNome($postjson['nome']);
    $event->setDataEvento($postjson['data_evento']);
    $event->setCapacidade($postjson['capacidade']);
    $event->setAtivo($postjson['ativo']);
    $event->setUsuariosId($postjson['usuarios_id']);

    if ($event->update()) {
        $result = json_encode(array('success' => true, 'msg' => "Alteração realizada com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Dados incorretos! Falha ao atualizar evento!"));
    }
    echo $result;
} //Final da requisição editarevent

else if ($postjson['requisicao'] == 'excluirevent') {
    $event = new Evento();
    $res = $event->delete($postjson['id']);
    //$query = $pdo->query("UPDATE eventos SET ativo = 0 WHERE id = $postjson[id]");
    if ($res) {
        $result = json_encode(array('success' => true, 'msg' => "Eventoexcluido com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao excluir o Evento!"));
    }
    echo $result;
} //Final do excluirevent

else if ($postjson['requisicao'] == 'ativarevent') {
    $event = new Evento();
    $event->setId($postjson['id']);
    $res = $user->ativar();
    if ($res) {
        $result = json_encode(array('success' => true, 'msg' => "Evento ativado com sucesso"));
    } else {
        $result = json_encode(array('success' => false, 'msg' => "Falha ao ativar o evento!"));
    }
    echo $result;
}//Final ativar
?>

