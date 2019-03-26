<?php
Class Echeancier{
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select * from echeancier");
        $this->insert=$db->prepare("insert into echeancier(libelle) values(:libelle);");
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($libelle){
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}
?>