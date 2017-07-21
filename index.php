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

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
?>

<div class="container">

    <div class="starter-template">
        <h1>Bienvenue sur Lokisalle</h1>
    </div>
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>
    <div class="row">
        <div class="col-sm-3">
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
            
            <form method="post" action="">
                <label>Capacité</label>
                <div class="form-group">
                    <select name="capacite" id="capacite"  class="form-control">
                    <?php                               
                    while($capacite = $liste_capacite->fetch(PDO::FETCH_ASSOC))
                    {
                        echo '<option><a href="?capacite=' . $capacite['capacite'] . '">' . $capacite['capacite'] . '</a></option>';
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prix</label>
                    <input type="range" min="0" value="" step="50" max="2000">
                </div>
                <label>Période</label>
                <p class="info_periode">Date d'arrivée</p>
                <div class="input-group">            
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="datetime" class="form-control" placeholder="dd/mm/yyyy hh:mm" id="date_arrivee" name="date_arrivee" aria-describedby="basic-addon1">
                </div>
                <br>
                <p class="info_periode">Date de départ</p>
                <div class="input-group">            
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="datetime" class="form-control" placeholder="dd/mm/yyyy hh:mm" id="date_depart" name="date_depart" aria-describedby="basic-addon1">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-membre">Filtrer</button>
                </div>
            </form>
        </div> <!-- /.col-sm-3 -->
    </div><!-- /.row -->
    

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>