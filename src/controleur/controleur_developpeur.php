<?php

function actionDeveloppeur($twig, $db){
    $form = array(); 
    $developpeur = new Developpeur($db);
    
     if(isset($_GET['id'])){
        /* Nous vérifions que l'utilisateur n'est pas responsable d'une équipe
           Car nous n'avons pas souhaité faire un DELETE ON CASCADE  
         */ 
        $equipe = new Equipe($db);
        $liste = $equipe->selectByIdResponsable($_GET['id']);
        if($liste==null){
            $exec=$developpeur->delete($_GET['id']);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table developpeur';
            }
            else{
                $form['valide'] = true;
                $form['message'] = 'Développeur supprimé avec succès';
            }
        }
        else{
           $form['valide'] = false;
           $form['message'] = 'Impossible de supprimer le développeur, il est affecté à des équipes'; 
        }
       
     }
     $liste = $developpeur->select();
     echo $twig->render('developpeur/developpeur.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionDeveloppeurModif($twig, $db){
 $form = array();   
 $outil = new Outil($db);
 $form['outil'] = $outil->select();
 if(isset($_GET['id'])){
    $developpeur = new Developpeur($db);
    $unDeveloppeur = $developpeur->selectByEmail($_GET['id']);  
    if ($unDeveloppeur!=null){
      $form['utilisateur'] = $unDeveloppeur;
      $form['utilisateur']['photo'] = '../src/private/'.$form['utilisateur']['photo'];
      $role = new Role($db);
      $liste = $role->select();
      $form['roles']=$liste;
    }
    else{
      $form['message'] = 'Développeur incorrect';  
    }
 }
 else{
     if(isset($_POST['btModifier'])){
       $developpeur = new Developpeur($db);
       $nom = $_POST['nom'];
       $prenom = $_POST['prenom'];
       $role = $_POST['role'];
       $email = $_POST['email'];
       $exec=$developpeur->update($email, $role, $nom, $prenom);
       if(!$exec){
         $form['valide'] = false;  
         $form['message'] = 'Echec de la modification des données. '; 
       }
       else{
         $form['valide'] = true;  
         $form['message'] = 'Modification des données réussie. ';  
       }
       if(!empty($_POST['inputPassword'])){
          $p1 = $_POST['inputPassword'];
          $p2 = $_POST['inputPassword2'];
          if ($p1==$p2){
             $p1 = password_hash($p1, PASSWORD_DEFAULT);
             $exec=$developpeur->updateMdp($email, $p1);
             if(!$exec){
                $form['valide'] = false;  
                $form['message'] .= 'Echec de la modification du mot de passe'; 
             }
             else{
                $form['valide'] = true;  
                $form['message'] .= 'Modification réussie du mot de passe';  
             } 
          }
          else{
            $form['valide'] = false;  
            $form['message'] .= 'Echec de la modification du mot de passe';   
          }
          
       }
     }
     else{
       $form['message'] = 'Developpeur non précisé';
     }  
 }
 echo $twig->render('developpeur/developpeur-modif.html.twig', array('form'=>$form));
}

function actionDeveloppeurWS($twig, $db){
    $developpeur = new Developpeur($db);
    $liste = $developpeur->select();
    for($i=0; $i<count($liste);$i++){
        $img_src = $liste[$i]['photo'];
        $imgbinary = fread(fopen('../src/private/'.$img_src,"r"), filesize('../src/private/'.$img_src));
        $liste[$i]['photo'] = base64_encode($imgbinary);
    }
    $json = json_encode($liste);
    echo $json;
}

function actionDeveloppeurAdd($twig, $db){
    $form = array();
    $developpeur = new Developpeur($db);
    $outil = new Outil($db);
    $form['outil'] = $outil->select();
    if(isset($_POST['btAjouter'])){
        $email = $_POST['inputEmail'];
        $mdp = $_POST['inputPassword'];
        $mdp2 = $_POST['inputPassword2'];
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $tel = $_POST['telephone'];
        $adresse = $_POST['adresse'];
        $role = 2;
        if(!empty($mdp) && !empty($mdp2)){
            if ($mdp!=$mdp2){
                $form['valide'] = false;  
                $form['message'] = 'Les mots de passe sont différents';
            }
        }
        else{
            $form['valide'] = false;  
            $form['message'] = 'Les mots de passe sont vides';
        }
        $photo = null;
        if(isset($_FILES['photo'])){
            if(!empty($_FILES['photo']['name'])){  
                $extensions_ok = array('png', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '../src/private/';
                if( !in_array( substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok ) ){
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                }
                else{
                    if( file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo'] ['tmp_name'])) >  $taille_max){
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    }
                    else{
                        $photo = basename($_FILES['photo']['name']);                           
                        $photo=strtr($photo,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                        move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier.$photo);        
                    }
                }
            }
            else{
                $dest_dossier = '../src/private/';
                $photo = 'default.jpeg';
            }
        }
        else{
            $dest_dossier = '../src/private/';
            $photo = 'default.jpeg';
        }
        $exec=$developpeur->insert($email, password_hash($mdp, PASSWORD_DEFAULT), $role, $nom, $prenom, $tel, $adresse, $photo);
        $outils = $_POST['outil'];
        if (!$exec){
            $form['valide'] = false;  
            $form['message'] = 'Problème d\'insertion dans la table développeur '; 
        }
        foreach($outils as $code){
            $exec = $developpeur->setComp($code, $email);
        }
    }
    echo $twig->render('developpeur/developpeur-ajout.html.twig', array('form'=>$form));
}

function actionTacheparDev($twig, $db){
    $form = array();
    $developpeur = new Developpeur($db);
    $form['developpeur'] = $developpeur->selectTache();
    echo $twig->render('developpeur/tache-chaquedev.html.twig', array('form'=>$form));
}

function actionTacheparDevPDF($twig, $db){
    $form = array();
    $developpeur = new Developpeur($db);
    $form['developpeur'] = $developpeur->selectTache();
    $html = $twig->render('developpeur/tache-chaquedevpdf.html.twig', array('form'=>$form));
        try { 
            ob_end_clean(); 
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr'); 
            $html2pdf->writeHTML($html); 
            $html2pdf->output('tache_dev.pdf'); 
        } 
        catch (Html2PdfException $e) {
            echo 'erreur '.$e;  
        }
}
?>

