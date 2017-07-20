<?php
require("../inc/init.inc.php");

// restriction d'accès, si l'utilisateur n'est pas admin alors il ne doit pas accéder à cette page
if(!utilisateur_admin())
{
    header('location:../connexion.php');
    exit(); // permet d'arrêter l'éxécution du script au cas où une personne malveillante ferait des injections via GET
}



// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
// echo '<pre>'; print_r($_FILES); echo '</pre>';
?>

    

    <div class="container">

      <div class="starter-template">
        <h1>Gestion des Salles</h1>
        <?php // echo $message; // message destinés à l'utilisateur ?>
        <?= $message; // cette balise php inclus un echo (equivalent à la ligne du dessus) ?>
        <hr>
        <a href="?action=ajout" class="btn btn-success">Ajouter une salle</a>
        <a href="?action=affichage" class="btn btn-primary">Afficher les salles</a>
      </div>

      <?php      
      if(isset($_GET['action']) && $_GET['action'] == 'affichage')
      {
        $articles = $pdo->query('SELECT * FROM article');
        echo '<hr />';

        // balise ouverture du tableau
        echo '<table border="1" style="width:80%; margin: 10px auto; border-collapse: collapse; text-align: center; background: rgba(238, 238, 238, 0.7); border: 1px solid #999;">';

            // première ligne du tableau pour le nom des colonnes
            echo '<tr>';

                // récupération du nombre de colonnes dans la requête:
                $nb_col = $articles->columnCount();

                for($i=0; $i<$nb_col; $i++)
                {
                    // echo '<pre>'; print_r($resultat->getColumnMeta($i)); echo '</pre>'; echo '<hr />';
                    $colonne = $articles->getColumnMeta($i); // on récupère les informations de la colonne en cours afin ensuite de demander le name
                    echo '<th style="padding: 10px; text-align: center;">' . $colonne['name'] . '</th>';
                }

                echo '<th style="padding: 10px; text-align: center;">Modifier</th>';
                echo '<th style="padding: 10px; text-align: center;">Supprimer</th>';
            echo '</tr>';

            while($ligne = $articles->fetch(PDO::FETCH_ASSOC))
            {
                echo '<tr>';

                foreach($ligne AS $indice => $article)
                {
                    if($indice == 'description')
                    {
                      echo '<td style="padding: 10px;">' . substr($article, 0, 43) . '</td>';
                    }
                    elseif($indice == 'photo')
                    {
                      echo '<td style="padding: 10px;"><img src="' . URL . 'photo/' . $article . '" width="120"></td>';
                    }
                    elseif($indice == 'prix')
                    {
                      echo '<td style="padding: 10px;">' . $article . '€</td>';
                    }
                    else
                    {
                      echo '<td style="padding: 10px;">' . $article . '</td>';
                    }

                    
                }
                echo '<td style="padding: 10px;"><a href="?action=modif&id_article=' . $ligne['id_article'] . '" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</a></td>';
                echo '<td style="padding: 10px;"><a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cette salle\'));" href="?action=suppr&id_article=' . $ligne['id_article'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer</a></td>';

                echo '</tr>';
            }

        echo '</table>';

      }

      ?>      
      
      <?php
      if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modif'))
      {
      ?>

      <div class="row">
        <div class="col-sm-5 col-sm-offset-1">
          <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
              <div class="col-sm-8">
                <!-- id_article caché via 'hidden' -->
                <input type="hidden" class="form-control" name="id_article" id="id_article" value="<?php echo $id_article ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="reference" class="col-sm-4 control-label">Référence</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="reference" id="reference" placeholder="Réf." value="<?php echo $reference ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="categorie" class="col-sm-4 control-label">Catégorie</label>
              <div class="col-sm-8">
                <select class="form-control" name="categorie" id="categorie">
                  <option value="vetement" <?php if($categorie == 'vetement') {echo 'selected';} ?> >Vêtements</option>
                  <option value="goodies" <?php if($categorie == 'goodies') {echo 'selected';} ?> >Goodies</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="titre" class="col-sm-4 control-label">Titre</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="titre" id="titre" placeholder="Titre" value="<?php echo $titre ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="description" class="col-sm-4 control-label">Description</label>
              <div class="col-sm-8">
               <textarea class="form-control" name="description" id="description" cols="41" rows="4" style="resize: none;"><?php echo $description ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="couleur" class="col-sm-4 control-label">Couleur</label>
              <div class="col-sm-8">
                <select class="form-control" name="couleur" id="couleur">
                  <option value=""></option>
                  <option value="noir" <?php if($couleur == 'noir') {echo 'selected';} ?> >Noir</option>
                  <option value="blanc" <?php if($couleur == 'blanc') {echo 'selected';} ?> >Blanc</option>
                  <option value="rouge" <?php if($couleur == 'rouge') {echo 'selected';} ?> >Rouge</option>
                  <option value="bleu" <?php if($couleur == 'bleu') {echo 'selected';} ?> >Bleu</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="taille" class="col-sm-4 control-label">Taille</label>
              <div class="col-sm-8">
                <select class="form-control" name="taille" id="taille">
                  <option value=""></option>
                  <option value="s" <?php if($taille == 's') {echo 'selected';} ?> >S</option>
                  <option value="m" <?php if($taille == 'm') {echo 'selected';} ?> >M</option>
                  <option value="l" <?php if($taille == 'l') {echo 'selected';} ?> >L</option>
                  <option value="xl" <?php if($taille == 'xl') {echo 'selected';} ?> >XL</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="sexe" class="col-sm-4 control-label">Sexe</label>
              <div class="col-sm-8">
                <select class="form-control" name="sexe" id="sexe">
                  <option value=""></option>
                  <option value="m" <?php if($sexe == 'm') {echo 'selected';} ?> >Homme</option>
                  <option value="f" <?php if($sexe == 'f') {echo 'selected';} ?> >Femme</option>
                </select>
              </div>
            </div>

            <?php
            // affichage de la photo actuelle dans le cas d'une modification d'article
              if(isset($article_actuelle)) // si cette variable existe alors nous sommes dans le cas d'une modification
              {
                echo '<div class="form-group">';
                echo '<label class="col-sm-4 control-label">Photo Actuelle</label>';
                echo '<img src="' . URL . 'photo/' . $photo_actuelle . '"class="img-thumbnail" width="130" />';
                // on crée un champ caché qui contiendra le nom de la photo afin de la récupérer lors de la validation du formulaire.
                echo '<input type="hidden" name="ancienne_photo" value="' . $photo_actuelle . '" />';
                echo '</div>';
              }
            ?>

            <div class="form-group">
              <label for="photo" class="col-sm-4 control-label">Photo produit</label>
              <div class="col-sm-8">
                <input type="file" name="photo" id="photo">
              </div>
            </div>
            <div class="form-group">
              <label for="prix" class="col-sm-4 control-label">Prix</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="prix" id="prix" placeholder="Prix HT" value="<?php echo $prix ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="stock" class="col-sm-4 control-label">Stock</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="stock" id="stock" placeholder="Stock" value="<?php echo $stock ?>">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-6 col-sm-10">
                <button type="submit" class="btn btn-primary" name="inscription" id="inscription">Enregistrer la salle <span class="glyphicon glyphicon-ok"></span></button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <?php
      }
      ?>

    </div><!-- /.container -->

    <?php
    require("../inc/footer.inc.php");