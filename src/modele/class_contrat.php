<?php
Class Contrat{
    private $db;
    private $select;
    private $insert;
    private $selectByID;
    private $update;
    private $delete;
    
    public function __construct($db) {
        $this->db=$db;
        $this->select=$db->prepare("select contrat.id as idContrat, nomEntreprise,entreprise.id as idEntreprise,echeancier.id as idEcheancier, echeancier.libelle as libelle, coutGlobal, delaiProd, DATE_FORMAT(dateSignature,'%d/%m/%Y') as dateSignature from contrat inner join entreprise on contrat.idEnt=entreprise.id inner join echeancier on contrat.idEch=echeancier.id");
        $this->insert=$db->prepare("insert into contrat(delaiProd,dateSignature,coutGlobal,idEch,idEnt) values(:delaiProd,:dateSignature,:coutGlobal,:idEch,:idEnt);");
        $this->selectByID=$db->prepare("select contrat.id as idContrat, nomEntreprise,entreprise.id as idEntreprise,echeancier.id as idEcheancier, echeancier.libelle as libelle, coutGlobal, delaiProd, dateSignature from contrat inner join entreprise on contrat.idEnt=entreprise.id inner join echeancier on contrat.idEch=echeancier.id where contrat.id=:id");
        $this->update=$db->prepare("update contrat set delaiProd=:delaiProd, dateSignature=:dateSignature, coutGlobal=:coutGlobal, idEch=:idEch, idEnt=:idEnt where id=:id");
        $this->delete=$db->prepare("delete from contrat where id=:id;");
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($delaiProd,$dateSignature,$coutGlobal,$idEch,$idEnt){
        $r = true;
        $this->insert->execute(array(':delaiProd'=>$delaiProd,':dateSignature'=>$dateSignature,':coutGlobal'=>$coutGlobal,':idEch'=>$idEch,':idEnt'=>$idEnt));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectByID($id){ 
        $this->selectByID->execute(array(':id'=>$id)); 
        if ($this->selectByID->errorCode()!=0){
            print_r($this->selectByID->errorInfo()); 
            
        }
        return $this->selectByID->fetch(); 
    }
    
    public function update($delaiProd, $dateSignature, $coutGlobal, $idEch, $idEnt, $id){
        $r = true;
        $this->update->execute(array(':delaiProd'=>$delaiProd, ':dateSignature'=>$dateSignature, ':coutGlobal'=>$coutGlobal, ':idEch'=>$idEch,':idEnt'=>$idEnt,':id'=>$id));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
}
?>