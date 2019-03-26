<?php
function actionOutil($twig, $db){
    $form = array();
    $outil = new Outil($db);
    $liste = $outil->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        foreach ( $cocher as $id){
            $exec=$outil->delete($id);
            if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème de suppression dans la table outil';   
            }
            $form['message'] = 'Suppression de l\'outil réussi ! ';   
            $form['valide'] = true;
            header("Location:index.php?page=outil");
            exit;
        }
    }
    echo $twig->render('outil/outil.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionOutilAdd($twig, $db){
    $form = array();
    $outil = new Outil($db);
    if(isset($_POST['btAjouter'])){
        $libelle = $_POST['libelle'];
        $version = $_POST['version'];
        $descriptif = $_POST['descriptif'];
        $exec = $outil->insert($libelle, $version, $descriptif);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Erreur d'insertion dans la table outils";
        }
        else{
            $form['valide'] = true;
            $form['message'] = "Ajout réussi ! ";
        }
    }
    echo $twig->render('outil/outil-ajout.html.twig', array('form'=>$form));
}

function actionOutilMod($twig, $db){
    $form = array();
    $outil = new Outil($db);
    if(isset($_GET['code'])){
        $unOutil = $outil->selectByCode($_GET['code']);
        if($unOutil != null){
            $form['outil'] = $unOutil;
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Outil introuvable";
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $code = $_POST['code'];
            $libelle = $_POST['libelle'];
            $version = $_POST['version'];
            $descriptif = $_POST['descriptif'];
            $exec = $outil->update($code, $libelle, $version, $descriptif);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = "Erreur de modification";
            }
            else{
                $form['valide'] = true;
                $form['message'] = "Modification réussie !";
            }
        }
    }
    echo $twig->render('outil/outil-modif.html.twig', array('form'=>$form));
}
