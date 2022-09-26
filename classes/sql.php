<?php 
Class Sql extends PDO{
    private $cn;
    public function __construct(){
        $this->cn = new PDO("mysql:host=127.0.0.1;dbname=controledb", "root", "");
    }

    //Método que atribui parametros para uma query sql
    public function setParams($comando, $colectParametros = array()){
        foreach($colectParametros as $key => $value){
            $this->setParam($comando, $key, $value);
        }
    }

    //Método para tratar o parametro
    public function setParam($cmd, $key, $value){
        $cmd->bindParam($key, $value);
    }
    //Executa comandos SQL NO BANCO
    public function querySql($comandoSql, $params = array()){
        $cmd = $this->cn->prepare($comandoSql);
        $this->setParams($cmd, $params);
        $cmd->execute();
        return $cmd;
    }

    public function select($comandoSql, $params = array()){
        $cmd = $this->querySql($comandoSql, $params);
        return $cmd->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>