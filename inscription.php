<?php
require_once("inc/init.inc.php");

// Vérification si l'utilisateur est connecté dans ce cas on le redirige sur profil
if(utilisateur_connecte())
{
    // header doit être placé avant tout affichage, il masque aussi les erreurs
    header("location:profil.php");
}

// Déclaration de variables vides pour affichage dans les values du formulaire
$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$civilite = "";

// Contrôle de l'existence de tous les champs présents dans le formulaire
if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite'])) 
{
    // On récupère les données saisies par l'utilisateur dans des variables
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $civilite = $_POST['civilite'];

    // Variable de contrôle des erreurs
    $erreur = "";

    // Contrôle sur la taille du pseudo (entre 4 et 14 caractères)
    // On rentre la taille du pseudo dans une variable
    $taille_pseudo = iconv_strlen($pseudo);
    if($taille_pseudo < 4 || $taille_pseudo > 14)
    {
      $message .= '<div class="alert alert-danger" role="alert">Attention, la taille du pseudo est incorrecte.<br />En effet, le pseudo doît être compris entre 4 et 14 caractères inclus.</div>';
      $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur.
    }

    // Vérification des caractères contenus dans le pseudo à l'aide d'une expression régulière
    // Contrôle des caractères dans le pseudo (autorisés a-z A-Z 0-9 _ - .)
    $verif_caracteres = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
    
    // On vérifie si les caractères sont autorisés
    if(!$verif_caracteres && !empty($pseudo)) 
    {
      // On rentre dans cette condition si verif_caracteres contient 0 donc s'il y a des caractères non-autorisés
      $message .= '<div class="alert alert-danger" role="alert">Attention, caractères non autorisés dans le pseudo.<br />Caractères autorisés: A-Z ET 0-9.</div>';
      $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur
    }

    // Vérification de l'adresse email
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) &&!empty($email))
    {
      $message .= '<div class="alert alert-danger" role="alert">Attention, votre adresse email est vide ou invalide.<br />Veuillez la saisir à nouveau.</div>';
      $erreur = true;
    }

    // Contrôle sur la disponibilité du pseudo en bdd
    $disponibilite_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $disponibilite_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $disponibilite_pseudo->execute();

    // On vérifie si la requête $verif_pseudo renvoie au moins une ligne de résultat
    if($disponibilite_pseudo->rowCount() > 0)
    {
      $message .= '<div class="alert alert-danger" role="alert">Attention, votre pseudo n\'est pas disponible.<br />Veuillez en saisir un nouveau.</div>';
      $erreur = true; // Si l'on rentre dans cette condition alors il y a une erreur
    }

    // S'il n'y a aucune erreur
    if($erreur !== true)
    {
      $insertion = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())");
      $insertion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
      $insertion->bindParam(":mdp", $mdp, PDO::PARAM_STR);
      $insertion->bindParam(":nom", $nom, PDO::PARAM_STR);
      $insertion->bindParam(":prenom", $prenom, PDO::PARAM_STR);
      $insertion->bindParam(":email", $email, PDO::PARAM_STR);
      $insertion->bindParam(":civilite", $civilite, PDO::PARAM_STR);
      $insertion->execute();

      // On redirige sur la page connexion
      header("location:connexion.php");
    }
}

require_once("inc/head.inc.php");
require_once("inc/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
?>

<div class="container">

    <div class="starter-template">
        <h1>Inscription</h1>
    </div>
    <?= $message; // cette balise php inclut un echo (equivalent à la ligne du dessus) ?>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
          <form method="post" action="">
            <div class="form-group">
              <label for="pseudo">Pseudo:</label>
              <!-- On met les value="" pour rafraichir le champ à chaque submit -->
              <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
            </div>  
            <div class="form-group">
              <label for="mdp">Mot de passe:</label>
              <input type="text" class="form-control" id="mdp" name="mdp" value="<?php echo $mdp; ?>">
            </div>   
            <div class="form-group">
              <label for="nom">Nom:</label>
              <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>">
            </div>
            <div class="form-group">
              <label for="prenom">Prénom:</label>
              <input type="text" class="form-control" id="prénom" name="prenom" value="<?php echo $prenom; ?>">
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
              <label for="civilite">Civilité:</label>
              <select class="form-control" name="civilite" id="civilite">
                <option value="m">Homme</option>
                <option value="f" <?php if($civilite == "f") { echo 'selected'; } ?>>Femme</option>
              </select>
            </div>
            <div class="form-group">
              <button type="submit" name="inscription" id="inscription" class="btn btn-primary form-control">S'inscrire</button>
            </div>
              
          </form>
        </div> <!-- /.col-sm-4 -->
      </div> <!-- /.row -->

</div><!-- /.container -->
        
<?php
require_once("inc/footer.inc.php");
?>