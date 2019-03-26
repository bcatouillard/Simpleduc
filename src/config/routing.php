<?php
function getPage($db){

// Panel accueil
$lesPages['accueil'] = "actionAccueil;0";
$lesPages['inscrire'] = "actionInscrire;0";
$lesPages['mentions'] = "actionMentions;0";
$lesPages['connexion'] = "actionConnexion;0";
$lesPages['deconnexion'] = "actionDeconnexion;0";
$lesPages['compte'] = "actionCompte;0";
$lesPages['vequipe'] = "actionConsEquipe;0";
$lesPages['apropos'] = "actionApropos;0";
$lesPages['maintenance'] = "actionMaintenance;0";

// Panel développeur
$lesPages['developpeur'] = "actionDeveloppeur;1";
$lesPages['developpeurAdd'] = "actionDeveloppeurAdd;1";
$lesPages['developpeurajout'] = "actionDeveloppeurAjout;1";
$lesPages['developpeurmodif'] = "actionDeveloppeurModif;1";
$lesPages['developpeurws'] = "actionDeveloppeurWS;0";

// Panel équipe
$lesPages['equipe'] = "actionEquipe;1";
$lesPages['equipeajout'] = "actionEquipeAjout;1";
$lesPages['equipemodif'] = "actionEquipeModif;1";
$lesPages['equipews'] = "actionEquipeWS;0";

//Panel client
$lesPages['client'] = "actionClient;1";
$lesPages['clientMod'] = "actionClientMod;1";
$lesPages['clientAdd'] = "actionClientAdd;1";

//Panel projet
$lesPages['projet'] = "actionProjet;1";
$lesPages['projetAdd'] = "actionProjetAdd;1";
$lesPages['projetMod'] = "actionProjetMod;1";
$lesPages['listeclientprojet'] = "actionListeProjetClient;1";
$lesPages['listeclientprojetpdf'] = "actionListeProjetClientPDF;1";
$lesPages['rechercheprojet'] = "actionRechercheProjet;1";
$lesPages['recherchepdf'] = "actionRecherchePDF;1";

//Panel contrat
$lesPages['contrat'] = "actionContrat;1";
$lesPages['contratAdd'] = "actionContratAdd;1";
$lesPages['contratMod'] = "actionContratMod;1";

//Panel Outil
$lesPages['outil'] = "actionOutil;1";
$lesPages['outilAdd'] = "actionOutilAdd;1";
$lesPages['outilMod'] = "actionOutilMod;1";



if ($db!=null){
  if(isset($_GET['page'])){
    $page = $_GET['page']; }
  else{
    $page = 'accueil';
  }

  if (!isset($lesPages[$page])){
    $page = 'accueil'; 
  }
  
  $explose = explode(";",$lesPages[$page]);
  $role = $explose[1];
  
  if ($role != 0){
      if(isset($_SESSION['login'])){
        if(isset($_SESSION['role'])){
            if($role!=$_SESSION['role']){
               $contenu = 'actionAccueil';  
            }
            else{
               $contenu = $explose[0]; 
            }
        }
        else{
           $contenu = 'actionAccueil'; 
        }
      }
      else{ 
        $contenu = 'actionAccueil';  
      }
    }else{
      $contenu = $explose[0];   
    }
}
else{
   $contenu = 'actionMaintenance';
}
return $contenu; 

}
?>