<?php
function actionClient($twig, $db){
    $form = array();
    $client = new Client($db);
    $liste = $client->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
          $exec=$client->delete($id); 
          if (!$exec){
             $form['valide'] = false;  
             $form['message'] = 'Problème de suppression dans la table entreprise';   
          }
          else{
              header("Location:index.php?page=client");
              exit;
          }
        }
    }
    echo $twig->render('client/client.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionClientAdd($twig, $db){
    $form = array();
    $client = new Client($db);
    if(isset($_POST['btAjouter'])){
        $inputNom = $_POST['inputNom'];
        $inputAdresse = $_POST['inputAdresse']; 
        $inputTel = $_POST['inputTel'];
        $inputMail = $_POST['inputMail'];
        $form['valide'] = true;
        $exec = $client->insert($inputNom, $inputAdresse, $inputTel, $inputMail);
        if (!$exec){
          $form['valide'] = false;  
          $form['message'] = 'Problème d\'insertion dans la table entreprise ';  
        }
    }
    echo $twig->render('client/client-ajout.html.twig', array('form'=>$form));
}

function actionClientMod($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
    $client = new Client($db);
    $unClient = $client->selectByID($_GET['id']);
        if ($unClient!=null){
            $form['client'] = $unClient;
        }
        else{
            $form['message'] = 'Entreprise incorrecte';  
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $client = new Client($db);
            $inputNom = $_POST['inputNom'];
            $inputAdresse = $_POST['inputAdresse']; 
            $inputTel = $_POST['inputTel'];
            $inputMail = $_POST['inputMail'];
            $id = $_POST['inputID'];
            $exec = $client->update($inputNom, $inputAdresse, $inputTel, $inputMail,$id);
            if(!$exec){
                $form['valide'] = false;  
                $form['message'] = 'Echec de la modification des données. '; 
            }
            else{
                $form['valide'] = true;  
                $form['message'] = 'Modification des données réussie. ';  
             }
        }
        else{
            $form['message'] = 'Entreprise non précisée';
        }
    }
    echo $twig->render('client/client-modif.html.twig', array('form'=>$form));
}
?>