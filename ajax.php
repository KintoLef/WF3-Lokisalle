<?php
require_once("inc/init.inc.php");

// On crée le tableau array
$tab = array();
$tab['resultat'] = ""; 

$capacite = (isset($_POST['capacite'])) ? $_POST['capacite'] : "";
$prix = (isset($_POST['prix'])) ? $_POST['prix'] : "";

if(!empty($_POST['capacite']))
{
    $filtre_capacite = $_POST['capacite'];
    $contenu = $pdo->prepare("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle AND capacite = :capacite");
    $contenu->bindParam(":capacite", $filtre_capacite, PDO::PARAM_STR);
    $contenu->execute();

    while($ligne = $contenu->fetch(PDO::FETCH_ASSOC))
    {
    
        // On crée l'objet date instanciée par la classe DateTime 
        $date_arrivee = new DateTime($ligne['date_arrivee']);

        $date_depart = new DateTime($ligne['date_depart']);

        $tab['resultat'] .= '<div class="row">
                                <div class="col-sm-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><img src="' . URL . 'photo/' . $ligne['photo'] . '" class="img-responsive" /></div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p>' . $ligne['titre'] . '</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-right"><strong>' . $ligne['prix'] . ' €</strong></p>
                                                </div> 
                                            </div>
                                            <p>' . substr_replace($ligne['description'], ' ...', 30) . '</p>
                                            <p><span class="glyphicon glyphicon-calendar"></span> ' . date_format($date_arrivee, 'd/m/Y') . ' au ' . date_format($date_depart, 'd/m/Y') . '</p>
                                            <div class="row">
                                                <div class="col-sm-6">

                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="fiche_produit.php?id_produit=' . $ligne['id_produit'] . '" class="pull-right"><span class="glyphicon glyphicon-search" style="font-size: 1em;"></span> Voir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }
}
elseif(!empty($_POST['prix']))
{
    $filtre_prix = $_POST['prix'];
    $contenu = $pdo->prepare("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle AND prix <= :prix");
    $contenu->bindParam(":capacite", $filtre_prix, PDO::PARAM_STR);
    $contenu->execute();

    while($ligne = $contenu->fetch(PDO::FETCH_ASSOC))
    {
    
        // On crée l'objet date instanciée par la classe DateTime 
        $date_arrivee = new DateTime($ligne['date_arrivee']);

        $date_depart = new DateTime($ligne['date_depart']);

        $tab['resultat'] .= '<div class="row">
                                <div class="col-sm-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><img src="' . URL . 'photo/' . $ligne['photo'] . '" class="img-responsive" /></div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p>' . $ligne['titre'] . '</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-right"><strong>' . $ligne['prix'] . ' €</strong></p>
                                                </div> 
                                            </div>
                                            <p>' . substr_replace($ligne['description'], ' ...', 30) . '</p>
                                            <p><span class="glyphicon glyphicon-calendar"></span> ' . date_format($date_arrivee, 'd/m/Y') . ' au ' . date_format($date_depart, 'd/m/Y') . '</p>
                                            <div class="row">
                                                <div class="col-sm-6">

                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="fiche_produit.php?id_produit=' . $ligne['id_produit'] . '" class="pull-right"><span class="glyphicon glyphicon-search" style="font-size: 1em;"></span> Voir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }
}


// NE JAMAIS FAIRE D'echo avant l'encodage JSON sinon celui-ci ne s'effectue pas et la requête ajax ne reçoit pas de réponse
echo json_encode($tab);