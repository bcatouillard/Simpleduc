<?php
function actionProjet($twig, $db){
    $form = array();
    $projet = new Projet($db);
    $liste = $projet->select();
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec=$projet->delete($id);
            if (!$exec){
               $form['valide'] = false;  
               $form['message'] = 'Problème de suppression dans la table projet';   
            }
            $form['message'] = 'Suppression du projet réussi ! ';   
            $form['valide'] = true;
        }
    }
    if(isset($_POST['codeprojet'])){
        header("Location:index.php?page=rechercheprojet&codeprojet=".$_POST['codeprojet']);
        exit;
    }
    echo $twig->render('projet/projet.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionProjetAdd($twig, $db){
    $form = array();
    $contrat = new Contrat($db);
    $liste = $contrat->select();
    $projet = new Projet($db);
    if(isset($_POST['btAjouter'])){
        $code = $_POST['inputCode'];
        $libelle = $_POST['inputLibelle'];
        $contrat = $_POST['contrat'];
        $exec = $projet->insert($code, $libelle, $contrat);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout dans la table projet";
        }
        else{
            $form['valide'] = true;
            $form['message'] = "Ajout réussi !";
        }
    }
    echo $twig->render('projet/projet-ajout.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionProjetMod($twig,$db){
    $form = array();
    $projet = new Projet($db);
    $contrat = new Contrat($db);
    $liste = $contrat->select();
    if(isset($_GET['code'])){
        $unProjet = $projet->selectByCode($_GET['code']);
        if($unProjet!=null){
            $form['projet'] = $unProjet;
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Projet invalide";
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            
        }
    }
    echo $twig->render('projet/projet-modif.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionListeProjetClient($twig, $db){
    $form = array();
    $projet = new Projet($db);
    $liste = $projet->selectInfos();
    $form['projet'] = $projet->select();
    echo $twig->render('projet/projet-client-liste.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionListeProjetClientPDF($twig,$db){
    $projet = new Projet($db);
    $liste = $projet->selectInfos();
    $form['projet'] = $projet->select();
    $html= $twig->render('projet/projet-client-listepdf.html.twig', array('form'=>$form,'liste'=>$liste));
    try { 
        ob_end_clean(); 
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr'); 
        $html2pdf->writeHTML($html); 
        $html2pdf->output('liste_projets_par-clients.pdf'); 
    } 
    catch (Html2PdfException $e) {
        echo 'erreur '.$e;  
    }
}

function actionRechercheProjet($twig,$db){
    $form = array();
    if(isset($_GET['codeprojet'])){
        $projet = new Projet($db);
        $leProjet = $projet->recherche($_GET['codeprojet']);
        if($leProjet['code'] != null){
            $form['valide'] = true;
            $form['projet'] = $leProjet;
        }
        else{
            $form['valide'] = false;
            $form['message'] = "Ce projet n'existe pas";
        }
    }
    else{
        $form['valide'] = false;
        $form['message'] = "Projet invalide";
    }
    echo $twig->render('projet/projet-recherche.html.twig', array('form'=>$form));
}

function actionRecherchePDF($twig,$db){
    $form = array();
    $projet = new Projet($db);
    if(isset($_GET['code'])){
        $form['projet'] = $projet->recherche($_GET['code']);
        $html = $twig->render('projet/projet-recherche-pdf.html.twig', array('form'=>$form));
        try { 
            ob_end_clean(); 
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr'); 
            $html2pdf->writeHTML($html); 
            $html2pdf->output('recherche_projet.pdf'); 
        } 
        catch (Html2PdfException $e) {
            echo 'erreur '.$e;  
        }
    }
}
?>