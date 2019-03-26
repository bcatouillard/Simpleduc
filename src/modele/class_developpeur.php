<?php

class Developpeur{
    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectByEmail;
    private $update;
    private $updateMdp;
    private $delete;
    private $selectByEmailEquip;
    private $deleteEquip;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("insert into developpeur(nom, prenom, adr, tel, email, mdp, photo, idRole) values (:nom, :prenom, :adr, :tel, :email, :mdp, :photo, :idRole)");    
        $this->connect = $db->prepare("select email, idRole, mdp from developpeur where email=:email");
        $this->select = $db->prepare("select email, idRole, nom, prenom,photo, tel, adr, r.libelle as libellerole from developpeur u, role r where u.idRole = r.id order by nom");
        $this->selectByEmail = $db->prepare("select email, nom, prenom, idRole from developpeur where email=:email");
        $this->update = $db->prepare("update developpeur set nom=:nom, prenom=:prenom, idRole=:role where email=:email");
        $this->updateMdp = $db->prepare("update developpeur set mdp=:mdp where email=:email");
        $this->delete = $db->prepare("delete from developpeur where email=:email");
        $this->selectByEmailEquip = $db->prepare("select * from developpeur inner join equipe on developpeur.idEquipe=equipe.id  where developpeur.email=:email");
        $this->deleteEquip = $db->prepare("update developpeur set idEquipe=null where idEquipe=:id;");
        }
    public function insert($email, $mdp, $role, $nom, $prenom, $tel, $adr, $photo){
        $r = true;
        $this->insert->execute(array(':email'=>$email, ':mdp'=>$mdp, ':idRole'=>$role, ':nom'=>$nom, ':prenom'=>$prenom,':tel'=>$tel,':adr'=>$adr,':photo'=>$photo));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function connect($email){  
        $unUtilisateur = $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
             print_r($this->connect->errorInfo());  
        }
        return $this->connect->fetch();
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectByEmail($email){ 
        $this->selectByEmail->execute(array(':email'=>$email)); 
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo()); 
            
        }
        return $this->selectByEmail->fetch(); 
    }
    
    public function update($email, $role, $nom, $prenom){
        $r = true;
        $this->update->execute(array(':email'=>$email, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function updateMdp($email, $mdp){
        $r = true;
        $this->updateMdp->execute(array(':email'=>$email, ':mdp'=>$mdp));
        if ($this->update->errorCode()!=0){
             print_r($this->updateMdp->errorInfo());  
             $r=false;
        }
        return $r;
    }
    public function delete($email){
        $r = true;
        $this->delete->execute(array(':email'=>$email));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function selectByEmailEquip($email){ 
        $this->selectByEmailEquip->execute(array(':email'=>$email)); 
        if ($this->selectByEmailEquip->errorCode()!=0){
            print_r($this->selectByEmailEquip->errorInfo()); 
            
        }
        return $this->selectByEmailEquip->fetch(); 
    }
    
    public function deleteEquip($id){
        $r = true;
        $this->deleteEquip->execute(array(':id'=>$id));
        if ($this->deleteEquip->errorCode()!=0){
             print_r($this->deleteEquip->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}

?>
