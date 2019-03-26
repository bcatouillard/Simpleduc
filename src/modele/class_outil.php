<?php
Class Outil{
    private $db;
    private $select;
    private $insert;
    private $delete;
    private $update;
    private $selectByCode;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select * from outils");
        $this->insert=$db->prepare("insert into outils(libelle, version, descriptif) values(:libelle, :version, :descriptif)");
        $this->delete=$db->prepare("delete from outils where code=:code");
        $this->update=$db->prepare("update outils set libelle=:libelle, version=:version, descriptif=:descriptif where code=:code");
        $this->selectByCode=$db->prepare("select * from outils where code=:code");
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($libelle, $version, $descriptif){
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle, ':version'=>$version,':descriptif'=>$descriptif));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($code){
        $r = true;
        $this->delete->execute(array(':code'=>$code));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($code, $libelle, $version, $descriptif){
        $r = true;
        $this->update->execute(array(':code'=>$code, ':libelle'=>$libelle, ':version'=>$version,':descriptif'=>$descriptif));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectByCode($code){ 
        $this->selectByCode->execute(array(':code'=>$code)); 
        if ($this->selectByCode->errorCode()!=0){
            print_r($this->selectByCode->errorInfo()); 
            
        }
        return $this->selectByCode->fetch(); 
    }
}