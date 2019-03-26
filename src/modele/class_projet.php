<?php
Class Projet{
    private $db;
    private $select;
    private $insert;
    private $update;
    private $delete;
    private $selectByCode;
    private $recherche;
    private $selectInfos;
    private $selectidContrat;
    
    public function __construct($db){
        $this->db = $db;
        $this->select=$db->prepare("select idContrat,nomEntreprise,code,libelle,DATE_FORMAT(dateSignature,'%d/%m/%Y') as dateSignatureF from projet inner join contrat on projet.idContrat=contrat.id inner join entreprise on contrat.idEnt=entreprise.id");
        $this->insert=$db->prepare("insert into projet(code, libelle, idContrat) values(:code, :libelle, :idContrat);");
        $this->update=$db->prepare("update projet set libelle=:libelle where code=:code;");
        $this->delete=$db->prepare("delete from projet where code=:code;");
        $this->recherche=$db->prepare("select projet.code,projet.libelle as libelleProj, nomEntreprise, dateSignature, sum(facture.prix) as total from projet inner join contrat on projet.idContrat=contrat.id inner join entreprise on contrat.idEnt=entreprise.id left outer join module on projet.code=module.idProjet left outer join tache on module.code=tache.idModule left outer join facture on facture.idTache=tache.code where projet.libelle like :code");
        $this->selectInfos=$db->prepare("select nomEntreprise, idContrat, DATE_FORMAT(dateSignature,'%d/%m/%Y') as dateSignatureF, projet.libelle from projet inner join contrat on projet.idContrat=contrat.id inner join entreprise on contrat.idEnt=entreprise.id group by nomEntreprise");
        $this->selectByCode=$db->prepare("select * from projet where code=:code;");
        $this->selectidContrat=$db->prepare("select code from projet where idContrat=:idContrat");
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function insert($code, $libelle, $idContrat){
        $r = true;
        $this->insert->execute(array(':code'=>$code, ':libelle'=>$libelle,':idContrat'=>$idContrat));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function update($libelle, $code){
        $r = true;
        $this->update->execute(array(':libelle'=>$libelle,':code'=>$code));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
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
    
    public function selectByCode($code){ 
        $this->selectByCode->execute(array(':code'=>$code)); 
        if ($this->selectByCode->errorCode()!=0){
            print_r($this->selectByCode->errorInfo()); 
            
        }
        return $this->selectByCode->fetch(); 
    }
    
    public function recherche($code){
        $this->recherche->execute(array(':code'=>$code));
        if ($this->recherche->errorCode()!=0){
             print_r($this->recherche->errorInfo());  
        }
        return $this->recherche->fetch();
    }
    
    public function selectInfos(){
        $this->selectInfos->execute(array());
        if ($this->selectInfos->errorCode()!=0){
             print_r($this->selectInfos->errorInfo());  
        }
        return $this->selectInfos->fetchAll();
    }
    
    public function selectidContrat($idContrat){ 
        $this->selectidContrat->execute(array(':idContrat'=>$idContrat)); 
        if ($this->selectidContrat->errorCode()!=0){
            print_r($this->selectidContrat->errorInfo()); 
            
        }
        return $this->selectidContrat->fetchAll(); 
    }
}
?>
