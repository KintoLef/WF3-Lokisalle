<?php
require_once("../inc/init.inc.php");

if(!utilisateur_admin()) 
{
    header("location:../connexion.php");
    exit(); // permet d'arrêter l'exécution du script
} 

//******************************************************************
//                SUPPRESSION D'UN PRODUIT
//******************************************************************
// mettre en place un contrôle pour savoir si l'utilisateur veut une suppression d'une salle.
if(isset($_GET['supprimer']) && !empty($_GET['id_produit']) && is_numeric($_GET['id_produit']))
{
    // is_numeric() permet de savoir si l'information est bien une valeur numérique sans tenir compte de son type (les informations provenant de GET et de POST sont toujours de type string)
    $id_produit = $_GET['id_produit'];
    $suppression = $pdo->prepare('DELETE FROM produit WHERE id_produit = :id_produit');
    $suppression->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $suppression->execute();

    $message .= '<div class="alert alert-success" role="alert" style="margin-top: 20px;">Le produit n°' . $id_produit . ' a bien été supprimé</div>';

}

$id_produit = "";
$id_salle = "";
$date_arrivee = "";
$date_depart = "";
$prix = "";

// variable de contrôle des erreurs
$erreur = "";

// *************************************************************
// RECUPERATION DES INFORMATIONS D'UN PRODUIT A MODIFIER
// *************************************************************

if(isset($_GET['modifier']) && !empty($_GET['id_produit']) && is_numeric($_GET['id_produit']))
{
    $id_produit = $_GET['id_produit'];
    $pdt_a_modifier = $pdo->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
    $pdt_a_modifier->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $pdt_a_modifier->execute();

    $pdt_actuel = $pdt_a_modifier->fetch(PDO::FETCH_ASSOC);

    $id_produit = $pdt_actuel['id_produit'];
    $id_salle = $pdt_actuel['id_salle'];
    $date_arrivee = $pdt_actuel['date_arrivee'];
    $date_depart = $pdt_actuel['date_depart'];
    $prix = $pdt_actuel['prix'];
    
}

//******************************************************************
//        ENREGISTREMENT DES SALLES DANS LA BDD
//******************************************************************

// contrôle sur l'existence de tous les champs provenant du formulaire (sauf le bouton de validation)
if(isset($_POST['salle']) && isset($_POST['date_arrivee']) && isset($_POST['date_depart']) && isset($_POST['prix']))
{
    // si le formulaire a été validé, on place dans ces variables les saisies correspondantes
    $id_salle = $_POST['salle'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];
    $prix = $_POST['prix'];

    // vérification si la salle est bien disponible aux dates définies   
    $dates = getDatesFromRange($date_arrivee, $date_depart);
    foreach($dates AS $check_date)
    {
        $verif_dispo = $pdo->prepare("SELECT * FROM produit WHERE id_salle = :id_salle AND $check_date != :date_arrivee AND $check_date != :date_depart");
            
        $verif_dispo->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
        $verif_dispo->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
        $verif_dispo->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
        $verif_dispo->execute();
        if($verif_dispo->rowCount() > 1 && isset($_GET['action']) && $_GET['action'] == 'ajout')
        {
            // si l'on obtient au moins 1 ligne de resultat alors la référence est déjà prise.
            $message = '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la salle et/ou les dates sont indisponibles<br />Veuillez vérifier votre saisie</div>';
            $erreur = true;
        }
    }
        
    // insertion dans la BDD
    if($erreur !== true) // si $erreur est différent de true lors les contrôles préalables sont ok
    {
    
        if(isset($_GET['action']) && $_GET['action'] == 'ajout')
        {
        $enregistrement_produit = $pdo->prepare("INSERT INTO produit(id_salle, date_arrivee, date_depart, prix) VALUES(:id_salle, :date_arrivee, :date_depart, :prix)");
        }
        elseif(isset($_GET['modifier']))
        {
        $enregistrement_produit = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix WHERE id_produit = :id_produit");
        $id_produit = $_POST['id_produit'];
        $enregistrement_produit->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
        }


        $enregistrement_produit->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
        $enregistrement_produit->bindParam(":prix", $prix, PDO::PARAM_STR);
        $enregistrement_produit->execute();

        
    }

}

require_once("../inc/head.inc.php");
require_once("../inc/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Gestion des produits</h1>
        <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>
        <br>
        <a href="?action=ajout" class="btn btn-primary">Ajouter un produit</a>
    </div>
    <?php
        // affichage de tous les produits dans un tableau html
        $products = $pdo->query('SELECT p.id_produit AS "id produit", s.photo, DATE_FORMAT(p.date_arrivee, "%d-%m-%Y %H:%i") AS "date d\'arrivée", DATE_FORMAT(p.date_depart, "%d-%m-%Y %H:%i") AS "date de départ", s.id_salle AS "id salle", s.titre, p.prix, p.etat FROM produit p, salle s WHERE p.id_salle = s.id_salle');
        echo '<hr />';

        // balise ouverture du tableau
        echo '<table border="1">';

            // première ligne du tableau pour le nom des colonnes
            echo '<tr>';

                // récupération du nombre de colonnes dans la requête:
                $nb_col = $products->columnCount();

                for($i=0; $i<$nb_col; $i++)
                {
                    // echo '<pre>'; print_r($resultat->getColumnMeta($i)); echo '</pre>'; echo '<hr />';
                    $colonne = $products->getColumnMeta($i); // on récupère les informations de la colonne en cours afin ensuite de demander le name
                    if($colonne['name'] != 'titre' && $colonne['name'] != 'photo') 
                    echo '<th>' . $colonne['name'] . '</th>';
                }

                echo '<th>action</th>';
            echo '</tr>';

            while($ligne = $products->fetch(PDO::FETCH_ASSOC))
            {
                echo '<tr>';

                foreach($ligne AS $indice => $produit)
                {
                    if($indice == 'id salle')
                    {
                        echo '<td style="width: 200px;">' . $ligne['id salle'] . ' - Salle ' . $ligne['titre'] . '<br/>' . '<img src="' . URL . 'photo/' . $ligne['photo'] . '" width="100" /></td>';
                    }
                    elseif($indice != 'photo' && $indice != 'titre' && $indice != 'id salle')
                    {
                        echo '<td>' . $produit . '</td>';
                    }

                    

                    
                }
                echo '<td><a href="../fiche_produit.php?id_produit=' . $ligne['id produit'] . '" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></a> <a href="?modifier&id_produit=' .  $ligne['id produit'] .'" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a> <a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer ce produit\'));" href="?supprimer&id_produit=' . $ligne['id produit'] .'" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>';

                echo '</tr>';
            }

        echo '</table>';
      
    ?>
    
    <?php

    if(isset($_GET['action']) && $_GET['action'] == 'ajout' || isset($_GET['modifier']))
    {
    ?>

    <form method="post" action="">
        <div class="row">
            <div class="col-md-6">
                <!-- Input vide pour récupérer l'id_membre via $_GET -->
                <input type="hidden" class="form-control" id="id_produit" name="id_produit" value="<?php echo $id_produit; ?>">
                <label class="gestion_membre" for="date_arrivee">Date d'arrivée</label>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" id="date_arrivee" name="date_arrivee" aria-describedby="basic-addon1" value="<?php echo $date_arrivee; ?>">
                </div>
                <label class="gestion_membre" for="date_depart">Date de départ</label>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" id="date_depart" name="date_depart" aria-describedby="basic-addon1" value="<?php echo $date_depart; ?>">
                </div>                
            </div>

            <div class="col-md-6">
                <label class="gestion_membre" for="salle">Salle</label>
                <div class="input-group">
                    <span class="input-group" id="basic-addon1"></span>
                    <select name="salle" id="salle" class="form-control">
                        <?php
                            $salle_produit = $pdo->query("SELECT * FROM salle");
                            while($ligne = $salle_produit->fetch(PDO::FETCH_ASSOC))
                            {
                                echo '<option value="' . $ligne['id_salle'] .'"';
                                if($id_salle == $ligne['id_salle']) {echo 'selected';}                                
                                echo '>' . $ligne['id_salle'] . ' - ' . $ligne['titre'] . '</option>';
                            }
                        ?>
                    </select>
                </div>
                
                <label class="gestion_membre" for="prix">Prix</label>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-euro"></span></span>
                    <input type="text" class="form-control" id="prix" name="prix" placeholder="Prix en euros" aria-describedby="basic-addon1" value="<?php echo $prix; ?>">
                </div>
               
                <button type="submit" class="btn btn-primary btn-membre pull-right">Enregistrer le produit</button>    
            </div>
        </div>
    </form>
    <?php
    }
    ?>

</div><!-- /.container -->
        
<?php
require_once("../inc/footer.inc.php");
?>