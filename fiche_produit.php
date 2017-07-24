<?php
require_once("inc/init.inc.php");

// On sélectionne les informations des tables produits et salles pour l'affichage
if(!empty($_GET['id_produit']) && is_numeric($_GET['id_produit']))
{
    $id_produit = $_GET['id_produit'];
    //  Syntaxe de la fonction CAST pour récupérer les dates DATETIME sous format date
    // SELECT CAST( expression AS type );
    $contenu = $pdo->query("SELECT *, DATE_FORMAT(CONVERT(date_arrivee, DATE), '%d-%m-%Y') AS date_arrivee_convert, DATE_FORMAT(CAST(date_depart AS DATE), '%d-%m-%Y') AS date_depart_cast FROM salle s, produit p WHERE p.id_salle = s.id_salle AND id_produit = $id_produit");
    
    while($ligne = $contenu->fetch(PDO::FETCH_ASSOC))
    {
        $message .= '<div class="row">
                        <div class="col-sm-6">
                            <h1 style="margin: 0">' . $ligne['titre'] . '</h1>
                        </div>
                        <div class="col-sm-6">
                            <a href="" class="btn btn-success pull-right col-sm-4">Réserver</a>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-sm-8">
                            <img src="' . URL . 'photo/' . $ligne['photo'] . '" class="img-responsive" />
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <p>' . $ligne['description'] . '</p>
                                    <br>
                                </div>
                                
                                <div class="col-sm-12">
                                    <label>Localisation</label>
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.149148583323!2d2.3070520154984266!3d48.87443320753131!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fc6fe531f73%3A0x20db4cc74f8dd7e!2s5+Rue+Paul+C%C3%A9zanne%2C+75008+Paris-8E-Arrondissement!5e0!3m2!1sfr!2sfr!4v1500837060173" width="370" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label class="info_comp">Informations complémentaires</label>
                    <div class="row" style="font-size:13px;">
                        
                        <div class="col-sm-4">                         
                            <p><span class="glyphicon glyphicon-calendar"></span> Arrivée: ' . $ligne['date_arrivee_convert'] . '</p>
                            <p><span class="glyphicon glyphicon-calendar"></span> Départ: ' . $ligne['date_depart_cast'] .'</p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="glyphicon glyphicon-user"></span> Capacité: ' . $ligne['capacite'] . ' </p>
                            <p><span class="glyphicon glyphicon-inbox"></span> Catégorie: ' . $ligne['categorie'] . ' </p>
                        </div>
                        <div class="col-sm-4">
                            <p><span class="glyphicon glyphicon-map-marker"></span> Adresse : ' . $ligne['adresse'] . ", " . $ligne['cp'] . ", " . $ligne['ville'] . '</p>
                            <p><span class="glyphicon glyphicon-euro"></span> Tarif: ' . $ligne['prix'] . ' €</p>
                        </div>
                    </div>
                    ';
    }
    // Requête pour obtenir les photos des autres produits
    $produits = $pdo->query("SELECT * FROM salle s, produit p WHERE s.id_salle = p.id_salle LIMIT 0, 4");
    $message .= '<div class="row">
                    <h2 style="font-size: 18px; font-weight: bold;">Autres produits</h2>';
    
    while($ligne = $produits->fetch(PDO::FETCH_ASSOC))
    {
        $message .= '<div class="col-sm-3">
                        <a href="fiche_produit.php?id_produit=' . $ligne['id_produit'] . '"><img src="' . URL . 'photo/' . $ligne['photo'] . '" class="img-responsive" /></a>
                    </div>
                ';
    }
    $message .= '</div><hr />';

    if(!empty($_GET['note']) && is_numeric($_GET['note']) && $_GET['note'] <= 5 && $_GET['note'] >= 0 && !empty($_POST['commentaire']))
    {
        $note = $_GET['note'];
        $commentaire = $_POST['commentaire'];
    }
        
}


require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
echo '<pre>'; print_r($_POST); echo '</pre>';
echo '<pre>'; print_r($_GET); echo '</pre>';
?>

<div class="container">
    
    <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>

        <div class="row">
            <div class="col-sm-6">
                <a href="" data-toggle="modal" data-target="#myModal"><p>Déposer un commentaire et une note</p></a>
            </div>
            <div class="col-sm-6">
                <a href="index.php"><p class="pull-right">Retour vers le catalogue</p></a>
            </div>
        </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-center" id="myModalLabel">Donnez-nous votre avis</h3>
                </div>
                <form method="post" action="">
                <div class="modal-body">             
                        <textarea rows="10" class="form-control" name="commentaire" id="commentaire" placeholder="Laissez un commentaire ici..."></textarea>
                        <div class="rating rating2"><!--
                            --><?php echo '<a href="#note=5" title="Give 5 stars">★</a>' ?><!--
                            --><?php echo '<a href="#note=4" title="Give 4 stars">★</a>' ?><!--
                            --><?php echo '<a href="#note=3" title="Give 3 stars">★</a>' ?><!--
                            --><?php echo '<a href="#note=2" title="Give 2 stars">★</a>' ?><!--
                            --><?php echo '<a href="#note=1" title="Give 1 stars">★</a>' ?><!--
                            -->
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
        



    

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>