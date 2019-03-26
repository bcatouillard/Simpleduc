<?php

function actionEquipe($twig, $db){
    $form = array(); 
    $equipe = new Equipe($db);
    $developpeur = new Developpeur($db);
    if(isset($_GET['id'])){
        $ex=$developpeur->deleteEquip($_GET['id']);
        $exec=$equipe->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table équipe';
        }
        else{
            $form['valide'] = true;
        }
        $form['message'] = 'Equipe supprimée avec succès';
     }
    
    $liste = $equipe->select();
    echo $twig->render('equipe/equipe.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionEquipeAjout($twig, $db){
    $form = array(); 
    $developpeur = new Developpeur($db);
    if (isset($_POST['btAjouter'])){
        $inputLibelle = $_POST['inputLibelle'];
        $inputIdResponsable = $_POST['inputIdResponsable']; 
        $form['valide'] = true;
        $equipe = new Equipe($db); 
        $r = $equipe->selectCount();
        $id = $r['nb'];
        $id += 1;
        $user = $_POST['user'];
        $exec = $equipe->insert($id, $inputLibelle, $inputIdResponsable);
        foreach ($user as $email){
            $exec = $developpeur->setEquipe($id, $email);
        }
        if (!$exec){
          $form['valide'] = false;  
          $form['message'] = 'Problème d\'insertion dans la table équipe ';  
        }
    }
    else{
        $developpeur = new Developpeur($db);
        $liste = $developpeur->select();
        $form['liste'] = $liste;
    }
 
    echo $twig->render('equipe/equipe-ajout.html.twig', array('form'=>$form)); 
}

function actionEquipeModif($twig, $db){
    $form = array();   
    if(isset($_GET['id'])){
        $equipe = new Equipe($db);
        $uneEquipe = $equipe->selectById($_GET['id']);  
        
        if ($uneEquipe!=null){
            $form['equipe'] = $uneEquipe;
           
            $developpeur = new Developpeur($db);
            $liste = $developpeur->select();
            $form['liste'] = $liste;
            
        }
        else{
            $form['message'] = 'Equipe incorrecte';  
        }
    }
    else{
        if(isset($_POST['btModifier'])){
          $id = $_POST['id'];  
          $libelle = $_POST['inputLibelle'];  
          $idResponsable = $_POST['inputIdResponsable'];
          $equipe = new Equipe($db);
          $exec = $equipe->update($id, $libelle, $idResponsable);
          if(!$exec){
                $form['valide'] = false;  
                $form['message'] .= 'Echec de la modification de l\'équipe'; 
          }
          else{
            $form['valide'] = true;  
            $form['message'] = 'Modification réussie';  
          } 
          
        }
        else{
            $form['message'] = 'Développeur non précisé';
        }    
     
    }
    
    echo $twig->render('equipe/equipe-modif.html.twig', array('form'=>$form));
}

// WebService
function actionEquipeWS($twig, $db){
   $equipe = new Equipe($db);
   $json = json_encode($liste = $equipe->select()); 
   echo $json; 
}

function actionConsEquipe($twig, $db){
    $developpeur = new Developpeur($db);
    $liste['equipe'] = $developpeur->selectByEmailEquip($_SESSION['login']);
    $liste['dev'] = $developpeur->select();
    echo $twig->render('equipe/consultation-equipe.html.twig', array('liste'=>$liste));
}

