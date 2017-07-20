<?php
require_once("../inc/init.inc.php");

// restriction d'accès, si l'utilisateur n'est pas admin alors il ne doit pas accéder à cette page
if(!utilisateur_admin())
{
    header('location:../connexion.php');
    exit(); // permet d'arrêter l'éxécution du script au cas où une personne malveillante ferait des injections via GET
}

$id_salle = "";
$titre = "";
$description = "";
$capacite = "";
$categorie = "";
$pays = "";
$ville = "";
$adresse = "";
$cp = "";
$photo_bdd = "";

// variable de contrôle des erreurs
$erreur = "";

//******************************************************************
//        ENREGISTREMENT DES SALLES DANS LA BDD
//******************************************************************

// contrôle sur l'existence de tous les champs provenant du formulaire (sauf le bouton de validation)
if(isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['capacite']) && isset($_POST['categorie']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']))
{
  // si le formulaire a été validé, on place dans ces variables les saisies correspondantes
  $id_salle = $_POST['id_salle'];
  $titre = $_POST['titre'];
  $description = $_POST['description'];
  $capacite = $_POST['capacite'];
  $categorie = $_POST['categorie'];
  $pays = $_POST['pays'];
  $ville = $_POST['ville'];
  $adresse = $_POST['adresse'];
  $cp = $_POST['cp'];


  // contrôle sur disponibilité du titre si on est dans le cas d'un ajout
  $dispo_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
  $dispo_titre->bindParam(":titre", $titre, PDO::PARAM_STR);
  $dispo_titre->execute();

  if($dispo_titre->rowCount()>0 && isset($_GET['action']) && $_GET['action'] == 'ajout')
  {
    // si l'on obtient au moins 1 ligne de resultat alors le pseudo est déjà pris.
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, cette salle existe déjà, veuillez changer le titre</div>';
    $erreur = true;

  }

  // vérification si le titre n'est pas vide
  if(empty($titre))
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, vous n\'avez pas renseigné de titre !!</div>';
    $erreur = true;
  }

  // récupération de l'ancienne photo (photo actuelle) dans le cas d'une modif
  if(isset($_GET['action']) && $_GET['action'] == 'modif')
  {
    if(isset($_POST['ancienne_photo']))
    {
      $photo_bdd = $_POST['ancienne_photo'];
    }
  }

  // vérification si l'utilisateur a chargé une image
  if(!empty($_FILES['photo']['name']))
  {
    // si ce n'est pas vide alors un fichier a bien été chargé via le formulaire

    // on concatène l'id sur le titre afin de ne jamais avoir un fichier avec un nom déjà existant sur le serveur
    $photo_bdd = $titre . '_' . $_FILES['photo']['name'];

    // vérification de l'extension de l'image (jpg, jpeg, png, gif)
    $extension = strrchr($_FILES['photo']['name'], '.'); // cette fonction prédéfinie permet de découper une chaîne selon le caractère fourni en 2ème argument ('.'). Attention, cette fonction découpera la chaîne à partir de la dernière occurance du 2ème argument.

    $extension = strtolower($extension); // on transforme $extension afin que tous les caractères soient en minuscule
    $extension = substr($extension, 1); // ex: .jpg -> jpg
    $tab_extension_valide = array('jpg', 'jpeg', 'png', 'gif'); // les extensions acceptées

    // on va maintenant vérifier l'extension
    $verif_extension = in_array($extension, $tab_extension_valide); // in_array vérifie si une valeur fournie en 1er argument fait partie des valeurs contenues dans un tableau array fournie en 2ème argument

    if($verif_extension && !$erreur)
    {
      // si $verif_extension est égal à true et que $erreur est égal à false (il n'y en a pas)
      $photo_dossier = RACINE_SERVER . 'photo/' . $photo_bdd;

      copy($_FILES['photo']['tmp_name'], $photo_dossier); // copy() permet de copier un fichier depuis un emplacement fourni en premier argument vers un autre emplacement fourni en deuxième argument
    }
    elseif(!$verif_extension)
    {
      $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, l\'extension n\'est pas au bon format</div>';
      $erreur = true;
    }
  }

  // insertion dans la BDD
  if($erreur !== true) // si $erreur est différent de true lors les contrôles préalables sont ok
  {
    
    if(isset($_GET['action']) && $_GET['action'] == 'ajout')
    {
      $enregistrement_salle = $pdo->prepare("INSERT INTO salle(titre, description, photo, capacite, categorie, pays, ville, adresse, cp) VALUES(:titre, :description, :photo, :capacite, :categorie, :pays, :ville, :adresse, :cp)");
    }
    elseif(isset($_GET['action']) && $_GET['action'] == 'modif')
    {
      $enregistrement_salle = $pdo->prepare("UPDATE salle SET  WHERE id_salle = :id_salle");
      $id_salle = $_POST['id_salle'];
      $enregistrement_salle->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
    }


    $enregistrement_salle->bindParam(":titre", $titre, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":description", $description, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":photo", $photo_bdd, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":capacite", $capacite, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":categorie", $categorie, PDO::PARAM_STR);    
    $enregistrement_salle->bindParam(":pays", $pays, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":ville", $ville, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":adresse", $adresse, PDO::PARAM_STR);
    $enregistrement_salle->bindParam(":cp", $cp, PDO::PARAM_STR);    
    $enregistrement_salle->execute();

    
  }

}

// la ligne suivante commence les affichages dans la page
require_once("../inc/head.inc.php");
require_once("../inc/nav.inc.php");
echo '<pre>'; print_r($_POST); echo '</pre>';
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
      // affichage de tous les produits dans un tableau html
      if(isset($_GET['action']) && $_GET['action'] == 'affichage')
      {
        $salles = $pdo->query('SELECT * FROM salle');
        echo '<hr />';

        // balise ouverture du tableau
        echo '<table border="1" style="width:80%; margin: 10px auto; border-collapse: collapse; text-align: center; background: rgba(238, 238, 238, 0.7); border: 1px solid #999;">';

            // première ligne du tableau pour le nom des colonnes
            echo '<tr>';

                // récupération du nombre de colonnes dans la requête:
                $nb_col = $salles->columnCount();

                for($i=0; $i<$nb_col; $i++)
                {
                    // echo '<pre>'; print_r($resultat->getColumnMeta($i)); echo '</pre>'; echo '<hr />';
                    $colonne = $salles->getColumnMeta($i); // on récupère les informations de la colonne en cours afin ensuite de demander le name
                    echo '<th style="padding: 10px; text-align: center;">' . $colonne['name'] . '</th>';
                }

                echo '<th>action</th>';
            echo '</tr>';

            while($ligne = $salles->fetch(PDO::FETCH_ASSOC))
            {
                echo '<tr>';

                foreach($ligne AS $indice => $salle)
                {
                    if($indice == 'description')
                    {
                      echo '<td>' . substr($salle, 0, 50) . '...</td>';
                    }
                    elseif($indice == 'photo')
                    {
                      echo '<td><img src="' . URL . 'photo/' . $salle . '" width="120"></td>';
                    }
                    else
                    {
                      echo '<td>' . $salle . '</td>';
                    }

                    
                }
                echo '<td><a href="?action=modif&id_article=' . $ligne['id_salle'] . '" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</a><a onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer cet article\'));" href="?action=suppr&id_article=' . $ligne['id_salle'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer</a></td>';

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
        <div class="col-sm-4 col-sm-offset-4">
          <form class="form" method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <!-- id_article caché via 'hidden' -->
                <input type="hidden" class="form-control" name="id_salle" id="id_salle" value="<?php echo $id_salle ?>">
            </div>

            <div class="form-group">
                <label for="titre" class="control-label">Titre</label>
                <input type="text" class="form-control" name="titre" id="titre" value="<?php echo $titre ?>">
            </div>

            <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <textarea class="form-control" name="description" id="description" cols="41" rows="4" style="resize: none;"><?php echo $description ?></textarea>
            </div>

            <div class="form-group">
                <label for="photo" class="control-label">Photo produit</label>
                <input type="file" name="photo" id="photo">
            </div>

            <div class="form-group">
                <label for="capacite" class="control-label">Capacité</label>
                <select name="capacite" id="capacite">
                    <?php
                    for($i=1; $i<=20; $i++)
                    {
                    ?>
                        <option <?php if($capacite == $i) {echo 'selected';} ?> ><?php echo $i ?></option>
                        
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="categorie" class="control-label">Catégorie</label>
                <select name="categorie" id="categorie">
                    <option value="" disabled></option>
                    <option <?php if($categorie == 'Reunion') {echo 'selected';} ?> >Reunion</option>
                    <option <?php if($categorie == 'Formation') {echo 'selected';} ?> >Formation</option>
                    <option <?php if($categorie == 'Bureau') {echo 'selected';} ?> >Bureau</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pays" class="control-label">Pays</label>
                <select name="pays" id="pays">
                    <option <?php if($pays == 'France') {echo 'selected';} ?> >France</option>
                    <option <?php if($pays == 'Angleterre') {echo 'selected';} ?> >Angleterre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ville" class="control-label">Ville</label>
                <select name="ville" id="ville">                    
                    <option <?php if($ville == 'Paris') {echo 'selected';} ?> >Paris</option>
                    <option <?php if($ville == 'Nantes') {echo 'selected';} ?> >Nantes</option>                
                    <option <?php if($ville == 'Londres') {echo 'selected';} ?> >Londres</option>
                    <option <?php if($ville == 'Oxford') {echo 'selected';} ?> >Oxford</option>                   
                </select>
            </div>

            <div class="form-group">
                <label for="adresse" class="control-label">Adresse</label>
               <textarea class="form-control" name="adresse" id="adresse" cols="41" rows="4" style="resize: none;"><?php echo $adresse ?></textarea>
            </div>

            <div class="form-group">
                <label for="cp" class="control-label">Code Postal</label>
                <input type="text" class="form-control" name="cp" id="cp" value="<?php echo $cp ?>">
            </div>

            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-10">
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
    <br>
    <?php
    require("../inc/footer.inc.php");