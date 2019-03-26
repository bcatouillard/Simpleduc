<?php
function actionContrat($twig,$db){
    $form = array();
    $contrat = new Contrat($db);
    $projet = new Projet($db);
    $liste = $contrat->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $verifContrat = $projet->selectidContrat($id);
            if(!empty($verifContrat)){
                $form['valide'] = false;
                $form['message'] = "Impossible de supprimer ce contrat, il est associé à des projets";
            }
            else{
                $exec=$contrat->delete($id);
                if (!$exec){
                   $form['valide'] = false;  
                   $form['message'] = 'Problème de suppression dans la table contrat';   
                }
                else{
                    $form['message'] = 'Suppression du contrat réussie ! ';  
                }
            }
        }
    }
    echo $twig->render('contrat/contrat.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionContratAdd($twig, $db){
    $form = array();
    $liste = array();
    $contrat = new Contrat($db);
    $entreprise = new Client($db);
    $echeancier = new Echeancier($db);
    $liste['entreprise'] = $entreprise->select();
    $liste['echeancier'] = $echeancier->select();
    if(isset($_POST['btAjouter'])){
        $date = $_POST['inputDate'];
        $delai = $_POST['inputDelai'];
        $cout = $_POST['inputCout'];
        $entreprise = $_POST['entreprise'];
        $echeancier = $_POST['echeancier'];
        $exec = $contrat->insert($delai, $date, $cout, $echeancier, $entreprise);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table contrat';
        }
        else{
            $form['valide'] = true;
            $form['message'] = 'Ajout réussi avec succès ! ';
        }
    }
    echo $twig->render('contrat/contrat-ajout.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionContratMod($twig, $db){
    $form = array();
    $liste = array();
    $contrat = new Contrat($db);
    $entreprise = new Client($db);
    $echeancier = new Echeancier($db);
    $liste['entreprise'] = $entreprise->select();
    $liste['echeancier'] = $echeancier->select();
    if(isset($_GET['id'])){
        $unContrat = $contrat->selectByID($_GET['id']);
        if($unContrat!=null){
            $form['contrat'] = $unContrat;
        }
        else{
            $form['valide'] = false;
            $form['message'] = 'Contrat invalide';
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $date = $_POST['inputDate'];
            $delai = $_POST['inputDelai'];
            $cout = $_POST['inputCout'];
            $entreprise = $_POST['entreprise'];
            $echeancier = $_POST['echeancier'];
            $id = $_POST['id'];
            $exec = $contrat->update($delai, $date, $cout, $echeancier, $entreprise, $id);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification du contrat';
            }
            else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie ! ';
            }
        }
    }
    
    echo $twig->render('contrat/contrat-modif.html.twig', array('form'=>$form,'liste'=>$liste));
}