<?php
require_once("inc/init.inc.php");

// On sélectionne les différentes catégories de la table salle
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM salle");
// On sélectionne les différentes villes de la table salle
$liste_ville = $pdo->query("SELECT DISTINCT ville FROM salle");
$liste_capacite = $pdo->query("SELECT DISTINCT capacite FROM salle ORDER BY capacite DESC");
$liste_prix = $pdo->query("SELECT DISTINCT prix FROM produit ORDER BY prix ASC");
$liste_date_arrivee = $pdo->query("SELECT date_arrivee FROM produit ORDER BY date_arrivee DESC");
$liste_date_depart = $pdo->query("SELECT date_depart FROM produit ORDER BY date_depart DESC");

// On sélectionne les informations des tables produits et salles pour l'affichage
$contenu = $pdo->query("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle");

// FILTRES 
if(!empty($_GET['categorie']))
{
    $filtre_categorie = $_GET['categorie'];
    $contenu = $pdo->prepare("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle AND categorie = :categorie");
    $contenu->bindParam(":categorie", $filtre_categorie, PDO::PARAM_STR);
    $contenu->execute();
}
elseif(!empty($_GET['ville']))
{
    $filtre_ville = $_GET['ville'];
    $contenu = $pdo->prepare("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle AND ville = :ville");
    $contenu->bindParam(":ville", $filtre_ville, PDO::PARAM_STR);
    $contenu->execute();
}
elseif(!empty($_GET['prix']))
{
    $filtre_prix = $_GET['prix'];
    $contenu = $pdo->prepare("SELECT * FROM salle s, produit p WHERE p.id_salle = s.id_salle AND prix < :prix");
    $contenu->bindParam(":prix", $filtre_prix, PDO::PARAM_STR);
    $contenu->execute();
}
// echo '<pre>'; print_r($_POST); echo '</pre>';
require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1  id="ligne_une">Bienvenue sur Lokisalle</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>
    <div class="row">
        <div class="col-sm-2">
            <label>Catégorie</label>
            <ul class="list-group">
            <?php
            while($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC))
            {
                echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a></li>';
            }
            ?>
            </ul>
            <label>Ville</label>
            <ul class="list-group">
            <?php
            while($ville = $liste_ville->fetch(PDO::FETCH_ASSOC))
            {
                echo '<li class="list-group-item"><a href="?ville=' . $ville['ville'] . '">' . $ville['ville'] . '</a></li>';
            }
            ?>
            </ul>
            
            <form method="post" action="ajax.php">
                <label>Capacité</label>
                <div class="form-group">
                    <select name="capacite" id="capacite" class="form-control">
                    <?php                               
                    while($capacite = $liste_capacite->fetch(PDO::FETCH_ASSOC))
                    {
                        echo '<option>' . $capacite['capacite'] . '</option>';
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prix max.</label>
                    <input type="range" name="prix" id="prix" min="50" max="1000" value="50" step="10" onchange="document.getElementById('AfficheRange').textContent=value; ajax()" />
                    <span id="AfficheRange">50</span>
                </div>
                <label>Période</label>
                <p class="info_periode">Date d'arrivée</p>
                <div class="input-group">            
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" id="date_arrivee" name="date_arrivee" aria-describedby="basic-addon1">
                </div>
                <br>
                <p class="info_periode">Date de départ</p>
                <div class="input-group">            
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" id="date_depart" name="date_depart" aria-describedby="basic-addon1">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-membre">Filtrer</button>
                </div>
            </form>
        </div> <!-- /.col-sm-3 -->
        <div class="col-sm-9 col-sm-offset-1" id="resultat">
            <div class="row">
                <?php
                // On crée un compteur initié à 0
                $compteur = 0;
                while($ligne = $contenu->fetch(PDO::FETCH_ASSOC))
                {
                    // On crée l'objet date instanciée par la classe DateTime 
                    $date_arrivee = new DateTime($ligne['date_arrivee']);

                    $date_depart = new DateTime($ligne['date_depart']);                   
                    
                    
                    
                    if($compteur % 3 == 0)
                    {
                        echo '</div><div class="row">';
                    }
                    echo '<div class="col-sm-4">
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
                        </div>';
                        // On incrémente le compteur dans la boucle while
                        $compteur++;
                }
                ?>
                
            </div><!-- /.row -->
        </div><!-- /.col-sm-9 -->
    </div><!-- /.row -->
</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>