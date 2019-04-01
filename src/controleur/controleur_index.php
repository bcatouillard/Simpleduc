<?php

function actionAccueil($twig, $db){
    $form = array(); 
    $form['valide'] = true;
    if (isset($_POST['btConnecter'])){
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];  
        $developpeur = new Developpeur($db);
        $unDeveloppeur = $developpeur->connect($inputEmail);
        if ($unDeveloppeur!=null){
          if(!password_verify($inputPassword,$unDeveloppeur['mdp'])){
              $form['valide'] = false;
              $form['message'] = 'Login ou mot de passe incorrect';
          }  
          else{
           $_SESSION['login'] = $inputEmail;     
           $_SESSION['role'] = $unDeveloppeur['idRole'];
           header("Location:index.php");
          } 
        }
        else{
           $form['valide'] = false;
           $form['message'] = 'Login ou mot de passe incorrect';
          
        }
    }
    echo $twig->render('accueil/index.html.twig', array('form'=>$form));
}

function actionConnexion($twig, $db){
    $form = array(); 
    $form['valide'] = true;
    if (isset($_POST['btConnecter'])){
        $inputEmail = $_POST['inputEmail'];
        $inputPassword = $_POST['inputPassword'];  
        $developpeur = new Developpeur($db);
        $unDeveloppeur = $developpeur->connect($inputEmail);
        if ($unDeveloppeur!=null){
          if(!password_verify($inputPassword,$unDeveloppeur['mdp'])){
              $form['valide'] = false;
              $form['message'] = 'Login ou mot de passe incorrect';
          }  
          else{
           $_SESSION['login'] = $inputEmail;     
           $_SESSION['role'] = $unDeveloppeur['idRole'];
           header("Location:index.php");
          } 
        }
        else{
           $form['valide'] = false;
           $form['message'] = 'Login ou mot de passe incorrect';
          
        }
    }
    echo $twig->render('accueil/connexion.html.twig', array('form'=>$form));
}

function actionDeconnexion($twig){
    session_unset();
    session_destroy();
    header("Location:index.php");
}

function actionInscrire($twig, $db){
    $form = array(); 
    if (isset($_POST['btInscrire'])){
      $email = $_POST['inputEmail'];
      $mdp = $_POST['inputPassword']; 
      $mdp2 =$_POST['inputPassword2']; 
      $nom = $_POST['nom']; 
      $prenom =$_POST['prenom']; 
      $role = 2; // Signifie que par défaut, une personne est un simple utilisateur
      $tel = $_POST['telephone'];
      $adr = $_POST['adresse'];
      $photo = null;
      if(isset($_FILES['photo'])){
            if(!empty($_FILES['photo']['name'])){  
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/var/www/html/Simpleduc/src/private/';
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
                $dest_dossier = '/var/www/html/Simpleduc/src/private/';
                $photo = 'default.jpeg';
                move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier.$photo);
            }
        }
        $form['valide'] = true;
        if ($mdp!=$mdp2){
            $form['valide'] = false;  
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
          $developpeur = new Developpeur($db); 
          $exec = $developpeur->insert($email, password_hash($mdp,PASSWORD_DEFAULT), $role, $nom, $prenom, $tel, $adr, $photo);
          if (!$exec){
            $form['valide'] = false;  
            $form['message'] = 'Problème d\'insertion dans la table développeur '; 

          }
        }
        $form['email'] = $email;
        $form['role'] = $role;
      
    }
    echo $twig->render('accueil/inscrire.html.twig', array('form'=>$form));
}

function actionMentions($twig){
    echo $twig->render('accueil/mentions.html.twig', array());
}

function actionApropos($twig){
    echo $twig->render('accueil/apropos.html.twig', array());
}

function actionMaintenance($twig){
    echo $twig->render('accueil/maintenance.html.twig', array());
}

function actionCompte($twig, $db){
    $form = array();
    $developpeur = new Developpeur($db);
    $form['utilisateur'] = $developpeur->selectByEmail($_SESSION['login']);
    $form['utilisateur']['photo'] = '../src/private/'.$form['utilisateur']['photo'];
    echo $twig->render('accueil/compte.html.twig', array('form'=>$form));
}
?>
