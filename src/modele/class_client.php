<?php
Class Client{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByID;
    
    //Afficher pour chaque client les différents projets avec la date de signature du contrat correspondant.
    private $selectContrat;
    
    public function __construct($db){
        $this->db = $db;
        $this->select=$db->prepare("select * from entreprise");
        $this->insert=$db->prepare("insert into entreprise(nomEntreprise,adresse,telephone,mailContact) values(:nomEntreprise,:adresse,:telephone,:mailContact)");
        $this->update=$db->prepare("update entreprise set nomEntreprise=:nomEntreprise, adresse=:adresse, telephone=:telephone, mailContact=:mailContact where id=:id");
        $this->delete=$db->prepare("delete from entreprise where id=:id");
        $this->selectByID=$db->prepare("select * from entreprise where id=:id");
        
        $this->selectContrat=$db->prepare("select nomEntreprise, libelle, dateSignature from entreprise inner join contrat on entreprise.id=contrat.idEnt inner join projet on contrat.id=projet.idContrat group by nomEntreprise");
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($nomEntreprise, $adresse, $telephone, $mailContact){
        $r = true;
        $this->insert->execute(array(':nomEntreprise'=>$nomEntreprise, ':adresse'=>$adresse, ':telephone'=>$telephone, ':mailContact'=>$mailContact));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($nomEntreprise, $adresse, $telephone, $mailContact,$id){
        $r = true;
        $this->update->execute(array(':nomEntreprise'=>$nomEntreprise, ':adresse'=>$adresse, ':telephone'=>$telephone, ':mailContact'=>$mailContact,':id'=>$id));
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
    
    public function selectByID($id){ 
        $this->selectByID->execute(array(':id'=>$id)); 
        if ($this->selectByID->errorCode()!=0){
            print_r($this->selectByID->errorInfo()); 
            
        }
        return $this->selectByID->fetch(); 
    }
    
    public function selectContrat(){
        $this->selectContrat->execute();
        if ($this->selectContrat->errorCode()!=0){
             print_r($this->selectContrat->errorInfo());  
        }
        return $this->selectContrat->fetchAll();
    }
}