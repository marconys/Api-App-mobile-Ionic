<?php 
Class Evento{
   //Atributos
   private $id;
   private $nome;
   private $data_evento;
   private $capacidade;
   private $ativo;
   private $usuarios_id;
   private $imagem;
   
   //Declaração métodos de acesso (Getters an Setters)
   public function getId(){return $this->id;}
   public function getNome(){ return $this->nome;}
   public function getDataEvento(){return $this->data_evento;}
   public function getCapacidade(){return $this->capacidade;}
   public function getAtivo(){return $this->ativo;}
   public function getUsuariosId(){return $this->usuarios_id;}
   public function getImagem(){return $this->imagem;}
   

   public function setId($value){$this->id = $value;}
   public function setNome($value){$this->nome = $value;}
   public function setDataEvento($value){$this->data_evento = $value;}
   public function setCapacidade($value){$this->capacidade = $value;}   
   public function setAtivo($value){$this->ativo = $value;}
   public function setUsuariosId($value){$this->usuarios_id = $value;}
   public function setImagem($value){return $this->imagem = $value;}

   public function loadById($_id){
    $sql = new Sql();
    $results = $sql->select("SELECT * FROM eventos WHERE id = :id", array(':id' => $_id));
    if (count($results)>0) {
        $this->setData($results[0]);
    }
   }

   public function setData($dados){
    $this->setId($dados['id']);
    $this->setNome($dados['nome']);
    $this->setDataEvento($dados['data_evento']);
    $this->setCapacidade($dados['capacidade']);
    $this->setAtivo($dados['ativo']);
    $this->setUsuariosId($dados['usuarios_id']);
    $this->setImagem($dados['imagem']);

   }
   public static function getList(){
    $sql = new Sql();
    return $sql->select("SELECT * FROM eventos ORDER BY nome");
   }

   public static function search($_nome){
    $sql = new Sql();
    return $sql->select("SELECT * FROM eventos WHERE nome LIKE :nome",
    array(":nome"=>"%".$_nome."%"));
   }

   public function insert(){
    $sql = new Sql();
    $res = $sql->select("CALL sp_event_insert(:nome, :data_evento, :capacidade, :usuarios_id, :imagem)", 
    array(
        ":nome" =>$this->getNome(),
        ":data_evento" =>$this->getDataEvento(),
        ":capacidade" =>$this->getCapacidade(),
        ":usuarios_id" =>$this->getUsuariosId(),
        ":imagem" =>$this->getImagem()

    ));

    if (count($res)>0) {
       $this->setData($res[0]);
    }
   }

   public function update(){
    $sql = new Sql();
    $sql->querySql("UPDATE eventos SET nome = :nome, data_evento = :data_evento, capacidade = :capacidade, 
    imagem = :imagem WHERE id = :id", array(

        ":nome" =>$this->getNome(),
        ":id" =>$this->getId(),
        ":data_evento" =>$this->getDataEvento(),
        ":capacidade" =>$this->getCapacidade(),
        ":imagem" =>$this->getImagem()

    ));
   }

   public function delete($_id)
    {
        $sql = new Sql();
        $res = $sql->querySql("UPDATE eventos SET ativo = 0 WHERE id = :id", array(":id" => $_id));
        return $res;
    }

   public function ativar()
   {
       $sql = new Sql();
       $sql->querySql("UPDATE eventos set ativo = 1 WHERE id = :id", array(":id" => $this->getId()));
   }

   public function __construct($_nome = "", $data_evento = "", $capacidade = "", $_ativo = "", $usuarios_id = ""){
        $this->nome = $_nome;
        $this->data_evento = $data_evento;
        $this->capacidade = $capacidade;
        $this->ativo = $_ativo;
        $this->usuarios_id = $usuarios_id;
   }


}
?>